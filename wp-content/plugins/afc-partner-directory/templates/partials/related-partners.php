<?php
/**
 * Related partners on profile pages.
 *
 * @package AFC_Partner_Directory
 *
 * @var int $post_id Current partner post ID.
 */

defined( 'ABSPATH' ) || exit;

$post_id  = isset( $post_id ) ? (int) $post_id : 0;
$category = (string) get_post_meta( $post_id, AFC_PARTNER_META_CATEGORY, true );

$query_args = array(
	'post_type'      => 'partner',
	'post_status'    => 'publish',
	'posts_per_page' => 3,
	'post__not_in'   => array( $post_id ),
	'orderby'        => 'rand',
);

if ( '' !== $category ) {
	$query_args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		array(
			'key'   => AFC_PARTNER_META_CATEGORY,
			'value' => $category,
		),
	);
}

$related = new WP_Query( $query_args );

if ( ! $related->have_posts() ) {
	return;
}
?>
<section class="afc-related-partners" aria-labelledby="afc-related-partners-title">
	<div class="afc-app-container">
		<h2 id="afc-related-partners-title" class="afc-related-partners__title">
			<?php esc_html_e( 'Related partners', 'afc-partner-directory' ); ?>
		</h2>
		<ul class="afc-related-partners__list" role="list">
			<?php
			while ( $related->have_posts() ) :
				$related->the_post();
				$rid     = get_the_ID();
				$partner = afc_partner_directory_get_partner_display_data( $rid );
				?>
				<li role="listitem">
					<a class="afc-related-partners__card" href="<?php echo esc_url( get_permalink( $rid ) ); ?>">
						<span class="afc-related-partners__name"><?php echo esc_html( $partner['name'] ); ?></span>
						<?php if ( ! empty( $partner['category'] ) ) : ?>
							<span class="afc-related-partners__category"><?php echo esc_html( $partner['category'] ); ?></span>
						<?php endif; ?>
					</a>
				</li>
			<?php endwhile; ?>
		</ul>
	</div>
</section>
<?php
wp_reset_postdata();
