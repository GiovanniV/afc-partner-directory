<?php
/**
 * Gutenberg block registration.
 *
 * @package AFC_Partner_Directory
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers plugin blocks when build artifacts are present.
 */
function afc_partner_directory_register_blocks(): void {
	$block_dir = afc_partner_directory_path( 'build/partner-directory' );

	if ( ! file_exists( $block_dir . '/block.json' ) ) {
		return;
	}

	register_block_type( $block_dir );
}
