<?php
/**
 * Branded application footer.
 *
 * @package AFC_Partner_Directory
 */

defined( 'ABSPATH' ) || exit;

$directory_url = afc_partner_directory_get_directory_page_url();
$logo_url      = afc_partner_directory_brand_logo_url();
list( $logo_width, $logo_height ) = afc_partner_directory_brand_logo_dimensions();
?>
<footer class="afc-shell-footer" role="contentinfo">
	<div class="afc-shell-footer__inner afc-app-container">
		<div class="afc-shell-footer__grid">
			<div class="afc-shell-footer__col">
				<img
					class="afc-shell-footer__logo"
					src="<?php echo esc_url( $logo_url ); ?>"
					alt="<?php esc_attr_e( 'AFC Scholarship Fund', 'afc-partner-directory' ); ?>"
					width="<?php echo (int) $logo_width; ?>"
					height="<?php echo (int) $logo_height; ?>"
					decoding="async"
				/>
				<p class="afc-shell-footer__mission">
					<?php esc_html_e( 'Supporting educational opportunity through scholarship access, school choice, and community partnerships across the AFC Scholarship Fund ecosystem.', 'afc-partner-directory' ); ?>
				</p>
			</div>

			<div class="afc-shell-footer__col">
				<h3 class="afc-shell-footer__heading"><?php esc_html_e( 'Explore', 'afc-partner-directory' ); ?></h3>
				<ul class="afc-shell-footer__links">
					<li><a href="<?php echo esc_url( $directory_url ); ?>"><?php esc_html_e( 'Partner Directory', 'afc-partner-directory' ); ?></a></li>
					<li><a href="https://afcscholarshipfund.org/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Scholarship Programs', 'afc-partner-directory' ); ?></a></li>
					<li><a href="https://afcscholarshipfund.org/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Family Resources', 'afc-partner-directory' ); ?></a></li>
					<li><a href="https://afcscholarshipfund.org/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Contact', 'afc-partner-directory' ); ?></a></li>
				</ul>
			</div>

			<div class="afc-shell-footer__col">
				<h3 class="afc-shell-footer__heading"><?php esc_html_e( 'Trust & compliance', 'afc-partner-directory' ); ?></h3>
				<ul class="afc-shell-footer__links">
					<li><span><?php esc_html_e( '501(c)(3) nonprofit', 'afc-partner-directory' ); ?></span></li>
					<li><span><?php esc_html_e( 'Educational opportunity', 'afc-partner-directory' ); ?></span></li>
					<li><a href="https://afcscholarshipfund.org/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Privacy Policy', 'afc-partner-directory' ); ?></a></li>
					<li><a href="<?php echo esc_url( $directory_url . '#afc-accessibility' ); ?>"><?php esc_html_e( 'Accessibility Statement', 'afc-partner-directory' ); ?></a></li>
				</ul>
			</div>
		</div>

		<div class="afc-shell-footer__bar">
			<p class="afc-shell-footer__copy">
				<?php
				printf(
					/* translators: %s: year */
					esc_html__( '© %s AFC Scholarship Fund Partner Directory.', 'afc-partner-directory' ),
					esc_html( gmdate( 'Y' ) )
				);
				?>
			</p>
			<p class="afc-shell-footer__ein">
				<?php esc_html_e( 'EIN: 41-3421652', 'afc-partner-directory' ); ?>
			</p>
		</div>
	</div>
</footer>
