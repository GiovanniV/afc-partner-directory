<?php
/**
 * Theme footer — delegates to AFC Partner Directory shell.
 *
 * @package AFC_Partner_Shell
 */

defined( 'ABSPATH' ) || exit;

if ( function_exists( 'afc_partner_directory_render_shell_footer' ) ) {
	afc_partner_directory_render_shell_footer();
}
?>
<?php wp_footer(); ?>
</body>
</html>
