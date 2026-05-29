<?php
/**
 * Server-side render template for the Partner Directory block.
 *
 * @package AFC_Partner_Directory
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$show_heading = ! isset( $attributes['showHeading'] ) || (bool) $attributes['showHeading'];
$heading      = isset( $attributes['heading'] ) ? (string) $attributes['heading'] : __( 'Our Scholarship Partners', 'afc-partner-directory' );
$intro        = isset( $attributes['intro'] ) ? (string) $attributes['intro'] : '';

$partners_query = new WP_Query(
	array(
		'post_type'      => 'partner',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
	)
);

$wrapper_args = array(
	'class' => 'afc-partner-directory',
);

if ( function_exists( 'afc_partner_directory_should_use_shell' ) && afc_partner_directory_should_use_shell() ) {
	$wrapper_args['aria-labelledby'] = 'afc-directory-region-title';
} else {
	$wrapper_args['aria-label'] = __( 'Partner directory', 'afc-partner-directory' );
}

$wrapper_attributes = get_block_wrapper_attributes( $wrapper_args );

$partner_count = (int) $partners_query->post_count;
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( $show_heading && ( '' !== $heading || '' !== $intro ) ) : ?>
		<header class="afc-partner-directory__header">
			<?php if ( '' !== $heading ) : ?>
				<h2 class="afc-partner-directory__heading">
					<?php echo esc_html( $heading ); ?>
				</h2>
			<?php endif; ?>
			<?php if ( '' !== $intro ) : ?>
				<p class="afc-partner-directory__intro">
					<?php echo esc_html( $intro ); ?>
				</p>
			<?php endif; ?>
			<?php if ( $partner_count > 0 ) : ?>
				<p class="afc-partner-directory__count">
					<?php
					printf(
						/* translators: %d: number of partners */
						esc_html( _n( '%d partner', '%d partners', $partner_count, 'afc-partner-directory' ) ),
						(int) $partner_count
					);
					?>
				</p>
			<?php endif; ?>
		</header>
	<?php endif; ?>

	<?php if ( $partners_query->have_posts() ) : ?>
		<ul class="afc-partner-directory__list" role="list">
			<?php
			while ( $partners_query->have_posts() ) :
				$partners_query->the_post();
				$partner  = afc_partner_directory_get_partner_display_data( get_the_ID() );
				$initial_char = function_exists( 'mb_substr' )
					? mb_substr( $partner['name'], 0, 1 )
					: substr( $partner['name'], 0, 1 );
				$initial      = function_exists( 'mb_strtoupper' )
					? mb_strtoupper( $initial_char )
					: strtoupper( $initial_char );
				$title_id = 'afc-partner-title-' . get_the_ID();
				?>
				<li class="afc-partner-directory__item" role="listitem">
					<article class="afc-partner-directory__card">
						<div class="afc-partner-directory__logo<?php echo empty( $partner['logo_url'] ) ? ' afc-partner-directory__logo--placeholder' : ''; ?>" aria-hidden="true">
							<?php if ( ! empty( $partner['logo_url'] ) ) : ?>
								<img
									src="<?php echo esc_url( $partner['logo_url'] ); ?>"
									alt=""
									loading="lazy"
									decoding="async"
									width="240"
									height="120"
								/>
							<?php else : ?>
								<span class="afc-partner-directory__logo-initial">
									<?php echo esc_html( $initial ); ?>
								</span>
							<?php endif; ?>
						</div>

						<div class="afc-partner-directory__body">
							<h3 id="<?php echo esc_attr( $title_id ); ?>" class="afc-partner-directory__name">
								<?php echo esc_html( $partner['name'] ); ?>
							</h3>

							<?php if ( ! empty( $partner['category'] ) ) : ?>
								<p class="afc-partner-directory__category">
									<span class="afc-partner-directory__category-label">
										<?php esc_html_e( 'Category', 'afc-partner-directory' ); ?>
									</span>
									<span class="afc-partner-directory__category-badge">
										<?php echo esc_html( $partner['category'] ); ?>
									</span>
								</p>
							<?php endif; ?>

							<div class="afc-partner-directory__cta">
								<a
									class="afc-partner-directory__link afc-partner-directory__link--profile"
									href="<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>"
									aria-label="<?php echo esc_attr( sprintf( __( 'View partner profile for %s', 'afc-partner-directory' ), $partner['name'] ) ); ?>"
								>
									<?php esc_html_e( 'View partner profile', 'afc-partner-directory' ); ?>
									<span class="afc-partner-directory__link-icon" aria-hidden="true">→</span>
								</a>
							</div>
						</div>
					</article>
				</li>
			<?php endwhile; ?>
		</ul>
		<?php wp_reset_postdata(); ?>
	<?php else : ?>
		<div class="afc-partner-directory__empty" role="status">
			<p class="afc-partner-directory__empty-title">
				<?php esc_html_e( 'Partners coming soon', 'afc-partner-directory' ); ?>
			</p>
			<p class="afc-partner-directory__empty-text">
				<?php esc_html_e( 'Scholarship, school, and community partners will appear here once published in the Partners admin area.', 'afc-partner-directory' ); ?>
			</p>
		</div>
	<?php endif; ?>
</section>
