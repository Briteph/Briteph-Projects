<?php

if ( ! defined( 'ABSPATH' ) ) exit();

/**
* Meta_Boxes class
*/
class Learndash_Memberpress_Meta_Boxes
{
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_meta_boxes' ), 10, 3 );
    }

    public function add_meta_boxes() {
        add_meta_box( 'learndash-memberpress-associated-memberships-meta-box', __( 'Associated Memberships', 'learndash-memberpress' ), array( $this, 'associated_memberships_meta_box' ), 'sfwd-courses', 'side', 'low' );
    }

    public function associated_memberships_meta_box() {
        wp_nonce_field( 'learndash_memberpress_associated_memberships_meta_box', 'learndash_memberpress_nonce' );

        $memberships = get_posts( array(
            'post_type' => 'memberpressproduct',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'post_status' => 'publish',
        ) );

        $current_memberships = get_post_meta( get_the_ID(), '_learndash_memberpress_course_memberships', true );
        if ( empty( $current_memberships ) || ! is_array( $current_memberships ) ) {
            $current_memberships = [];
        }

        ?>
        
        <select name="learndash_memberpress_associated_memberships[]" id="learndash-memberpress-associated-memberships" class="select2 full-width" multiple="multiple" size="4" data-ld-select2="1">
            <?php foreach ( $memberships as $membership ) : ?>
            <?php $checked = in_array( $membership->ID, $current_memberships ) ? 'selected="selected"' : '';  ?>
            <option value="<?php echo esc_attr( $membership->ID ) ?>" <?php echo esc_attr( $checked ) ?>><?php echo esc_html( $membership->post_title ) ?></option>
            <?php endforeach; ?>
        </select>
        <p class="description"><?php _e( 'You can select multiple memberships by holding ctrl key on Windows and cmd key on Mac.', 'learndash-memberpress' ) ?></p>

        <?php
    }

    public function save_meta_boxes( $post_id, $post, $update ) {
        if ( ! isset( $_POST['learndash_memberpress_nonce'] ) || ! wp_verify_nonce( $_POST['learndash_memberpress_nonce'], 'learndash_memberpress_associated_memberships_meta_box' ) ) {
            return;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $associated_memberships = ! empty( $_POST['learndash_memberpress_associated_memberships'] ) ? $_POST['learndash_memberpress_associated_memberships'] : [];

        $memberships = array_map( function( $membership_id ) use ( $post_id ) {
            $membership_id  = intval( $membership_id );

            $old_courses = get_post_meta( $membership_id, '_learndash_memberpress_courses', true );
            $new_courses = $old_courses;

            if ( ! empty( $new_courses ) ) {
                $new_courses[] = $post_id;
            } else {
                $new_courses = array( $post_id );
            }

            // Update associated course in DB so that it will be executed in cron
            $course_update_queue = get_option( 'learndash_memberpress_course_access_update', array() );

            $course_update_queue[ $membership_id ] = array(
                'old_courses'   => $old_courses,
                'new_courses'   => $new_courses
            );

            update_option( 'learndash_memberpress_course_access_update', $course_update_queue );

            update_post_meta( $membership_id, '_learndash_memberpress_courses', $new_courses );

            return $membership_id;
        }, $associated_memberships );

        update_post_meta( $post_id, '_learndash_memberpress_course_memberships', $memberships );
    }
}

new Learndash_Memberpress_Meta_Boxes();