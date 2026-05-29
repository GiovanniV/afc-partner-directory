<?php
/**
 * Plugin Name:       AFC Partner Directory
 * Plugin URI:        https://github.com/GiovanniV/afc-partner-directory
 * Description:       Manage and display AFC partner records, directory, and profile pages.
 * Version:           0.3.2
 * Requires at least: 6.4
 * Requires PHP:      8.1
 * Author:            AFC
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       afc-partner-directory
 *
 * @package AFC_Partner_Directory
 */

defined( 'ABSPATH' ) || exit;

define( 'AFC_PARTNER_DIRECTORY_VERSION', '0.3.2' );
define( 'AFC_PARTNER_DIRECTORY_FILE', __FILE__ );
define( 'AFC_PARTNER_DIRECTORY_PATH', plugin_dir_path( __FILE__ ) );
define( 'AFC_PARTNER_DIRECTORY_URL', plugin_dir_url( __FILE__ ) );
define( 'AFC_PARTNER_DIRECTORY_TEXT_DOMAIN', 'afc-partner-directory' );

require_once AFC_PARTNER_DIRECTORY_PATH . 'includes/helpers.php';
require_once AFC_PARTNER_DIRECTORY_PATH . 'includes/lifecycle.php';
require_once AFC_PARTNER_DIRECTORY_PATH . 'includes/cpt.php';
require_once AFC_PARTNER_DIRECTORY_PATH . 'includes/admin.php';
require_once AFC_PARTNER_DIRECTORY_PATH . 'includes/rest.php';
require_once AFC_PARTNER_DIRECTORY_PATH . 'includes/blocks.php';
require_once AFC_PARTNER_DIRECTORY_PATH . 'includes/partner-profile.php';
require_once AFC_PARTNER_DIRECTORY_PATH . 'includes/frontend-shell.php';
require_once AFC_PARTNER_DIRECTORY_PATH . 'includes/templates.php';
require_once AFC_PARTNER_DIRECTORY_PATH . 'includes/plugin.php';

register_activation_hook( AFC_PARTNER_DIRECTORY_FILE, 'afc_partner_directory_activate' );
register_deactivation_hook( AFC_PARTNER_DIRECTORY_FILE, 'afc_partner_directory_deactivate' );

add_action( 'plugins_loaded', 'afc_partner_directory_bootstrap' );
