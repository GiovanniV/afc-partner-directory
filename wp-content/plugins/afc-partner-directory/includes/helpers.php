<?php
/**
 * Shared helper functions for AFC Partner Directory.
 *
 * @package AFC_Partner_Directory
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns an absolute path within the plugin directory.
 *
 * @param string $relative_path Path relative to the plugin root.
 * @return string
 */
function afc_partner_directory_path( string $relative_path = '' ): string {
	return trailingslashit( AFC_PARTNER_DIRECTORY_PATH ) . ltrim( $relative_path, '/' );
}

/**
 * Returns a public URL within the plugin directory.
 *
 * @param string $relative_path Path relative to the plugin root.
 * @return string
 */
function afc_partner_directory_url( string $relative_path = '' ): string {
	return trailingslashit( AFC_PARTNER_DIRECTORY_URL ) . ltrim( $relative_path, '/' );
}

/**
 * Public URL for the AFC Scholarship Fund brand logo used in the application shell.
 *
 * @return string
 */
function afc_partner_directory_brand_logo_url(): string {
	return afc_partner_directory_url( 'assets/images/afc-scholarship-fund-logo.png' );
}

/**
 * Intrinsic dimensions of the brand logo (width, height) for img attributes.
 *
 * @return array{0: int, 1: int}
 */
function afc_partner_directory_brand_logo_dimensions(): array {
	return array( 1024, 182 );
}

/**
 * Returns normalized partner display data aligned with the REST API contract.
 *
 * @param int $post_id Partner post ID.
 * @return array{
 *     name: string,
 *     website_url: string,
 *     category: string,
 *     logo_url: string,
 *     featured_image_url: string
 * }
 */
function afc_partner_directory_get_partner_display_data( int $post_id ): array {
	$logo_id = (int) get_post_meta( $post_id, AFC_PARTNER_META_LOGO_ID, true );
	$logo_url = '';

	if ( $logo_id > 0 ) {
		$resolved = wp_get_attachment_image_url( $logo_id, 'medium' );
		$logo_url = is_string( $resolved ) ? $resolved : '';
	}

	$featured_image_url = get_the_post_thumbnail_url( $post_id, 'medium' );
	$featured_image_url = is_string( $featured_image_url ) ? $featured_image_url : '';

	if ( '' === $logo_url && '' !== $featured_image_url ) {
		$logo_url = $featured_image_url;
	}

	return array(
		'name'               => get_the_title( $post_id ),
		'website_url'        => (string) get_post_meta( $post_id, AFC_PARTNER_META_WEBSITE_URL, true ),
		'category'           => (string) get_post_meta( $post_id, AFC_PARTNER_META_CATEGORY, true ),
		'logo_url'           => $logo_url,
		'featured_image_url' => $featured_image_url,
	);
}
