<?php
/**
 * Plugin Name:		Wordpress Essentials
 * Plugin URI:		https://github.com/mattpfeffer/wordpress-essentials
 * Description:		A collection of useful functions and filters to help enforce best practice when working with Wordpress and common third party plugins.
 * Author:			Matt Pfeffer
 * Text Domain:		essentials
 * Version:			0.1
 * Domain Path:		/languages
 * License:			GPL2
 * License URI:		https://opensource.org/licenses/GPL-2.0
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

if ( class_exists( 'acf' ) ) :

	add_filter( 'acf/settings/save_json', function ( $path ) {
		return WP_CONTENT_DIR . '/fields';
	});

	add_filter( 'acf/settings/load_json', function ( $path ) {
		$paths[] = WP_CONTENT_DIR . '/fields';
		return $paths;
	});

endif;

/**
 * Disable autocomplete on Gravity Forms fields. Requires
 * HTML5 output to be enabled
 */

if ( class_exists( 'GFForms' ) ) :

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

endif;
