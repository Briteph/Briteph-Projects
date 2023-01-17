<?php
/**
 * Security functions.
 *
 * Enable or disable certain functionality to harden WordPress.
 *
 * @package My_Project_Partners
 */

/**
 * Remove generator meta tags.
 *
 * @author Project Partners
 * @see https://developer.wordpress.org/reference/functions/the_generator/
 */
add_filter( 'the_generator', '__return_false' );

/**
 * Disable XML RPC.
 *
 * @author Project Partners
 * @see https://developer.wordpress.org/reference/hooks/xmlrpc_enabled/
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Change REST-API header from "null" to "*".
 *
 * @author Project Partners
 * @see https://w3c.github.io/webappsec-cors-for-developers/#avoid-returning-access-control-allow-origin-null
 */
function pp__cors_control() {
	header( 'Access-Control-Allow-Origin: *' );
}
add_action( 'rest_api_init', 'pp__cors_control' );
