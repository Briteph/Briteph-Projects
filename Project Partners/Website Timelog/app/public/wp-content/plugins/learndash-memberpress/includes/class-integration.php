<?php
/**
* Integration class
*/
class Learndash_Memberpress_Integration
{
	
	public function __construct()
	{
		add_action( 'mepr-product-options-tabs', array( $this, 'learndash_tab' ) );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
		add_action( 'mepr-product-options-pages', array( $this, 'learndash_tab_page' ) );
		add_action( 'mepr-membership-save-meta', array( $this, 'save_post_meta' ) );

		// Associate or disasociate course when MP transaction status is changed
		add_action( 'mepr-txn-transition-status', array( $this, 'transaction_transition_status' ), 10, 3 );
		// Disassociate course when MP transaction is expired
		add_action( 'mepr-transaction-expired', array( $this, 'transaction_expired' ), 10, 2 );
		// Disassociate course when MP transaction is deleted
		add_action( 'mepr_pre_delete_transaction', array( $this, 'delete_transaction' ), 10, 1 );

		// Associate or disasociate course when MP subscription status is changed
		add_action( 'mepr_subscription_transition_status', array( $this, 'subscription_transition_status' ), 10, 3 );
		// Disassociate course when MP subscription is deleted
		add_action( 'mepr_subscription_pre_delete', array( $this, 'delete_subscription' ), 10, 1 );

		// Corporate account hooks
		// Remove access on corporate account removal
		add_action( 'delete_user_meta', array( $this, 'remove_corporate_account_access' ), 10, 4 );
	}

	/**
	 * Output new tab for LearnDash on MemberPress membership edit screen
	 * 
	 * @param  array  $product MemberPress product information
	 */
	public function learndash_tab( $product )
	{
		?>

		<a class="nav-tab main-nav-tab" href="#" id="learndash"><?php _e( 'LearnDash', 'learndash-memberpress' ); ?></a>

		<?php
	}

	public function admin_enqueue_scripts()
	{
		wp_enqueue_script( 'learndash-memberpress-edit-membership', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/edit-membership.js', [ 'learndash-select2-jquery-script' ], LEARNDASH_MEMBERPRESS_VERSION, true );

		wp_enqueue_style( 'learndash-memberpress-edit-membership', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/edit-membership.css', [], LEARNDASH_MEMBERPRESS_VERSION );
	}

	/**
	 * Output tab content for LearnDash tab on MemberPress membership edit screen
	 * 
	 * @param  array  $product MemberPress product information
	 */
	public function learndash_tab_page( $product )
	{
		$saved_objects = self::get_membership_associated_objects( $product->rec->ID );

		$courses = $this->get_learndash_courses();
		$groups  = $this->get_learndash_groups();
		?>
		
		<div class="product_options_page learndash">
			<div class="product-options-panel">
				<div class="ld-memberpress-options">
					<p><?php printf( __( '<strong>Important:</strong> When adding five or more courses/groups to a single membership, course enrollment process is done in the background and you will need to set up a cron job. To set up a cron job please follow <a href="%s" target="_blank">these steps</a>.', 'learndash-memberpress' ), 'https://www.learndash.com/support/docs/faqs/email-notifications-send-time/#create-cron-job-in-cpanel' ); ?></p>
					<p><strong><?php echo learndash_get_custom_label( 'courses' ); ?></strong></p>
					<div class="ld-memberpress-course-options">
						<select name="_learndash_memberpress_courses[]" id="learndash-memberpress-courses" multiple="multiple" class="select2">
							<?php foreach( $courses as $course ) : ?>
								<option value="<?php echo esc_attr( $course->ID ) ?>" <?php $this->selected_course( $course->ID, $saved_objects ); ?>><?php echo $course->post_title; ?></option>
							<?php endforeach; ?>
						</select>
						<p class="description"><?php printf( __( 'Click the box to start adding %s.', 'learndash-memberpress' ), learndash_get_custom_label_lower( 'courses' ) ); ?></p>
					</div>
					<p><strong><?php echo learndash_get_custom_label( 'groups' ); ?></strong></p>
					<div class="ld-memberpress-group-options">
					    <select name="_learndash_memberpress_groups[]" id="learndash-memberpress-groups" multiple="multiple" class="select2">
					        <?php foreach( $groups as $group ) : ?>
					            <option value="<?php echo esc_attr( $group->ID ) ?>" <?php $this->selected_course( $group->ID, $saved_objects ); ?>><?php echo $group->post_title; ?></option>
					        <?php endforeach; ?>
					    </select>
					    <p class="description"><?php printf( __( 'Click the box to start adding %s.', 'learndash-memberpress' ), learndash_get_custom_label_lower( 'groups' ) ); ?></p>
					</div>
				</div>
			</div>
		</div>

		<?php
	}

	/**
	 * Save LearnDash post meta for MemberPress membership post object
	 * 
	 * @param  array  $product MemberPress product information
	 */
	public function save_post_meta( $product )
	{
		$selected_courses = ! empty( $_POST['_learndash_memberpress_courses'] ) ? $_POST['_learndash_memberpress_courses'] : [];
		$old_courses = get_post_meta( $product->rec->ID, '_learndash_memberpress_courses', true );
		$new_courses = array_unique( (array) array_map( 'sanitize_text_field', $selected_courses ) );

		$selected_groups = ! empty( $_POST['_learndash_memberpress_groups'] ) ? $_POST['_learndash_memberpress_groups'] : [];
		$old_groups = get_post_meta( $product->rec->ID, '_learndash_memberpress_groups', true );
		$new_groups = array_unique( (array) array_map( 'sanitize_text_field', $selected_groups ) );

		// Update associated course in DB so that it will be executed in cron
		$course_update_queue = get_option( 'learndash_memberpress_course_access_update', array() );

		$course_update_queue[ $product->rec->ID ] = array(
			// 'membership_id' => $product->rec->ID,
			'old_courses'   => $old_courses,
			'new_courses'   => $new_courses,
			'old_groups'    => $old_groups,
			'new_groups'    => $new_groups,
		);

		update_option( 'learndash_memberpress_course_access_update', $course_update_queue );

		update_post_meta( $product->rec->ID, '_learndash_memberpress_courses', $new_courses );
		update_post_meta( $product->rec->ID, '_learndash_memberpress_groups', $new_groups );
	}

	/**
	 * Cron job: update user course access
	 */
	public static function cron_update_course_access()
	{
		// Get course update queue
		$updates = get_option( 'learndash_memberpress_course_access_update', array() );

		foreach ( $updates as $membership_id => $update ) {
			// Get transactions from DB with membership_id
			global $wpdb;
			$mepr_db = new MeprDb();

			$per_batch = apply_filters( 'learndash_memberpress_cron_update_course_access_per_batch', 100 );
			$per_batch = intval( $per_batch / 2 );
			$batch = $update['batch'] ?? 1;
			$offset = ( $batch - 1 ) * $per_batch;

			// Transactions
			$query = "SELECT id, user_id, trans_num, subscription_id, created_at FROM {$mepr_db->transactions} WHERE status = 'complete' AND product_id = {$membership_id} LIMIT {$per_batch} OFFSET {$offset}";
			$transactions = $wpdb->get_results( $query, OBJECT );

			$old_courses = $update['old_courses'] ?? [];
			$new_courses = $update['new_courses'] ?? [];

			$old_groups = $update['old_groups'] ?? [];
			$new_groups = $update['new_groups'] ?? [];

			// Remove or give access for each transaction
			foreach ( $transactions as $transaction ) {
				if ( empty( $transaction->subscription_id ) ) {
					foreach ( $old_courses as $course_id ) {
						self::remove_access( $course_id, $transaction->user_id, $transaction->id );
					}

					foreach ( $new_courses as $course_id ) {
						self::add_access( $course_id, $transaction->user_id, $transaction->id, 'transaction' );
					}

					foreach ( $old_groups as $group_id ) {
						self::remove_access( $group_id, $transaction->user_id, $transaction->id );
					}

					foreach ( $new_groups as $group_id ) {
						self::add_access( $group_id, $transaction->user_id, $transaction->id, 'transaction' );
					}
				}
			}

			// Subscriptions
			$query = "SELECT id, user_id, subscr_id, created_at FROM {$mepr_db->subscriptions} WHERE status = 'active' AND product_id = {$membership_id} LIMIT {$per_batch} OFFSET {$offset}";
			$subscriptions = $wpdb->get_results( $query, OBJECT );

			// Remove or give access for each subscription
			foreach ( $subscriptions as $subscription ) {
				foreach ( $old_courses as $course_id ) {
					self::remove_access( $course_id, $subscription->user_id, $subscription->id );
				}

				foreach ( $new_courses as $course_id ) {
					self::add_access( $course_id, $subscription->user_id, $subscription->id, 'subscription' );
				}

				foreach ( $old_groups as $group_id ) {
					self::remove_access( $group_id, $subscription->user_id, $subscription->id );
				}

				foreach ( $new_groups as $group_id ) {
					self::add_access( $group_id, $subscription->user_id, $subscription->id, 'subscription' );
				}
			}

			if ( ! empty( $transactions ) || ! empty( $subscriptions ) ) {
				$updates[ $membership_id ]['batch'] = $batch + 1;
				// Bail, still processing the same membership ID
				break;
			}

			unset( $updates[ $membership_id ] );
			// Not necessary to bail since it can handle the next iteration since current iteration processes 0 transaction
		}
		update_option( 'learndash_memberpress_course_access_update', $updates );
	}

	/**
	 * Change LearnDash course status if MemberPress txn status is changed
	 *
	 * @param  string $old_status 	Old status of a transaction
	 * @param  string $new_status 	New status of a transaction
	 * @param  array  $txn 		  	Transaction data	 
	 */
	public function transaction_transition_status( $old_status, $new_status, $txn )
	{
		$subscription = $txn->subscription();

		if ( $subscription ) {
			if ( $subscription->first_txn_id != $txn->id ) {
				return;
			}
		}

		$ld_objects = self::get_membership_associated_objects( $txn->rec->product_id );

		// If no LearnDash object associated, exit
		if ( empty( $ld_objects ) ) {
			return;
		}

		if ( ( $txn->txn_type == 'sub_account' || $old_status != 'complete' ) && $new_status == 'complete' ) {
			if ( count( $ld_objects ) >= self::get_min_courses_count_for_silent_course_enrollment() ) {
				self::enqueue_silent_course_access_update( array( 'transaction_id' => $txn->id, 'action' => 'enroll' ) );
			} else {
				foreach ( $ld_objects as $object_id ) {
					self::add_access( $object_id, $txn->rec->user_id, $txn->id, 'transaction' );
				}
			}

		} elseif ( $old_status == 'complete' && $new_status != 'complete' ) {
			if ( count( $ld_objects ) >= self::get_min_courses_count_for_silent_course_enrollment() ) {
				self::enqueue_silent_course_access_update( array( 'transaction_id' => $txn->id, 'action' => 'unenroll' ) );
			} else {
				foreach ( $ld_objects as $object_id ) {
					self::remove_access( $object_id, $txn->rec->user_id, $txn->id );
				}
			}

		}
	}

	/**
	 * Fired when a MP transaction is expired
	 * 
	 * @param  object $txn        MP transaction object
	 * @param  string $sub_status Subscription status
	 */
	public function transaction_expired( $txn, $sub_status )
	{
		$ld_objects = self::get_membership_associated_objects( $txn->rec->product_id );

		// If no LearnDash object associated, exit
		if ( empty( $ld_objects ) ) { 
			return; 
		}

		// Make sure user is really expired
		$user = new MeprUser( $txn->user_id );
		$subs = $user->active_product_subscriptions( 'ids' );

		if ( ! empty( $subs ) && in_array( $txn->product_id, $subs ) ) { 
			return; 
		}

		foreach ( $ld_objects as $object_id ) { 
			self::remove_access( $object_id, $txn->rec->user_id, $txn->rec->id ); 
		}
	}

	/**
	 * Delete LearnDash course association if transaction is deleted
	 * 
	 * @param  int|bool $query Result of $wpdb->query
	 * @param  array 	$args  Args of transaction
	 */
	public function delete_transaction( $txn )
	{
		if ( $txn->subscription() ) {
			return;
		}

		// Bail if the transaction is not complete
		if ( $txn->rec->status != 'complete' ) {
			return;
		}

		$ld_objects = self::get_membership_associated_objects( $txn->product_id );

		// If no LearnDash group associated, exit
		if ( empty( $ld_objects ) ) {
			return;
		}
		
		foreach ( $ld_objects as $object_id ) {
			self::remove_access( $object_id, $txn->user_id, $txn->rec->id );
		}
	}

	/**
	 * Change LearnDash course status if MemberPress subscription status is changed
	 *
	 * @param  string $old_status 	Old status of a transaction
	 * @param  string $new_status 	New status of a transaction
	 * @param  array  $txn 		  	Transaction data	 
	 */
	public function subscription_transition_status( $old_status, $new_status, $subscription )
	{
		$ld_objects = self::get_membership_associated_objects( $subscription->product_id );

		// If no LearnDash object associated, exit
		if ( empty( $ld_objects ) ) {
			return;
		}

		if ( $new_status == 'active' ) {
			$first_txn  = $subscription->first_txn();
			
			/**
			 * Prevent course enrollment when subscription status is active
			 * even though the payment is stil pending (offline payments)
			 */
			if ( $first_txn && $first_txn->txn_type == 'payment' && $first_txn->status !== 'complete' && ! $subscription->trial && $subscription->trial_amount != '0.00' ) {
				return;
			}

			if ( count( $ld_objects ) >= self::get_min_courses_count_for_silent_course_enrollment() ) {
				self::enqueue_silent_course_access_update( array( 'subscription_id' => $subscription->id, 'action' => 'enroll' ) );
			} else {
				foreach ( $ld_objects as $object_id ) {
					self::add_access( $object_id, $subscription->user_id, $subscription->id, 'subscription' );
				}
			}
		} elseif ( $new_status != 'active' ) {
			// Exit if subscription is not expired yet
			if ( ! $subscription->is_expired() ) {
				return;
			}

			if ( count( $ld_objects ) >= self::get_min_courses_count_for_silent_course_enrollment() ) {
				self::enqueue_silent_course_access_update( array( 'subscription_id' => $subscription->id, 'action' => 'unenroll' ) );
			} else {
				foreach ( $ld_objects as $object_id ) {
					self::remove_access( $object_id, $subscription->user_id, $subscription->id );
				}
			}
		}
	}

	/**
	 * Delete LearnDash course association if subscription is deleted
	 *
	 * 
	 * @param  int|bool $subscription_id   MP subscription ID
	 */
	public function delete_subscription( $subscription_id )
	{
		$subscription = new MeprSubscription( $subscription_id );

		if ( ! $subscription || $subscription->status != 'active' ) {
			return;
		}

		$ld_objects = self::get_membership_associated_objects( $subscription->product_id );

		// If no LearnDash object associated, exit
		if ( empty( $ld_objects ) ) {
			return;
		}
		
		foreach ( $ld_objects as $object_id ) {
			self::remove_access( $object_id, $subscription->user_id, $subscription->id );
		}
	}

	/**
	 * Remove corporate account access
	 *
	 * Hooked to delete_user_meta since there's no available hook in the plugin.
	 * 
	 * @param  array  $meta_ids  
	 * @param  int    $user_id 
	 * @param  string $meta_key  
	 * @param  string $meta_value
	 */
	public function remove_corporate_account_access( $meta_ids, $user_id, $meta_key, $meta_value ) {
		if ( $meta_key != 'mpca_corporate_account_id' ) {
			return;
		}

		$ca  = new MPCA_Corporate_Account( $meta_value );
		$txn = $ca->get_user_sub_account_transaction( $user_id );

		$ld_objects = self::get_membership_associated_objects( $txn->rec->product_id );

		// If no LearnDash object associated, exit
		if ( empty( $ld_objects ) ) {
			return;
		}

		foreach ( $ld_objects as $object_id ) {
			self::remove_access( $object_id, $user_id, $txn->rec->id );
		}
	}

	/**
	 * Add course/group access
	 * 
	 * @param int 	 $object_id    ID of an object, course or group
	 * @param int 	 $user_id      ID of a user
	 * @param int 	 $order_id     Subscription ID or Transaction ID
	 * @param string $order_type   Type of $order_id, 'subscription' or 'transaction'
	 */
	public static function add_access( $object_id, $user_id, $order_id, $order_type )
	{
		self::increment_access_counter( $object_id, $user_id, $order_id );

		$type = get_post_type( $object_id );

		if ( $type == learndash_get_post_type_slug( 'course' ) ) {
			if ( ! self::is_user_enrolled_to_course( $user_id, $object_id ) ) {
				ld_update_course_access( $user_id, $object_id );

				self::reset_enrollment_date( $object_id, $order_id, $order_type );
			}
		} elseif ( $type == learndash_get_post_type_slug( 'group' ) ) {
			if ( ! learndash_is_user_in_group( $user_id, $object_id ) ) {
				ld_update_group_access( $user_id, $object_id );
			}
		}
	}

	/**
	 * Remove course/group access
	 * 
	 * @param int $object_id ID of an object, course or group
	 * @param int $user_id   ID of a user
	 * @param int $order_id  Subscription ID or Transaction ID
	 */
	public static function remove_access( $object_id, $user_id, $order_id )
	{
		$accesses = self::decrement_access_counter( $object_id, $user_id, $order_id );

		if ( ! isset( $accesses[ $object_id ] ) || empty( $accesses[ $object_id ] ) ) {
			$type = get_post_type( $object_id );

			if ( $type == learndash_get_post_type_slug( 'course' ) ) {
				ld_update_course_access( $user_id, $object_id, $remove = true );
			} elseif ( $type == learndash_get_post_type_slug( 'group' ) ) {
				ld_update_group_access( $user_id, $object_id, $remove = true );
			}
		}
	}

	/**
	 * Reset enrollment date to subscription or transaction creation date
	 *
	 * @param  int    $object_id  LearnDash object ID, 'course' or 'object'
	 * @param  int    $order_id   Order ID, subscription or transaction ID
	 * @param  string $order_type Order type, 'subscription' or 'transaction'
	 * @return void
	 */
	public static function reset_enrollment_date( $object_id, $order_id, $order_type )
	{
		$first_txn = false;

		// Reset enrollment date to keep the drip feeding working
		if ( $order_type == 'subscription' ) {
			$subscription = new MeprSubscription( $order_id );
			$first_txn    = $subscription->first_txn();

			self::maybe_update_course_access_timestamp_to_first_subscription( $subscription, $subscription->user_id, $object_id );
		} elseif ( $order_type == 'transaction' ) {
			$first_txn = new MeprTransaction( $order_id );
		}

		if ( $first_txn && ! empty( $first_txn->created_at ) ) {
			$type = get_post_type( $object_id );

			if ( $type == learndash_get_post_type_slug( 'course' ) ) {
				update_user_meta( $first_txn->user_id, 'course_' . $object_id . '_access_from', strtotime( $first_txn->created_at ) );
			} elseif ( $type == learndash_get_post_type_slug( 'group' ) ) {
				update_user_meta( $first_txn->user_id, 'learndash_group_enrolled_' . $object_id, strtotime( $first_txn->created_at ) );
			}
		}
	}

	/**
	 * Check if a user is already enrolled to a course
	 * 
	 * @param  integer $user_id   User ID
	 * @param  integer $course_id Course ID
	 * @return boolean            True if enrolled|false otherwise
	 */
	public static function is_user_enrolled_to_course( $user_id = 0, $course_id = 0 ) {
		// Check against sfwd_lms_has_access
		if ( sfwd_lms_has_access( $course_id, $user_id ) ) {
			return true;
		}

		// Check against user's enrolled courses
		$enrolled_courses = learndash_user_get_enrolled_courses( $user_id );
		foreach ( $enrolled_courses as $c_id ) {
			if ( $c_id == $course_id ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get all LearnDash courses
	 * 
	 * @return array WP_Post of LearnDash courses
	 */
	private function get_learndash_courses()
	{
		return get_posts([
			'post_type' => 'sfwd-courses',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'orderby' => 'post_title',
			'order' => 'ASC',
		]);
	}

	/**
	 * Get LearnDash groups
	 * @return array WP_Post of LearnDash groups
	 */
	private function get_learndash_groups()
	{
		return get_posts([
			'post_type' => 'groups',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'orderby' => 'post_title',
			'order' => 'ASC',
		]);
	}

	/**
	 * Get membership access objects, courses and groups
	 * 
	 * @param  int    $membership_id Membership ID
	 * @return array                 List of membership associated objects
	 */
	public static function get_membership_associated_objects( $membership_id )
	{
		$courses = array_unique( (array) get_post_meta( $membership_id, '_learndash_memberpress_courses', true ) );
		$groups  = array_unique( (array) get_post_meta( $membership_id, '_learndash_memberpress_groups', true ) );

		return array_merge( $courses, $groups );
	}

	/**
	 * Check if a course belong to a courses array
	 * If true, output HTML attribute checked="checked"
	 * 
	 * @param  int    $course_id     Course ID
	 * @param  array  $courses_array Course IDs array
	 */
	private function checked_course( $course_id, $courses_array )
	{
		if ( isset( $courses_array ) && is_array( $courses_array ) && in_array( $course_id, $courses_array ) ) {
			echo 'checked="checked"';
		}
	}

	/**
	 * Check if a course belong to a courses array
	 * If true, output HTML attribute selected="selected"
	 *
	 * @param  int    $course_id     Course ID
	 * @param  array  $courses_array Course IDs array
	 */
	private function selected_course( $course_id, $courses_array )
	{
		if ( isset( $courses_array ) && is_array( $courses_array ) && in_array( $course_id, $courses_array ) ) {
			echo 'selected="selected"';
		}
	}

	/**
	 * Add enrolled object record to a user
	 * 
	 * @param int $object_id ID of a course
	 * @param int $user_id   ID of a user
	 * @param int $order_id  Subscription ID or Transaction ID
	 */
	public static function increment_access_counter( $object_id, $user_id, $order_id )
	{
		$objects = self::get_objects_access_counter( $user_id );

		if ( isset( $objects[ $object_id ] ) && ! is_array( $objects[ $object_id ] ) ) {
			$objects[ $object_id ] = array();
		}

		if ( ! isset( $objects[ $object_id ] ) || ! is_array( $objects[ $object_id ] ) || ( is_array( $objects[ $object_id ] ) && array_search( $order_id, $objects[ $object_id ] ) === false ) ) {
			// Add order ID to course access counter
			$objects[ $object_id ][] = $order_id;
		}

		// Meta key name is kept for backward compatibility
		update_user_meta( $user_id, '_learndash_memberpress_enrolled_courses_access_counter', $objects );

		return $objects;
	}

	/**
	 * Delete enrolled object record from a user
	 * 
	 * @param int $object_id ID of a LearnDash object, course or group
	 * @param int $user_id   ID of a user
	 * @param int $order_id  Subscription ID or Transaction ID
	 */
	public static function decrement_access_counter( $object_id, $user_id, $order_id )
	{
		$objects = self::get_objects_access_counter( $user_id );

		if ( isset( $objects[ $object_id ] ) && is_array( $objects[ $object_id ] ) ) {
			$keys = array_keys( $objects[ $object_id ], $order_id );
			if ( is_array( $keys ) ) {
				foreach ( $keys as $key ) {
					unset( $objects[ $object_id ][ $key ] );
				}
			}
		} elseif ( isset( $objects[ $object_id ] ) && ! is_array( $objects[ $object_id ] ) ) {
			unset( $objects[ $object_id ] );
		}

		// Meta key name is kept for backward compatibility
		update_user_meta( $user_id, '_learndash_memberpress_enrolled_courses_access_counter', $objects );

		return $objects;
	}

	/**
	 * Check if a course user access is empty
	 * 
	 * @param int $course_id ID of a course
	 * @param int $user_id   ID of a user
	 * @return boolean       True if empty|false otherwise
	 */
	private function is_course_user_access_empty( $course_id, $user_id )
	{
		$courses = self::get_objects_access_counter( $user_id );

		if ( $courses[ $courses_id ] < 1 ) {
			return true;
		}

		return false;
	}

	/**
	 * Get user enrolled course access counter
	 * 
	 * @param  int $user_id ID of a user
	 * @return array        Objects access counter array
	 */
	public static function get_objects_access_counter( $user_id )
	{
		// Meta key name is kept for backward compatibility
		$objects = get_user_meta( $user_id, '_learndash_memberpress_enrolled_courses_access_counter', true );
		
		return ! empty( $objects ) ? $objects : [];
	}

	public static function reset_objects_access_counter()
	{
		global $wpdb;
		return $wpdb->query( "DELETE FROM {$wpdb->prefix}usermeta WHERE meta_key = '_learndash_memberpress_enrolled_courses_access_counter'" );
	}

	/**
	 * Set 'course_' . $course_id . '_access_from' value to the first subscription
	 * 
	 * @param  object $subscription   MeprSubscription object
	 * @param  int    $user_id   	  User ID
	 * @param  int    $course_id 	  Course ID
	 */
	public static function maybe_update_course_access_timestamp_to_first_subscription( $subscription, $user_id, $course_id ) {
		// default to false
		$update = apply_filters( 'learndash_memberpress_update_course_access_timestamp_to_first_subscription', false, $subscription, $course_id );

		if ( $update ) {
			global $wpdb;

			$include_membership = apply_filters( 'learndash_memberpress_update_course_access_timestamp_to_first_subscription_with_same_membership', true, $subscription, $course_id ) ? "AND `product_id` = %d" : "";

			// Get the first subscription
			$query = $wpdb->prepare( "SELECT `id` from {$wpdb->prefix}mepr_subscriptions WHERE `user_id` = %d {$include_membership} ORDER BY `created_at` ASC LIMIT 1", $user_id, $subscription->product_id );
			$first_subscription = $wpdb->get_row( $query );
			$first_subscription = new MeprSubscription( $first_subscription->id );

			if ( $first_subscription ) {
				$first_txn = $first_subscription->first_txn();
				update_user_meta( $user_id, 'course_' . $course_id . '_access_from', strtotime( $first_txn->created_at ) );
			}
		}

	}

	/**
	 * Enqueue course enrollment in database for product with many courses
	 * 
	 * @param  array  $args Transaction/subscription args in this 
	 *                      key value pair: 
	 *                      'transaction_id' => $transaction_id, 
	 *                      for Memberpress one-time/transaction type
	 *                      'subscription_id' => $subscription_id, for 
	 *                      Memberpress subscription type
	 *                      'action' => 'enroll' or 'unenroll'
	 * @return void
	 */
	public static function enqueue_silent_course_access_update( $args ) {
		$queue = get_option( 'learndash_memberpress_silent_course_enrollment_queue', array() );

		if ( ! empty( $args['transaction_id'] ) ) {
			$queue[ $args['transaction_id'] ] = $args;
		} elseif( ! empty( $args['subscription_id'] ) ) {
			$queue[ $args['subscription_id'] ] = $args;
		}

		update_option( 'learndash_memberpress_silent_course_enrollment_queue', $queue );
	}

	/**
	 * Process silent background course enrollment using cron
	 * 
	 * @return void
	 */
	public static function process_silent_course_access_update() {
		$queue = get_option( 'learndash_memberpress_silent_course_enrollment_queue', array() );

		$processed_queue = array_slice( $queue, 0, 1, true );

		foreach ( $processed_queue as $id => $args ) {
			if ( ! empty( $args['transaction_id'] ) ) {
				$txn = new MeprTransaction( $args['transaction_id'] );
				$ld_objects = self::get_membership_associated_objects( $txn->rec->product_id );

				foreach ( $ld_objects as $object_id ) {
					if ( $args['action'] == 'enroll' ) {
						self::add_access( $object_id, $txn->rec->user_id, $txn->id, 'transaction' );
					} else {
						self::remove_access( $object_id, $txn->rec->user_id, $txn->id );
					}
				}
			} elseif ( ! empty( $args['subscription_id'] ) ) {
				$subscription = new MeprSubscription( $args['subscription_id'] );
				$first_txn  = $subscription->first_txn();
				$ld_objects = self::get_membership_associated_objects( $subscription->product_id );

				foreach ( $ld_objects as $object_id ) {
					if ( $args['action'] == 'enroll' ) {
						self::add_access( $object_id, $subscription->user_id, $subscription->id, 'subscription' );
					} else {
						self::remove_access( $object_id, $subscription->user_id, $subscription->id );
					}
				}
			}

			unset( $queue[ $id ] );

			update_option( 'learndash_memberpress_silent_course_enrollment_queue', $queue );
		}
	}

	/**
	 * Minimum courses count in a transaction so that its courses enrollment will be processed in the background using cron job
	 * @return int
	 */
	public static function get_min_courses_count_for_silent_course_enrollment() {
		return apply_filters( 'learndash_memberpress_min_courses_count_for_silent_course_enrollment', 5 );
	}
}

new Learndash_Memberpress_Integration();