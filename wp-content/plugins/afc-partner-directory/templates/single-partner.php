<?php
/**
 * Single partner template (theme fallback).
 *
 * @package AFC_Partner_Directory
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="afc-main-content" class="site-main afc-partner-profile-page">
	<?php
	while ( have_posts() ) :
		the_post();
		afc_partner_directory_render_partner_profile( (int) get_the_ID() );
	endwhile;
	?>
</main>
<?php
get_footer();
