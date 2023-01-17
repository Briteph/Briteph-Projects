<?php
/**
* Activation class
*/
class Learndash_Memberpress_Activation
{
	
	public function __construct()
	{
		register_activation_hook( LEARNDASH_MEMBERPRESS_FILE, array( $this, 'activation' ) );
	}

	public function activation()
	{
		if ( ! $this->is_memberpress_active() ) {
			deactivate_plugins( LEARNDASH_MEMBERPRESS_FILE, true );
			add_action( 'admin_notices', array( $this, 'required_notice' ) );
		}
	}

	private function is_memberpress_active()
	{
		 if ( is_plugin_active( WP_PLUGIN_DIR . 'memberpress/memberpress.php' ) )
		 {
		 	return true;
		 } else {
		 	return false;
		 }
	}

	public function required_notice()
	{
		?>
		
		<div id="message" class="error notice is-dismissible">
			<p><?php _e( 'MemberPress plugin is required to be activated first.', 'learndash-memberpress' ); ?></p>
		</div>

		<?php
	}
}

new Learndash_Memberpress_Activation();