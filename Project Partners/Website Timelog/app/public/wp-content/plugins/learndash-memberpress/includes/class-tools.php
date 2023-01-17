<?php
/**
* Tools class
*/
class Learndash_Memberpress_Tools
{
	
	public function __construct()
	{
		add_action( 'admin_menu', array( $this, 'retroactive_access_menu' ) );
		add_action( 'admin_notices', array( $this, 'retroactive_access_notice' ) );
		add_action( 'wp_ajax_learndash_memberpress_dismiss_notice', [ $this, 'ajax_dismiss_notice' ] );

		add_action( 'mepr_display_options_tabs', array( $this, 'mp_options_learndash_tab' ), 99 );
		add_action( 'mepr_display_options', array( $this, 'mp_options_learndash_tab_page' ), 99 );

		add_action( 'wp_ajax_learndash_memberpress_retroactive', array( $this, 'retroactive_access_process' ) );
		add_action( 'admin_head', array( $this, 'retroactive_access_page_style' ) );
	}

	public function retroactive_access_menu()
	{
		add_submenu_page( 'learndash_memberpress_not_exists', __( 'LearnDash Memberpress', 'learndash-memberpress' ), __( 'LearnDash Memberpress', 'learndash-memberpress' ), 'manage_options', 'learndash-memberpress', array( $this, 'learndash_memberpress_page') );
	}

	public function retroactive_access_notice()
	{
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'learndash-memberpress' ) {
			return;
		}

		$options = get_option( 'learndash_memberpress', array() );

		if ( isset( $options['retroactive_access'] ) && $options['retroactive_access'] == 1 ) {
			return;
		}

		if ( isset( $options['dismiss_notice'] ) && $options['dismiss_notice'] == 1 ) {
			return;
		}

		?>

		<div id="learndash-memberpress-message" class="notice notice-warning is-dismissible">
			<p><?php printf( __( 'After configuring LearnDash - Memberpress integration settings, please <a href="%s">click here</a> to start retroactive member access process.', 'learndash-memberpress' ), 'admin.php?page=learndash-memberpress&action=retroactive_access&_wpnonce=' . wp_create_nonce( 'learndash_memberpress' ) ); ?></p>
		</div>

		<script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
				$( document ).on( 'click', '#learndash-memberpress-message button.notice-dismiss', function( e ) {
					e.preventDefault();
					
					$.ajax({
						url: ajaxurl,
						type: 'POST',
						dataType: 'json',
						data: {
							action: 'learndash_memberpress_dismiss_notice',
							nonce: '<?php echo wp_create_nonce( 'learndash_memberpress_dismiss_notice' ) ?>',
						},
					} )
					.done( function() {
						console.log( 'Notice dismissed.' );
					} );
				});
			} );
		</script>

		<?php
	}

	public function ajax_dismiss_notice() {
	    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'learndash_memberpress_dismiss_notice' ) ) {
	    	wp_die();
	    }

	    $options = get_option( 'learndash_memberpress', [] );
	    $options['dismiss_notice'] = 1;

	    update_option( 'learndash_memberpress', $options );

	    echo json_encode( [ 'status' => 'success' ] );
	    wp_die();
	}

	public function mp_options_learndash_tab() {
		?>
		<a class="nav-tab" id="learndash" href="#"><?php echo 'LearnDash'; ?></a>
		<?php
	}

	public function mp_options_learndash_tab_page() {
		?>
		<div id="learndash" class="mepr-options-hidden-pane">
			<h3><?php _e( 'Tools', 'learndash-memberpress' ); ?></h3>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="retroactive-tool"><?php _e( 'Retroactive tool:', 'learndash-memberpress' ); ?></label> 
						</th>
						<td>
							<a class="button button-secondary" href="<?php echo admin_url( 'admin.php?page=learndash-memberpress&action=retroactive_access&_wpnonce=' . wp_create_nonce( 'learndash_memberpress' ) ); ?>"><?php _e( 'Run', 'learndash-memberpress' ) ?></a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php
	}

	public function learndash_memberpress_page()
	{
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'retroactive_access' )
		{
			if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'learndash_memberpress' ) ) {
				return;
			}

			?>

			<div class="wrap">
				<div class="learndash-memberpress-message">
					<p><?php _e( 'The process is being done. Please do not close this browser until the process is finished.', 'learndash-memberpress' ); ?></p>
				</div>
				<div class="learndash-memberpress-progress-bar-wrapper">
					<div class="learndash-memberpress-spinner"></div>
					<div class="learndash-memberpress-progress-bar-inner-wrapper">
						<div class="learndash-memberpress-progress-bar"></div>
						</div>
					<div class="clear"></div>
				</div>
			</div>

			<script type="text/javascript">
				jQuery( document ).ready( function( $ ) {
					$( window ).load( function() {
						process_step( 1 );
					} );

					function process_step( step ) {
						$.ajax( {
							url: ajaxurl,
							type: 'POST',
							dataType: 'json',
							data: {
								'action': 'learndash_memberpress_retroactive',
								'step': step,
							},
							success: function( response ) {
								if ( 'done' == response.step ) {
									$( '.learndash-memberpress-progress-bar' ).animate( {
										width: response.percentage + '%' },
										200 );

									setTimeout( function() {
										$( '.learndash-memberpress-message' ).remove();
										$( '.learndash-memberpress-progress-bar' ).remove();
										$( '.learndash-memberpress-spinner' ).remove();

										$( '.learndash-memberpress-progress-bar-wrapper' ).html( '<p>' + '<?php _e( 'The process is complete.', 'learndash-memberpress' ); ?>' + '</p>');

										location.href = '<?php echo admin_url( 'edit.php?post_type=memberpressproduct' ); ?>';
									}, 2000 );
								} else {
									$( '.learndash-memberpress-progress-bar' ).animate( {
										width: response.percentage + '%' },
										200 );

									process_step( parseInt( response.step ) );
								}
							}
						} )
						.fail( function( response ) {
							if ( window.console && window.console.log ) {
								console.log( response );
							}
						} );
					}
				});
			</script>

			<?php
		}
	}

	public function retroactive_access_process()
	{
		error_reporting( E_ALL );
		ini_set( 'log_errors', 1 );
		set_time_limit( 300 );
		
		Learndash_Memberpress_Integration::reset_objects_access_counter();

		global $wpdb;
		$mepr_db = new MeprDb();

		$per_batch   = apply_filters( 'learndash_memberpress_retroactive_tool_per_batch', 5 );
		$step        = intval( $_POST['step'] );
		$offset      = $per_batch * ( $step - 1 );
		$trans_total = MeprTransaction::get_count();
		$percentage = ! empty( $trans_total ) ? ( $step - 1 ) / ceil( $trans_total / $per_batch ) * 100 : 100;

		if ( $percentage >= 100 ) {
			$options = get_option( 'learndash_memberpress', array() );
			$options['retroactive_access'] = 1;
			update_option( 'learndash_memberpress', $options );

			echo json_encode( array( 'percentage' => 100, 'step' => 'done' ) );
		} else {
			// Transactions
			$query = $wpdb->prepare( "SELECT id, user_id, product_id, trans_num, status, created_at, expires_at, subscription_id FROM {$mepr_db->transactions} LIMIT %d OFFSET %d", $per_batch, $offset );
			$transactions = $wpdb->get_results( $query, OBJECT );

			foreach ( $transactions as $transaction ) {
				if ( ! empty( $transaction->subscription_id ) ) {
					$type    = 'subscription';
					$type_id = $transaction->subscription_id;
				} else {
					$type    = 'transaction';
					$type_id = $transaction->id;
				}

				$ld_objects = Learndash_Memberpress_Integration::get_membership_associated_objects( $transaction->product_id );

				foreach ( $ld_objects as $object_id ) {
					if ( 
						( 
							$transaction->status === 'complete' 
							|| $transaction->status === 'confirmed'
						)
						&& 
						(
							$transaction->expires_at > date( 'Y-m-d H:i:s' )
							|| empty( $transaction->expires_at )
							|| $transaction->expires_at === '0000-00-00 00:00:00' 
						) 
					) {
						Learndash_Memberpress_Integration::add_access( $object_id, $transaction->user_id, $type_id, $type );
					} else {
						Learndash_Memberpress_Integration::remove_access( $object_id, $transaction->user_id, $type_id );
					}
				}
			}

			$step += 1;

			echo json_encode( array( 'percentage' => $percentage, 'step' => $step ) );
		}

		wp_die();
	}

	public function retroactive_access_page_style()
	{
		if ( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != 'retroactive_access' ) {
			return;
		}

		?>

		<style type="text/css">
			.learndash-memberpress-progress-bar-wrapper {
				border: 1px solid #c6c6bb;
				padding: 10px;
			}

			.learndash-memberpress-progress-bar-inner-wrapper {
				padding: 0;
				margin: 5px 0 0;
				background-color: #fff;
				display: block;
				float: left;
				line-height: 1;
				width: 94.8%;
			}

			.learndash-memberpress-progress-bar {
				background-color: #3498db;
				border: 1px solid #fff;
				height: 15px;
				width: 0;
				max-width: 100%;
			}

			.learndash-memberpress-spinner {
				border: 4px solid #f3f3f3; /* Light grey */
			    border-top: 4px solid #3498db; /* Blue */
			    background-color: #fff;
			    border-radius: 50%;
			    margin-right: 10px;
			    width: 20px;
			    height: 20px;
			    animation: spin 1s linear infinite;
			    float: left;
			}

			@keyframes spin {
				0% { transform: rotate( 0deg ); }
				100% { transform: rotate( 360deg ); }
			}

			.clear {
				clear: both;
			}
		</style>

		<?php
	}
}

new Learndash_Memberpress_Tools();