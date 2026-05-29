<?php
/**
 * Branded application header.
 *
 * @package AFC_Partner_Directory
 */

defined( 'ABSPATH' ) || exit;

$directory_url = afc_partner_directory_get_directory_page_url();
$logo_url      = afc_partner_directory_brand_logo_url();
list( $logo_width, $logo_height ) = afc_partner_directory_brand_logo_dimensions();
?>
<a class="afc-skip-link screen-reader-text" href="#afc-main-content">
	<?php esc_html_e( 'Skip to content', 'afc-partner-directory' ); ?>
</a>
<header class="afc-shell-header" role="banner">
	<div class="afc-shell-header__inner afc-app-container">
		<div class="afc-shell-header__brand">
			<a class="afc-shell-header__logo-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img
					class="afc-shell-header__logo"
					src="<?php echo esc_url( $logo_url ); ?>"
					alt="<?php esc_attr_e( 'AFC Scholarship Fund', 'afc-partner-directory' ); ?>"
					width="<?php echo (int) $logo_width; ?>"
					height="<?php echo (int) $logo_height; ?>"
					decoding="async"
				/>
			</a>
			<p class="afc-shell-header__product">
				<?php esc_html_e( 'Partner Directory', 'afc-partner-directory' ); ?>
			</p>
		</div>

		<button
			type="button"
			class="afc-shell-header__toggle"
			aria-expanded="false"
			aria-controls="afc-shell-nav"
		>
			<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'afc-partner-directory' ); ?></span>
			<span class="afc-shell-header__toggle-bar" aria-hidden="true"></span>
			<span class="afc-shell-header__toggle-bar" aria-hidden="true"></span>
			<span class="afc-shell-header__toggle-bar" aria-hidden="true"></span>
		</button>

		<nav id="afc-shell-nav" class="afc-shell-header__nav" aria-label="<?php esc_attr_e( 'Primary', 'afc-partner-directory' ); ?>">
			<ul class="afc-shell-header__menu">
				<li><a href="<?php echo esc_url( $directory_url . '#afc-about-network' ); ?>"><?php esc_html_e( 'About', 'afc-partner-directory' ); ?></a></li>
				<li><a href="<?php echo esc_url( $directory_url ); ?>"><?php esc_html_e( 'Partners', 'afc-partner-directory' ); ?></a></li>
				<li><a href="https://afcscholarshipfund.org/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Scholarship Programs', 'afc-partner-directory' ); ?></a></li>
				<li><a href="https://afcscholarshipfund.org/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Resources', 'afc-partner-directory' ); ?></a></li>
				<li><a href="https://afcscholarshipfund.org/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Contact', 'afc-partner-directory' ); ?></a></li>
			</ul>
		</nav>
	</div>
</header>
