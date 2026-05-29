<?php
/**
 * Central plugin bootstrap.
 *
 * @package AFC_Partner_Directory
 */

defined( 'ABSPATH' ) || exit;

/**
 * Initializes plugin behavior after WordPress loads.
 */
function afc_partner_directory_bootstrap(): void {
	afc_partner_directory_load_textdomain();

	add_action( 'init', 'afc_partner_directory_register_partner_cpt' );
	add_action( 'init', 'afc_partner_directory_register_partner_meta' );

	add_action( 'add_meta_boxes', 'afc_partner_directory_register_meta_boxes' );
	add_action( 'save_post_partner', 'afc_partner_directory_save_partner_meta' );
	add_action( 'admin_enqueue_scripts', 'afc_partner_directory_enqueue_admin_assets' );

	add_action( 'rest_api_init', 'afc_partner_directory_register_rest_fields' );

	add_action( 'init', 'afc_partner_directory_register_blocks' );

	add_filter( 'template_include', 'afc_partner_directory_partner_single_template' );
}

/**
 * Loads the plugin text domain for translations.
 */
function afc_partner_directory_load_textdomain(): void {
	load_plugin_textdomain(
		AFC_PARTNER_DIRECTORY_TEXT_DOMAIN,
		false,
		dirname( plugin_basename( AFC_PARTNER_DIRECTORY_FILE ) ) . '/languages'
	);
}
