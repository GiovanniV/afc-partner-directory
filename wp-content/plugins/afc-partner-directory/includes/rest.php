<?php
/**
 * REST API field registration and computed serialization for partners.
 *
 * @package AFC_Partner_Directory
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers custom REST fields for the partner post type.
 */
function afc_partner_directory_register_rest_fields(): void {
	register_rest_field(
		'partner',
		'afc_partner',
		array(
			'get_callback' => 'afc_partner_directory_get_partner_rest_bundle',
			'schema'       => array(
				'description' => __( 'Normalized partner fields for frontend consumption.', 'afc-partner-directory' ),
				'type'        => 'object',
				'context'     => array( 'view', 'edit', 'embed' ),
				'properties'  => array(
					'website_url' => array(
						'type' => 'string',
					),
					'category'    => array(
						'type' => 'string',
					),
					'logo_id'     => array(
						'type' => 'integer',
					),
				),
			),
		)
	);

	register_rest_field(
		'partner',
		'logo_url',
		array(
			'get_callback' => 'afc_partner_directory_get_logo_url',
			'schema'       => array(
				'description' => __( 'Partner logo URL resolved from the logo attachment ID.', 'afc-partner-directory' ),
				'type'        => 'string',
				'context'     => array( 'view', 'edit', 'embed' ),
			),
		)
	);

	register_rest_field(
		'partner',
		'featured_image_url',
		array(
			'get_callback' => 'afc_partner_directory_get_featured_image_url',
			'schema'       => array(
				'description' => __( 'Partner featured image URL.', 'afc-partner-directory' ),
				'type'        => 'string',
				'context'     => array( 'view', 'edit', 'embed' ),
			),
		)
	);
}

/**
 * Returns normalized partner meta for REST consumers (read-only bundle).
 *
 * @param array<string, mixed> $post Prepared REST post object.
 * @return array{website_url: string, category: string, logo_id: int}
 */
function afc_partner_directory_get_partner_rest_bundle( array $post ): array {
	$post_id = isset( $post['id'] ) ? (int) $post['id'] : 0;

	return array(
		'website_url' => $post_id > 0 ? (string) get_post_meta( $post_id, AFC_PARTNER_META_WEBSITE_URL, true ) : '',
		'category'    => $post_id > 0 ? (string) get_post_meta( $post_id, AFC_PARTNER_META_CATEGORY, true ) : '',
		'logo_id'     => $post_id > 0 ? (int) get_post_meta( $post_id, AFC_PARTNER_META_LOGO_ID, true ) : 0,
	);
}

/**
 * Resolves the partner logo meta attachment ID to a public image URL.
 *
 * @param array<string, mixed> $post Prepared REST post object.
 * @return string Image URL or empty string when no logo is set.
 */
function afc_partner_directory_get_logo_url( array $post ): string {
	$post_id = isset( $post['id'] ) ? (int) $post['id'] : 0;
	if ( $post_id <= 0 ) {
		return '';
	}

	$logo_id = (int) get_post_meta( $post_id, AFC_PARTNER_META_LOGO_ID, true );
	if ( $logo_id <= 0 ) {
		return '';
	}

	$url = wp_get_attachment_image_url( $logo_id, 'medium' );
	return is_string( $url ) ? $url : '';
}

/**
 * Resolves the partner featured image (post thumbnail) to a public image URL.
 *
 * @param array<string, mixed> $post Prepared REST post object.
 * @return string Image URL or empty string when no featured image is set.
 */
function afc_partner_directory_get_featured_image_url( array $post ): string {
	$post_id = isset( $post['id'] ) ? (int) $post['id'] : 0;
	if ( $post_id <= 0 ) {
		return '';
	}

	$url = get_the_post_thumbnail_url( $post_id, 'medium' );
	return is_string( $url ) ? $url : '';
}
