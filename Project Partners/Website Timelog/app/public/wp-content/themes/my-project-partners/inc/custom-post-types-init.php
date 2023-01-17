<?php
/* CUSTOM POST TYPES INIT */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_action( 'init', 'my_project_partners_register_event_post_type' );

function my_project_partners_register_event_post_type() {
	$args = [
		'label'  => esc_html__( 'Events', 'text-domain' ),
		'labels' => [
			'menu_name'          => esc_html__( 'Events', 'project-partners' ),
			'name_admin_bar'     => esc_html__( 'Event', 'project-partners' ),
			'add_new'            => esc_html__( 'Add Event', 'project-partners' ),
			'add_new_item'       => esc_html__( 'Add new Event', 'project-partners' ),
			'new_item'           => esc_html__( 'New Event', 'project-partners' ),
			'edit_item'          => esc_html__( 'Edit Event', 'project-partners' ),
			'view_item'          => esc_html__( 'View Event', 'project-partners' ),
			'update_item'        => esc_html__( 'View Event', 'project-partners' ),
			'all_items'          => esc_html__( 'All Events', 'project-partners' ),
			'search_items'       => esc_html__( 'Search Events', 'project-partners' ),
			'parent_item_colon'  => esc_html__( 'Parent Event', 'project-partners' ),
			'not_found'          => esc_html__( 'No Events found', 'project-partners' ),
			'not_found_in_trash' => esc_html__( 'No Events found in Trash', 'project-partners' ),
			'name'               => esc_html__( 'Events', 'project-partners' ),
			'singular_name'      => esc_html__( 'Event', 'project-partners' ),
		],
		'public'              => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'show_in_rest'        => true,
		'capability_type'     => 'post',
		'hierarchical'        => false,
		'has_archive'         => true,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite_no_front'    => false,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-calendar-alt',
		'supports' => [
			'title',
			'editor',
			'thumbnail',
			'excerpt',
			'custom-fields',
		],
		'taxonomies' => [
			'category',
			'tag',
		],
		'rewrite' => true
	];

	register_post_type( 'event', $args );
}

add_action( 'init', 'my_project_partners_register_video_post_type' );

function my_project_partners_register_video_post_type() {
	$args = [
		'label'  => esc_html__( 'Videos', 'text-domain' ),
		'labels' => [
			'menu_name'          => esc_html__( 'Videos', 'project-partners' ),
			'name_admin_bar'     => esc_html__( 'Video', 'project-partners' ),
			'add_new'            => esc_html__( 'Add Video', 'project-partners' ),
			'add_new_item'       => esc_html__( 'Add new Video', 'project-partners' ),
			'new_item'           => esc_html__( 'New Video', 'project-partners' ),
			'edit_item'          => esc_html__( 'Edit Video', 'project-partners' ),
			'view_item'          => esc_html__( 'View Video', 'project-partners' ),
			'update_item'        => esc_html__( 'View Video', 'project-partners' ),
			'all_items'          => esc_html__( 'All Videos', 'project-partners' ),
			'search_items'       => esc_html__( 'Search Videos', 'project-partners' ),
			'parent_item_colon'  => esc_html__( 'Parent Video', 'project-partners' ),
			'not_found'          => esc_html__( 'No Videos found', 'project-partners' ),
			'not_found_in_trash' => esc_html__( 'No Videos found in Trash', 'project-partners' ),
			'name'               => esc_html__( 'Videos', 'project-partners' ),
			'singular_name'      => esc_html__( 'Video', 'project-partners' ),
		],
		'public'              => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'show_in_rest'        => true,
		'capability_type'     => 'post',
		'hierarchical'        => false,
		'has_archive'         => true,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite_no_front'    => false,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-video-alt',
		'supports' => [
			'title',
			'editor',
			'thumbnail',
			'excerpt',
			'custom-fields',
		],
		'taxonomies' => [
			'category',
			'tag',
		],
		'rewrite' => true
	];

	register_post_type( 'video', $args );
}

add_action( 'init', 'my_project_partners_register_learning_resources_post_type' );

function my_project_partners_register_learning_resources_post_type() {
	$args = [
		'label'  => esc_html__( 'Learning Resources', 'text-domain' ),
		'labels' => [
			'menu_name'          => esc_html__( 'Learning Resources', 'project-partners' ),
			'name_admin_bar'     => esc_html__( 'Learning Resource', 'project-partners' ),
			'add_new'            => esc_html__( 'Add Resource', 'project-partners' ),
			'add_new_item'       => esc_html__( 'Add new Resource', 'project-partners' ),
			'new_item'           => esc_html__( 'New Resource', 'project-partners' ),
			'edit_item'          => esc_html__( 'Edit Resource', 'project-partners' ),
			'view_item'          => esc_html__( 'View Resource', 'project-partners' ),
			'update_item'        => esc_html__( 'View Resource', 'project-partners' ),
			'all_items'          => esc_html__( 'All Resources', 'project-partners' ),
			'search_items'       => esc_html__( 'Search Resources', 'project-partners' ),
			'parent_item_colon'  => esc_html__( 'Parent Resource', 'project-partners' ),
			'not_found'          => esc_html__( 'No Resources found', 'project-partners' ),
			'not_found_in_trash' => esc_html__( 'No Resources found in Trash', 'project-partners' ),
			'name'               => esc_html__( 'Learning Resources', 'project-partners' ),
			'singular_name'      => esc_html__( 'Learning Resource', 'project-partners' ),
		],
		'public'              => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'show_in_rest'        => true,
		'capability_type'     => 'post',
		'hierarchical'        => false,
		'has_archive'         => true,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite_no_front'    => false,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-welcome-learn-more',
		'supports' => [
			'title',
			'editor',
			'thumbnail',
			'excerpt',
			'custom-fields',
		],
		'taxonomies' => [
			'category',
			'tag',
		],
		'rewrite' => true
	];

	register_post_type( 'learning-resource', $args );
}