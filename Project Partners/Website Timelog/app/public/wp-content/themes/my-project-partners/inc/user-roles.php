<?php
/* FUNCTIONS RELATED TO CUSTOM USER ROLES AND MANAGEMENT */

function pp__update_custom_roles()
{
    if (get_option('custom_roles_version') < 1) {
        add_role('pp_partner', 'Partner', array('read' => true, 'level_0' => true));
        add_role('pp_timesheet_approver', 'Timesheet Approver', array('read' => true, 'level_0' => true));
        update_option('custom_roles_version', 1);
    }
}
add_action('init', 'pp__update_custom_roles');



function pp_is_user_role($role_to_check) {
	// check if there is a logged in user
    if( is_user_logged_in() ) { 

        if (current_user_can('administrator')) return true;
        // getting & setting the current user, then obtaining the roles as an array
        $user = wp_get_current_user();
        $roles = ( array ) $user->roles;

        // check if the role to check is in the roles array
        if (in_array($role_to_check, $roles)) {
           return true;
        }
    }
    return false;
}

// remove admin bar for non-admins
add_action('after_setup_theme', 'pp_remove_admin_bar');

function pp_remove_admin_bar() {
    show_admin_bar(false);
}

// fix auth0 "Share email logins" so it doesn't try to create a new account with the same email for each connection (e.g. DB + Microsoft logins should log in to the same account)
function auth0_theme_hook_auth0_get_wp_user( $user, $userinfo ) {
	$found_user = get_user_by( 'email', $userinfo->email );
	$user = $found_user instanceof WP_User ? $found_user : null;
	return $user;
}
add_filter( 'auth0_get_wp_user', 'auth0_theme_hook_auth0_get_wp_user', 1, 2 );


function pp_enroll_in_community_on_first_login( $user_login, $user ){
    $mepr_user = new MeprUser( $user->id );
    if( !$mepr_user->is_active_on_membership( 741 ) ){
        $txn = new MeprTransaction();
        $txn->amount = 0.00;
        $txn->total = 0.00;
        $txn->user_id = $user->id;
        $txn->product_id = 741;
        $txn->status = MeprTransaction::$complete_str;
        $txn->txn_type = MeprTransaction::$payment_str;
        $txn->gateway = 'manual';
        $txn->expires_at = '';
        $txn->store();
    }
}
add_action( 'wp_login', 'pp_enroll_in_community_on_first_login', 10, 2 );