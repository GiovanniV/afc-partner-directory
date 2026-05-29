<?php
/**
 * Plugin activation and deactivation callbacks.
 *
 * @package AFC_Partner_Directory
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the partner CPT and flushes rewrite rules on activation.
 */
function afc_partner_directory_activate(): void {
	afc_partner_directory_register_partner_cpt();
	flush_rewrite_rules();
}

/**
 * Flushes rewrite rules on deactivation.
 */
function afc_partner_directory_deactivate(): void {
	flush_rewrite_rules();
}
