<?php
/**
 * Product Chimp scripts functions
 *
 * Enqueue / Register scripts and styles
 *
 * @package Product_Chimp
 */

/**
 * /inc/ directory override core functions
 */
if ( file_exists( get_template_directory() . '/inc/scripts.php' ) ) {
	require get_template_directory() . '/inc/scripts.php';
}

if ( ! function_exists( 'product_chimp_scripts' ) ) {
	/**
	 * Enqueue Stylesheets / Scripts
	 *
	 * @return void
	 */
	function product_chimp_scripts(): void {
		// Theme Version Stylesheet.
		wp_enqueue_style( 'product-chimp-style', get_stylesheet_uri(), array(), PRODUCT_CHIMP_VERSION );

		// Theme Main Stylesheet.
		wp_enqueue_style( 'product-chimp-main', get_template_directory_uri() . '/assets/css/main.min.css', array(), filemtime( get_template_directory() . '/assets/css/main.min.css' ) );

		// Theme Main Script.
		wp_enqueue_script( 'product-chimp-main', get_template_directory_uri() . '/assets/js/main.min.js', array(), filemtime( get_template_directory() . '/assets/js/main.min.js' ), true );

		// Comments.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Load Custom Scripts / Styles Here.
	}
}
add_action( 'wp_enqueue_scripts', 'product_chimp_scripts' );

if ( ! function_exists( 'product_chimp_admin_scripts' ) ) {
	/**
	 * Theme Dashboard Stylesheet
	 *
	 * @param string $hook // caller hook string.
	 *
	 * @return void
	 */
	function product_chimp_admin_scripts( string $hook ): void {
		/*
		 * ──────────────────────────────────────────────────
		 * 1. Load the media‑modal files once.
		 *    Only required on nav‑menus.php, so guard it:
		 * ──────────────────────────────────────────────────
		 */
		if ( 'nav-menus.php' === $hook ) {
			wp_enqueue_media(); // adds  wp.media + styles.
			$script_deps = array( 'jquery', 'media-editor' );
		} else {
			$script_deps = array( 'jquery' );
		}

		// Main Admin Stylesheet. Runs *after* media‑modal JS.
		wp_enqueue_style(
			'admin-styles',
			get_template_directory_uri() . '/assets/css/admin.min.css',
			array(),
			filemtime( get_template_directory() . '/assets/css/admin.min.css' ),
			false
		);

		// Main Admin Scripts.
		wp_enqueue_script(
			'admin-scripts',
			get_template_directory_uri() . '/assets/js/admin.min.js',
			array( 'jquery' ),
			filemtime( get_template_directory() . '/assets/js/admin.min.js' ),
			true
		);

		// pass data to Main Admin Scripts.
		wp_localize_script(
			'admin-scripts',
			'product_chimp_plugin_notice',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'product_chimp_ignore_plugin_notice' ),
			)
		);
	}
}
add_action( 'admin_enqueue_scripts', 'product_chimp_admin_scripts' );

if ( ! function_exists( 'product_chimp_enable_separate_core_block_assets' ) ) {
	/**
	 * Block Styles - Loading Enhancement
	 *  Only Load Styles for used blocks
	 *  since v5.8
	 *
	 * Ref_01: https://stackoverflow.com/a/76836510/22644768
	 * Ref_02: https://make.wordpress.org/core/2021/07/01/block-styles-loading-enhancements-in-wordpress-5-8/
	 *
	 * @return bool
	 */
	function product_chimp_enable_separate_core_block_assets(): bool {
		return true;
	}
}
add_filter( 'should_load_separate_core_block_assets', 'product_chimp_enable_separate_core_block_assets' );
