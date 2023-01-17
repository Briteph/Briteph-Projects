<?php
if ( ! defined( 'ABSPATH' ) ) exit();
/**
* Update class
*/
class Learndash_Memberpress_Update
{
    public $saved_version;

    public $current_version;

    public function __construct() {
        $this->saved_version = get_option( 'learndash_memberpress_version' );
        $this->current_version = LEARNDASH_MEMBERPRESS_VERSION;

        add_action( 'admin_init', array( $this, 'update_to_2_0_1' ) );        
    }

    public function update_to_2_0_1() {
        if ( version_compare( $this->saved_version, '2.0.0.8', '<=' ) && version_compare( $this->current_version, '2.0.0.8', '>' ) ) {
            global $wpdb;
            $query = "DELETE FROM {$wpdb->prefix}usermeta WHERE meta_key = '_learndash_memberpress_enrolled_courses_access_counter'";
            $wpdb->query( $query );
        }
    }
}

new Learndash_Memberpress_Update();