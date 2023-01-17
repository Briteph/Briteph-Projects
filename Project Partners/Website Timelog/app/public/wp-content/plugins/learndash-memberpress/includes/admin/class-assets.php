<?php

if ( ! defined( 'ABSPATH' ) ) exit();

/**
* Admin_Assets class
*/
class Learndash_Memberpress_Admin_Assets
{
    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    public function enqueue_scripts() {
        $screen = get_current_screen();

        if ( 'sfwd-courses' === $screen->post_type ) {
            wp_enqueue_style( 'learndash-memberpress-edit-post', LEARNDASH_MEMBERPRESS_PLUGIN_URL . 'assets/css/edit-post.css', array(), LEARNDASH_MEMBERPRESS_VERSION, 'all' );
            wp_enqueue_script( 'learndash-memberpress-edit-post', LEARNDASH_MEMBERPRESS_PLUGIN_URL . 'assets/js/edit-post.js', array( 'jquery' ), LEARNDASH_MEMBERPRESS_VERSION, true );
            wp_localize_script( 'learndash-memberpress-edit-post', 'LD_Memberpress_Edit_Post_Params', array(
                'placeholder' => __( 'Select memberships', 'learndash-memberpress' ),
            ) );
        }
    }
}

new Learndash_Memberpress_Admin_Assets();