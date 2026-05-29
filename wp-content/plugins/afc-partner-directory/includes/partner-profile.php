<?php
/**
 * Partner single-page profile rendering.
 *
 * @package AFC_Partner_Directory
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns the best URL for “Back to Partner Directory” navigation.
 */
function afc_partner_directory_get_directory_page_url(): string {
	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 50,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);

	foreach ( $pages as $page ) {
		if ( str_contains( (string) $page->post_content, 'afc/partner-directory' ) ) {
			return (string) get_permalink( $page );
		}
	}

	$archive_link = get_post_type_archive_link( 'partner' );
	if ( is_string( $archive_link ) && '' !== $archive_link ) {
		return $archive_link;
	}

	return home_url( '/' );
}

/**
 * Renders a single partner profile (used on partner detail pages).
 *
 * @param int $post_id Partner post ID.
 */
function afc_partner_directory_render_partner_profile( int $post_id ): void {
	$partner  = afc_partner_directory_get_partner_display_data( $post_id );
	$content  = get_post_field( 'post_content', $post_id );
	$back_url = afc_partner_directory_get_directory_page_url();

	$logo_alt = sprintf(
		/* translators: %s: partner name */
		__( '%s logo', 'afc-partner-directory' ),
		$partner['name']
	);

	$initial_char = function_exists( 'mb_substr' )
		? mb_substr( $partner['name'], 0, 1 )
		: substr( $partner['name'], 0, 1 );
	$initial      = function_exists( 'mb_strtoupper' )
		? mb_strtoupper( $initial_char )
		: strtoupper( $initial_char );

	afc_partner_directory_render_partial(
		'profile-hero',
		array(
			'partner'  => $partner,
			'back_url' => $back_url,
			'logo_alt' => $logo_alt,
			'initial'  => $initial,
		)
	);
	?>
	<div class="afc-app-container afc-app-container--profile">
		<article class="afc-partner-profile" id="partner-<?php echo esc_attr( (string) $post_id ); ?>">
			<?php if ( is_string( $content ) && '' !== trim( $content ) ) : ?>
				<div class="afc-partner-profile__content">
					<?php echo wp_kses_post( apply_filters( 'the_content', $content ) ); ?>
				</div>
			<?php endif; ?>
		</article>
	</div>
	<?php
	afc_partner_directory_render_partial(
		'related-partners',
		array(
			'post_id' => $post_id,
		)
	);
}
