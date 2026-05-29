<?php
/**
 * Fallback index template.
 *
 * @package AFC_Partner_Shell
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="afc-main-content" class="site-main">
	<div class="afc-app-container" style="padding-block: 2rem;">
		<?php
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				the_content();
			}
		}
		?>
	</div>
</main>
<?php
get_footer();
