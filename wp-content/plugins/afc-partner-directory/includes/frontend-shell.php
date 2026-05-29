<?php
/**
 * Branded application shell (header, footer, hero, institutional sections).
 *
 * @package AFC_Partner_Directory
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether the branded shell should render on the current request.
 */
function afc_partner_directory_should_use_shell(): bool {
	if ( is_singular( 'partner' ) || is_post_type_archive( 'partner' ) ) {
		return true;
	}

	if ( is_singular( 'page' ) ) {
		$post = get_post();
		return $post instanceof WP_Post && str_contains( (string) $post->post_content, 'afc/partner-directory' );
	}

	return false;
}

/**
 * Published partner count for metrics display.
 */
function afc_partner_directory_get_published_partner_count(): int {
	$count = wp_count_posts( 'partner' );
	return isset( $count->publish ) ? (int) $count->publish : 0;
}

/**
 * Includes a template partial from templates/partials/.
 *
 * @param string $slug Partial filename without extension.
 * @param array  $args Variables exposed to the partial.
 */
function afc_partner_directory_render_partial( string $slug, array $args = array() ): void {
	$path = afc_partner_directory_path( "templates/partials/{$slug}.php" );
	if ( ! file_exists( $path ) ) {
		return;
	}

	if ( ! empty( $args ) ) {
		// phpcs:ignore WordPress.PHP.DontExtract.extract_extract -- controlled partial args.
		extract( $args, EXTR_SKIP );
	}

	include $path;
}

/**
 * Renders the branded site header.
 */
function afc_partner_directory_render_shell_header(): void {
	afc_partner_directory_render_partial( 'header' );
}

/**
 * Renders the branded site footer.
 */
function afc_partner_directory_render_shell_footer(): void {
	afc_partner_directory_render_partial( 'footer' );
}

/**
 * Renders the directory page hero.
 */
function afc_partner_directory_render_shell_hero(): void {
	afc_partner_directory_render_partial(
		'hero',
		array(
			'partner_count' => afc_partner_directory_get_published_partner_count(),
		)
	);
}

/**
 * Renders informational sections below the directory.
 */
function afc_partner_directory_render_shell_institutional_sections(): void {
	afc_partner_directory_render_partial( 'institutional-sections' );
}

/**
 * Wraps partner directory block output with shell page structure.
 *
 * @param string $block_content Rendered block HTML.
 * @param array  $block         Block data.
 * @return string
 */
function afc_partner_directory_wrap_directory_block( string $block_content, array $block ): string {
	if ( 'afc/partner-directory' !== ( $block['blockName'] ?? '' ) ) {
		return $block_content;
	}

	ob_start();
	afc_partner_directory_render_shell_hero();
	$hero = ob_get_clean();

	ob_start();
	afc_partner_directory_render_shell_institutional_sections();
	$sections = ob_get_clean();

	$directory_region = sprintf(
		'<div class="afc-directory-region"><h2 id="afc-directory-region-title" class="afc-directory-region__title">%s</h2>%s</div>',
		esc_html__( 'Partner Directory', 'afc-partner-directory' ),
		$block_content
	);

	return sprintf(
		'<div class="afc-app-page afc-app-page--directory">%1$s<div class="afc-app-container afc-app-container--directory">%2$s</div>%3$s</div>',
		$hero,
		$directory_region,
		$sections
	);
}

/**
 * Registers shell hooks on eligible requests.
 */
function afc_partner_directory_init_frontend_shell(): void {
	if ( ! afc_partner_directory_should_use_shell() ) {
		return;
	}

	add_filter( 'body_class', 'afc_partner_directory_shell_body_class' );
	add_filter( 'render_block', 'afc_partner_directory_wrap_directory_block', 10, 2 );
}
add_action( 'wp', 'afc_partner_directory_init_frontend_shell' );

/**
 * Adds body class when shell is active.
 *
 * @param array $classes Body classes.
 * @return array
 */
function afc_partner_directory_shell_body_class( array $classes ): array {
	$classes[] = 'afc-has-directory-shell';
	return $classes;
}

/**
 * Enqueues frontend shell styles and scripts.
 */
function afc_partner_directory_enqueue_frontend_shell_assets(): void {
	if ( ! afc_partner_directory_should_use_shell() ) {
		return;
	}

	wp_enqueue_style(
		'afc-partner-directory-shell',
		afc_partner_directory_url( 'assets/css/frontend-shell.css' ),
		array(),
		AFC_PARTNER_DIRECTORY_VERSION
	);

	wp_enqueue_script(
		'afc-partner-directory-shell',
		afc_partner_directory_url( 'assets/js/shell.js' ),
		array(),
		AFC_PARTNER_DIRECTORY_VERSION,
		true
	);
}
