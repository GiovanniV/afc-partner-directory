<?php
/**
 * Partner profile hero banner.
 *
 * @package AFC_Partner_Directory
 *
 * @var array  $partner     Display data.
 * @var string $back_url    Directory back link.
 * @var string $logo_alt    Logo alt text.
 * @var string $initial     Monogram initial.
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="afc-profile-hero" aria-labelledby="afc-profile-hero-title">
	<div class="afc-profile-hero__inner afc-app-container">
		<p class="afc-profile-hero__back">
			<a class="afc-profile-hero__back-link" href="<?php echo esc_url( $back_url ); ?>">
				<?php esc_html_e( '← Back to Partner Directory', 'afc-partner-directory' ); ?>
			</a>
		</p>

		<div class="afc-profile-hero__card">
			<div class="afc-profile-hero__logo<?php echo empty( $partner['logo_url'] ) ? ' afc-profile-hero__logo--placeholder' : ''; ?>"<?php echo empty( $partner['logo_url'] ) ? ' aria-hidden="true"' : ''; ?>>
				<?php if ( ! empty( $partner['logo_url'] ) ) : ?>
					<img src="<?php echo esc_url( $partner['logo_url'] ); ?>" alt="<?php echo esc_attr( $logo_alt ); ?>" />
				<?php else : ?>
					<span class="afc-profile-hero__initial"><?php echo esc_html( $initial ); ?></span>
				<?php endif; ?>
			</div>

			<div class="afc-profile-hero__content">
				<?php if ( ! empty( $partner['category'] ) ) : ?>
					<p class="afc-profile-hero__category">
						<span class="afc-profile-hero__category-badge"><?php echo esc_html( $partner['category'] ); ?></span>
					</p>
				<?php endif; ?>

				<h1 id="afc-profile-hero-title" class="afc-profile-hero__title">
					<?php echo esc_html( $partner['name'] ); ?>
				</h1>

				<?php if ( ! empty( $partner['website_url'] ) ) : ?>
					<p class="afc-profile-hero__cta">
						<a
							class="afc-profile-hero__website"
							href="<?php echo esc_url( $partner['website_url'] ); ?>"
							target="_blank"
							rel="noopener noreferrer"
						>
							<?php esc_html_e( 'Visit official website', 'afc-partner-directory' ); ?>
							<span class="screen-reader-text">
								<?php
								printf(
									/* translators: %s: partner name */
									esc_html__( '(opens in a new tab — %s)', 'afc-partner-directory' ),
									esc_html( $partner['name'] )
								);
								?>
							</span>
						</a>
					</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
