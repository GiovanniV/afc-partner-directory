<?php
/**
 * Partner Custom Post Type and registered metadata.
 *
 * @package AFC_Partner_Directory
 */

defined( 'ABSPATH' ) || exit;

/**
 * Meta key for partner website URL.
 */
const AFC_PARTNER_META_WEBSITE_URL = '_afc_partner_website_url';

/**
 * Meta key for partner category label.
 */
const AFC_PARTNER_META_CATEGORY = '_afc_partner_category';

/**
 * Meta key for partner logo attachment ID.
 */
const AFC_PARTNER_META_LOGO_ID = '_afc_partner_logo_id';

/**
 * Meta key marking a partner record created by the demo seed script.
 */
const AFC_PARTNER_META_SEEDED_DEMO = '_afc_partner_seeded_demo';

/**
 * Registers the partner Custom Post Type.
 */
function afc_partner_directory_register_partner_cpt(): void {
	register_post_type(
		'partner',
		array(
			'labels'              => array(
				'name'               => __( 'Partners', 'afc-partner-directory' ),
				'singular_name'      => __( 'Partner', 'afc-partner-directory' ),
				'menu_name'          => __( 'Partners', 'afc-partner-directory' ),
				'name_admin_bar'     => __( 'Partner', 'afc-partner-directory' ),
				'add_new'            => __( 'Add New', 'afc-partner-directory' ),
				'add_new_item'       => __( 'Add New Partner', 'afc-partner-directory' ),
				'new_item'           => __( 'New Partner', 'afc-partner-directory' ),
				'edit_item'          => __( 'Edit Partner', 'afc-partner-directory' ),
				'view_item'          => __( 'View Partner', 'afc-partner-directory' ),
				'all_items'          => __( 'All Partners', 'afc-partner-directory' ),
				'search_items'       => __( 'Search Partners', 'afc-partner-directory' ),
				'not_found'          => __( 'No partners found.', 'afc-partner-directory' ),
				'not_found_in_trash' => __( 'No partners found in Trash.', 'afc-partner-directory' ),
			),
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'menu_position'       => 20,
			'menu_icon'           => 'dashicons-groups',
			'supports'            => array( 'title', 'thumbnail', 'editor' ),
			'has_archive'         => true,
			'rewrite'             => array(
				'slug'       => 'partners',
				'with_front' => false,
			),
			'capability_type'     => 'post',
		)
	);
}

/**
 * Registers partner post metadata with REST and sanitization support.
 */
function afc_partner_directory_register_partner_meta(): void {
	register_post_meta(
		'partner',
		AFC_PARTNER_META_WEBSITE_URL,
		array(
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => array(
				'schema' => array(
					'type'    => 'string',
					'default' => '',
				),
			),
			'sanitize_callback' => 'esc_url_raw',
			'auth_callback'     => 'afc_partner_directory_partner_meta_auth_callback',
		)
	);

	register_post_meta(
		'partner',
		AFC_PARTNER_META_CATEGORY,
		array(
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => array(
				'schema' => array(
					'type'    => 'string',
					'default' => '',
				),
			),
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => 'afc_partner_directory_partner_meta_auth_callback',
		)
	);

	register_post_meta(
		'partner',
		AFC_PARTNER_META_LOGO_ID,
		array(
			'type'              => 'integer',
			'single'            => true,
			'show_in_rest'      => array(
				'schema' => array(
					'type'    => 'integer',
					'default' => 0,
				),
			),
			'sanitize_callback' => 'absint',
			'auth_callback'     => 'afc_partner_directory_partner_meta_auth_callback',
		)
	);
}

/**
 * Controls read/write access to partner meta (including protected keys in REST).
 *
 * @param bool   $allowed Whether the user can add the meta.
 * @param string $meta_key Meta key.
 * @param int    $post_id Post ID.
 * @param int    $user_id User ID.
 * @param string $cap Capability name.
 * @param array  $caps User capabilities.
 * @return bool
 */
function afc_partner_directory_partner_meta_auth_callback(
	bool $allowed,
	string $meta_key,
	int $post_id,
	int $user_id = 0,
	string $cap = '',
	array $caps = array()
): bool {
	unset( $allowed, $meta_key, $user_id, $caps );

	if ( in_array( $cap, array( 'edit_post_meta', 'delete_post_meta' ), true ) ) {
		return current_user_can( 'edit_post', $post_id );
	}

	$post = get_post( $post_id );
	if ( ! $post || 'partner' !== $post->post_type ) {
		return false;
	}

	if ( 'publish' === $post->post_status ) {
		return true;
	}

	return current_user_can( 'edit_post', $post_id );
}
