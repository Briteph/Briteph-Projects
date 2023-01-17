<?php
/**
 * My Project Partners functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package My_Project_Partners
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'my_project_partners_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function my_project_partners_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on My Project Partners, use a find and replace
		 * to change 'my-project-partners' to the name of your theme in all the template files.
		 */

		load_theme_textdomain( 'my-project-partners', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'my-project-partners' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'my_project_partners_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'my_project_partners_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function my_project_partners_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'my_project_partners_content_width', 640 );
}
add_action( 'after_setup_theme', 'my_project_partners_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function my_project_partners_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'my-project-partners' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'my-project-partners' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'my_project_partners_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function my_project_partners_scripts() {
	wp_enqueue_style( 'my-project-partners-style', get_stylesheet_uri(), array(), filemtime(get_stylesheet_directory() . '/style.css') );
	wp_style_add_data( 'my-project-partners-style', 'rtl', 'replace' );

	wp_enqueue_script( 'my-project-partners-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	// barba JS and gsap
	wp_enqueue_script( 'barba', get_template_directory_uri() . '/js/barba.umd.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'gsap', get_template_directory_uri() . '/js/gsap.min.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'barba-init', get_template_directory_uri() . '/js/barba-init.js', array(), filemtime(get_stylesheet_directory() . '/js/barba-init.js'), true );

/* 	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	} */
	wp_enqueue_style( 'shepherd', get_stylesheet_directory_uri() . '/css/shepherd.css', array(), _S_VERSION );
	wp_enqueue_script('shepherdjs', get_stylesheet_directory_uri() . '/js/shepherd.min.js', array(), _S_VERSION, true);
	
}
add_action( 'wp_enqueue_scripts', 'my_project_partners_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Security additions.
 */
require get_template_directory() . '/inc/security.php';

/**
 * Navigation icon additions.
 */
require get_template_directory() . '/inc/navigation-icons.php';

/**
 * Additional custom user roles.
 */
require get_template_directory() . '/inc/user-roles.php';

/**
 * Scoro API functions.
 */
require get_template_directory() . '/inc/scoro-functions.php';

/**
 * Zoho Tokens.
 */
require get_template_directory() . '/inc/test.php';

/**
 * Utility functions.
 */
require get_template_directory() . '/inc/utility-functions.php';

/**
 * Register Custom Post types.
 */
require get_template_directory() . '/inc/custom-post-types-init.php';

/**
 * Custom ACF Gutenberg blocks.
 */
require get_template_directory() . '/inc/custom-blocks.php';

/**
 * AJAX timesheet editing functions
 */
require get_template_directory() . '/inc/timesheet-editing.php';

/**
 * Analytics
 */
require get_template_directory() . '/inc/analytics.php';

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}

/* Redirect to login if not logged in */ 
add_action('template_redirect', 'pp_redirect_to_login');

function pp_redirect_to_login(){
	if ( !is_user_logged_in() ) {
		auth_redirect();
	 }
}

/* Add logo for large screens */
function pp_customizer_setting($wp_customize) {
	// add a setting 
		$wp_customize->add_setting('pp_wide_logo');
	// Add a control to upload the hover logo
		$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'pp_wide_logo', array(
			'label' => 'Wide Logo for large screens',
			'section' => 'title_tagline', //this is the section where the custom-logo from WordPress is
			'settings' => 'pp_wide_logo',
			'priority' => 8 // show it just below the custom-logo
		)));
	}
	
add_action('customize_register', 'pp_customizer_setting');

// PRESTO PLAYER INIT
add_filter('presto_player_load_js', '__return_true');