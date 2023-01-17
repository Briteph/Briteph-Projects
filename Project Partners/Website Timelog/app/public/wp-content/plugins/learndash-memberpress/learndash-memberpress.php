<?php
/**
 * Plugin Name: LearnDash LMS - MemberPress Integration
 * Plugin URI: http://www.learndash.com/
 * Description: Integrate LearnDash LMS with MemberPress
 * Version: 2.2.1.2
 * Author: LearnDash
 * Author URI: http://www.learndash.com/
 * Text Domain: learndash-memberpress
 * Domain Path: languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Check if class Learndash_Memberpress already exists
if ( ! class_exists( 'Learndash_Memberpress' ) ) :

/**
* Main Learndash_Memberpress class
*
* This main class is responsible for instantiating the class, including the necessary files
* used throughout the plugin, and loading the plugin translation files.
*
* @since 1.0
*/
final class Learndash_Memberpress {

	/**
	 * The one and only true Learndash_Memberpress instance
	 *
	 * @since 1.0
	 * @access private
	 * @var object $instance
	 */
	private static $instance;

	/**
	 * Instantiate the main class
	 *
	 * This function instantiates the class, initialize all functions and return the object.
	 * 
	 * @since 1.0
	 * @return object The one and only true Learndash_Memberpress instance.
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ( ! self::$instance instanceof Learndash_Memberpress ) ) {

			self::$instance = new Learndash_Memberpress;
			self::$instance->setup_constants();
			
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );

			self::$instance->check_dependency();

			add_action( 'plugins_loaded', function() {
			    if ( LearnDash_Dependency_Check_LD_Memberpress::get_instance()->check_dependency_results() ) {
					self::$instance->includes();
			    }
			} );
		}

		return self::$instance;
	}	

	/**
	 * Function for setting up constants
	 *
	 * This function is used to set up constants used throughout the plugin.
	 *
	 * @since 1.0
	 */
	public function setup_constants() {

		// Plugin version
		if ( ! defined( 'LEARNDASH_MEMBERPRESS_VERSION' ) ) {
			define( 'LEARNDASH_MEMBERPRESS_VERSION', '2.2.1.2' );
		}

		// Plugin file
		if ( ! defined( 'LEARNDASH_MEMBERPRESS_FILE' ) ) {
			define( 'LEARNDASH_MEMBERPRESS_FILE', __FILE__ );
		}		

		// Plugin folder path
		if ( ! defined( 'LEARNDASH_MEMBERPRESS_PLUGIN_PATH' ) ) {
			define( 'LEARNDASH_MEMBERPRESS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
		}

		// Plugin folder URL
		if ( ! defined( 'LEARNDASH_MEMBERPRESS_PLUGIN_URL' ) ) {
			define( 'LEARNDASH_MEMBERPRESS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}		
	}

	/**
	 * Load text domain used for translation
	 *
	 * This function loads mo and po files used to translate text strings used throughout the 
	 * plugin.
	 *
	 * @since 1.0
	 */
	public function load_textdomain() {

		// Set filter for plugin language directory
		$lang_dir = dirname( plugin_basename( LEARNDASH_MEMBERPRESS_FILE ) ) . '/languages/';
		$lang_dir = apply_filters( 'learndash_memberpress_languages_directory', $lang_dir );

		// Load plugin translation file
		load_plugin_textdomain( 'learndash-memberpress', false, $lang_dir );

		// include ld translation class
		include LEARNDASH_MEMBERPRESS_PLUGIN_PATH . 'includes/class-translations-ld-memberpress.php';
	}

	/**
	 * Check and set plugin dependencies
	 * 
	 * @return void
	 */
	public function check_dependency()
	{
	    include LEARNDASH_MEMBERPRESS_PLUGIN_PATH . 'includes/class-dependency-check.php';

	    LearnDash_Dependency_Check_LD_Memberpress::get_instance()->set_dependencies(
	        array(
	            'sfwd-lms/sfwd_lms.php' => array(
	                'label'       => '<a href="https://learndash.com">LearnDash LMS</a>',
	                'class'       => 'SFWD_LMS',
	                'min_version' => '3.0.0',
	            ),
	            'memberpress/memberpress.php' => array(
	                'label'       => '<a href="https://memberpress.com/">Memberpress</a>',
	                'class'       => 'MeprCtrlFactory',
	                'min_version' => '1.8.0',
	            ),
	        )
	    );

	    LearnDash_Dependency_Check_LD_Memberpress::get_instance()->set_message(
	        __( 'LearnDash LMS - Memberpress Integration Add-on requires the following plugin(s) to be active:', 'learndash-memberpress' )
	    );
	}

	/**
	 * Includes all necessary PHP files
	 *
	 * This function is responsible for including all necessary PHP files.
	 *
	 * @since  1.0
	 */
	public function includes() {
		if ( is_admin() ) {
			include LEARNDASH_MEMBERPRESS_PLUGIN_PATH . '/includes/admin/class-assets.php';
			include LEARNDASH_MEMBERPRESS_PLUGIN_PATH . '/includes/admin/class-meta-boxes.php';
		}

		include LEARNDASH_MEMBERPRESS_PLUGIN_PATH . '/includes/class-activation.php';
		include LEARNDASH_MEMBERPRESS_PLUGIN_PATH . '/includes/class-update.php';
		include LEARNDASH_MEMBERPRESS_PLUGIN_PATH . '/includes/class-cron.php';
		include LEARNDASH_MEMBERPRESS_PLUGIN_PATH . '/includes/class-tools.php';
		include LEARNDASH_MEMBERPRESS_PLUGIN_PATH . '/includes/class-integration.php';
	}
}
endif; // End if class_exist check

/**
 * The main function for returning Learndash_Memberpress instance
 *
 * @since 1.0
 * @return object The one and only true Learndash_Memberpress instance.
 */
function learndash_memberpress() {
	return Learndash_Memberpress::instance();
}

// Run plugin
learndash_memberpress();