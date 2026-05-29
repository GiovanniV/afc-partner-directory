<?php
/**
 * Directory page hero section.
 *
 * @package AFC_Partner_Directory
 *
 * @var int $partner_count Published partner count.
 */

defined( 'ABSPATH' ) || exit;

$partner_count = isset( $partner_count ) ? (int) $partner_count : 0;
$display_count = $partner_count > 0 ? (string) $partner_count : '0';
?>
<section class="afc-shell-hero" aria-labelledby="afc-shell-hero-title">
	<div class="afc-shell-hero__inner afc-app-container">
		<p class="afc-shell-hero__eyebrow"><?php esc_html_e( 'AFC Scholarship Fund Ecosystem', 'afc-partner-directory' ); ?></p>
		<h1 id="afc-shell-hero-title" class="afc-shell-hero__title">
			<?php esc_html_e( 'Scholarship Partner Network', 'afc-partner-directory' ); ?>
		</h1>
		<p class="afc-shell-hero__lead">
			<?php esc_html_e( 'Organizations and educational partners supporting school choice, scholarship access, and student opportunity initiatives through the AFC Scholarship Fund ecosystem.', 'afc-partner-directory' ); ?>
		</p>

		<ul class="afc-shell-hero__metrics" role="list">
			<li class="afc-shell-hero__metric" role="listitem">
				<span class="afc-shell-hero__metric-value"><?php echo esc_html( $display_count . '+' ); ?></span>
				<span class="afc-shell-hero__metric-label"><?php esc_html_e( 'Partners', 'afc-partner-directory' ); ?></span>
			</li>
			<li class="afc-shell-hero__metric" role="listitem">
				<span class="afc-shell-hero__metric-value"><?php esc_html_e( 'National', 'afc-partner-directory' ); ?></span>
				<span class="afc-shell-hero__metric-label"><?php esc_html_e( 'Coverage', 'afc-partner-directory' ); ?></span>
			</li>
			<li class="afc-shell-hero__metric" role="listitem">
				<span class="afc-shell-hero__metric-value"><?php esc_html_e( 'Scholarship', 'afc-partner-directory' ); ?></span>
				<span class="afc-shell-hero__metric-label"><?php esc_html_e( 'Access Programs', 'afc-partner-directory' ); ?></span>
			</li>
			<li class="afc-shell-hero__metric" role="listitem">
				<span class="afc-shell-hero__metric-value"><?php esc_html_e( 'Education', 'afc-partner-directory' ); ?></span>
				<span class="afc-shell-hero__metric-label"><?php esc_html_e( 'Support Orgs', 'afc-partner-directory' ); ?></span>
			</li>
		</ul>
	</div>
</section>
