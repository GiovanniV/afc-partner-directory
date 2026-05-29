<?php
/**
 * Frontend template loading for partner singles.
 *
 * @package AFC_Partner_Directory
 */

defined( 'ABSPATH' ) || exit;

/**
 * Loads the plugin single-partner template when the theme does not provide one.
 *
 * @param string $template Path to the template.
 * @return string
 */
function afc_partner_directory_partner_single_template( string $template ): string {
	if ( ! is_singular( 'partner' ) ) {
		return $template;
	}

	$plugin_template = afc_partner_directory_path( 'templates/single-partner.php' );
	if ( file_exists( $plugin_template ) ) {
		return $plugin_template;
	}

	return $template;
}

/**
 * Enqueues styles for partner profile pages.
 */
function afc_partner_directory_enqueue_partner_profile_assets(): void {
	if ( ! is_singular( 'partner' ) ) {
		return;
	}

	wp_enqueue_style(
		'afc-partner-directory-profile',
		afc_partner_directory_url( 'assets/css/single-partner.css' ),
		array( 'afc-partner-directory-shell' ),
		AFC_PARTNER_DIRECTORY_VERSION
	);
}

/**
 * Enqueues all public frontend assets for shell and profiles.
 */
function afc_partner_directory_enqueue_public_assets(): void {
	afc_partner_directory_enqueue_frontend_shell_assets();
	afc_partner_directory_enqueue_partner_profile_assets();
}
add_action( 'wp_enqueue_scripts', 'afc_partner_directory_enqueue_public_assets' );
