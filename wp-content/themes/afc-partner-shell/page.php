<?php
/**
 * Page template.
 *
 * @package AFC_Partner_Shell
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="afc-main-content" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
	?>
</main>
<?php
get_footer();
