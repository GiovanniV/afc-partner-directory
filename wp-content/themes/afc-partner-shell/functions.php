<?php
/**
 * AFC Partner Shell theme functions.
 *
 * @package AFC_Partner_Shell
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	function (): void {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	}
);
