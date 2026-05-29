<?php
/**
 * Partner archive — redirect to the directory page for a consistent demo experience.
 *
 * @package AFC_Partner_Shell
 */

defined( 'ABSPATH' ) || exit;

$directory_url = function_exists( 'afc_partner_directory_get_directory_page_url' )
	? afc_partner_directory_get_directory_page_url()
	: home_url( '/' );

wp_safe_redirect( $directory_url, 302 );
exit;
