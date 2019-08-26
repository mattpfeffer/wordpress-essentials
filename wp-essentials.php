<?php
/**
 * Plugin Name:     Wordpress Essentials
 * Plugin URI:      https://github.com/mattpfeffer/wordpress-essentials
 * Description:     A collection of useful functions and filters to help enforce best practice when working with Wordpress and common third party plugins.
 * Author:          Matt Pfeffer
 * Text Domain:     essentials
 * Version:         0.2
 * Domain Path:     /languages
 * License:         GPL2
 * License URI:     https://opensource.org/licenses/GPL-2.0
 *
 * @package         Wordpress Essentials
 */

/**
 *  SECURITY & HARDENING
 *
 * - Functions and filters to help harden Wordpress core
 * - Overrides to help mitigate common Wordpress attacks
 */

/**
 * Remove generator metatag to avoid exposing Wordpress version
 */

remove_action( 'wp_head', 'wp_generator' );

add_filter( 'the_generator', function () {
	return '';
});

/**
 * Block access to XML-RPC. Comment out if trackbacks and pingbacks
 * are required.
 *
 * !! IMPORTANT !!
 * It's also recommend to block in server configuration
 * or htaccess as this is a common attack vector so blocking prior to
 * the application may provide some perfomance benfits.
 */

add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Block access to the Wordpress REST API. Comment out if API access is
 * required.
 *
 * !! IMPORTANT !!
 * At present, there are no known vulnerabilities in the Wordpress API but
 * it's best to dsaible until needed to prevent an additional attack
 * vector.
 */

add_filter( 'rest_enabled', '_return_false' );
add_filter( 'rest_jsonp_enabled', '_return_false' );

/**
 * Block enumeration of users.
 *
 * !! IMPORTANT !!
 * It's also recommend to block in server configuration
 * or htaccess as this is a common attack vector.
 */

if ( ! is_admin() ) {

	// Default URL format.
	if ( preg_match( '/author=([0-9]*)/i', $_SERVER['QUERY_STRING'] ) ) {
		die();
	}
	// Permalink URL format.
	add_filter( 'redirect_canonical', 'check_user_enum_perm', 10, 2 );

}

/**
 * Checks for user enumeration on permalink style URLs
 *
 * @param string $redirect Where to redirect the request.
 * @param string $request The actual request string.
 * @return string The location to redirect to as set in $redirect.
 */
function check_user_enum_perm( $redirect, $request ) {

	if ( preg_match( '/\?author=([0-9]*)(\/*)/i', $request ) ) {
		die();
	} else {
		return $redirect;
	}

}

/**
 *  THIRD PARTY PLUGINS
 *
 * - Functions and filters for common third party plugins
 * - Overrides to enforce development patterns
 * - Security hardening for common plugin vulnerabilities
 */

/**
 * Tell WordPress where to save field group configuration for
 * Advanced Custom Fields
 */

add_filter( 'acf/settings/save_json', function ( $path ) {
	return WP_CONTENT_DIR . '/fields';
});

add_filter( 'acf/settings/load_json', function ( $path ) {
	$paths[] = WP_CONTENT_DIR . '/fields';
	return $paths;
});

/**
 * Disable autocomplete on Gravity Forms fields. Requires
 * HTML5 output to be enabled
 */

/**
 * Disable autocomplete on form elements.
 *
 * @param string $form_tag The string containing the <form> tag.
 * @return string The filtered string.
 */
function gform_tag_no_autocomplete( $form_tag ) {

	if ( is_admin() ) {
		return $form_tag;
	} else if ( GFFormsModel::is_html5_enabled() ) {
		$form_tag = str_replace( '>', ' autocomplete="off">', $form_tag );
	}

	return $form_tag;

}

add_filter( 'gform_form_tag', 'gform_tag_no_autocomplete' );

/**
 * Disable autocomplete on input elements (input, textarea).
 *
 * @param string $field_content The field content to be filtered.
 * @return string The filtered field content.
 */
function gform_field_no_autocomplete( $field_content ) {

	if ( is_admin() ) {
		return $field_content;
	} else if ( GFFormsModel::is_html5_enabled() ) {
		$field_content = preg_replace( '/<(input|textarea)/', '<${1} autocomplete="off" ', $field_content );
	}

	return $field_content;

}

add_filter( 'gform_field_content', 'gform_field_no_autocomplete' );
