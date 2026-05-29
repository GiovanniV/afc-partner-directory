<?php
/**
 * Institutional content sections below the partner directory.
 *
 * @package AFC_Partner_Directory
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="afc-shell-sections">
	<div class="afc-app-container">
		<section id="afc-about-network" class="afc-shell-section">
			<h2 class="afc-shell-section__title"><?php esc_html_e( 'About the Partner Network', 'afc-partner-directory' ); ?></h2>
			<p class="afc-shell-section__text">
				<?php esc_html_e( 'The Scholarship Partner Network connects scholarship granting organizations, school partners, donor platforms, and community organizations working to expand educational access. Each profile represents a WordPress-managed partner record with verified metadata and public detail pages.', 'afc-partner-directory' ); ?>
			</p>
		</section>

		<section id="afc-educational-mission" class="afc-shell-section">
			<h2 class="afc-shell-section__title"><?php esc_html_e( 'Educational Access Mission', 'afc-partner-directory' ); ?></h2>
			<p class="afc-shell-section__text">
				<?php esc_html_e( 'Aligned with the AFC Scholarship Fund mission, this directory presents how partner data supports scholarship infrastructure, family resources, and school choice initiatives.', 'afc-partner-directory' ); ?>
			</p>
		</section>

		<section id="afc-how-participate" class="afc-shell-section">
			<h2 class="afc-shell-section__title"><?php esc_html_e( 'How Partners Participate', 'afc-partner-directory' ); ?></h2>
			<p class="afc-shell-section__text">
				<?php esc_html_e( 'Administrators publish partner records in WordPress, assign categories and website URLs, and upload logos through the Partners admin workflow. Partners appear in the directory block and on profile pages at /partners/{slug}/.', 'afc-partner-directory' ); ?>
			</p>
		</section>

		<section id="afc-accessibility" class="afc-shell-section afc-shell-section--accessibility">
			<h2 class="afc-shell-section__title"><?php esc_html_e( 'Accessibility Statement', 'afc-partner-directory' ); ?></h2>
			<p class="afc-shell-section__text">
				<?php esc_html_e( 'This site prioritizes semantic HTML, keyboard-accessible navigation, visible focus states, and screen-reader-friendly labels. Report accessibility issues to your site administrator.', 'afc-partner-directory' ); ?>
			</p>
		</section>
	</div>
</div>
