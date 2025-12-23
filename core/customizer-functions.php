<?php
/**
 * Product Chimp Theme Customizer Functions
 *
 * @package Product_Chimp
 */

/**
 * /inc/ directory override core functions
 */
if ( file_exists( get_template_directory() . '/inc/customizer-functions.php' ) ) {
	require get_template_directory() . '/inc/customizer-functions.php';
}

if ( get_theme_mod( 'product_chimp_disable_emojis', true ) ) {

	if ( ! function_exists( 'product_chimp_disable_wordpress_core_emojis' ) ) {
		/**
		 * Disable WordPress Core Emojis
		 * Uses theme customizer setting `product_chimp_disable_emojis`.
		 *
		 * @return void
		 */
		function product_chimp_disable_wordpress_core_emojis(): void {

			// Remove actions and filters related to emojis.
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_action( 'admin_print_styles', 'print_emoji_styles' );
			remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

			// Remove the TinyMCE emoji plugin.
			add_filter( 'tiny_mce_plugins', 'product_chimp_remove_tinymce_emoji' );

			// Remove emoji CDN hostname from DNS prefetching hints.
			add_filter( 'wp_resource_hints', 'product_chimp_remove_emoji_prefetch', 10, 2 );
		}
	}

	if ( ! function_exists( 'product_chimp_remove_tinymce_emoji' ) ) {
		/**
		 * Remove TinyMCE emoji plugin.
		 *
		 * @param array $plugins TinyMCE plugins array.
		 * @return array
		 */
		function product_chimp_remove_tinymce_emoji( $plugins ) {
			if ( is_array( $plugins ) ) {
				return array_diff( $plugins, array( 'wpemoji' ) );
			} else {
				return array();
			}
		}
	}

	if ( ! function_exists( 'product_chimp_remove_emoji_prefetch' ) ) {
		/**
		 * Remove emoji CDN hostname from DNS prefetching hints.
		 *
		 * @param array  $urls          URLs to print for resource hints.
		 * @param string $relation_type The relation type the URLs are printed.
		 * @return array
		 */
		function product_chimp_remove_emoji_prefetch( $urls, $relation_type ) {
			if ( 'dns-prefetch' === $relation_type ) {
				$emoji_svg_url = 'https://s.w.org/images/core/emoji/2/svg/';
				$urls          = array_diff( $urls, array( $emoji_svg_url ) );
			}
			return $urls;
		}
	}

	// Hook the function to 'init' and use theme setting "product_chimp_disable_emojis".
	add_action( 'init', 'product_chimp_disable_wordpress_core_emojis' );
}


if ( get_theme_mod( 'product_chimp_enable_animatecss', false ) ) {

	if ( ! function_exists( 'product_chimp_enable_animatecss' ) ) {
		/**
		 * Enqueue Stylesheet / Scripts for AnimateCSS support.
		 * Uses theme customizer setting `product_chimp_enable_animatecss`.
		 *
		 * @return void
		 */
		function product_chimp_enable_animatecss(): void {

			// Enqueue animateCSS Stylesheet.
			wp_enqueue_style( 'plugins.animatecss.animate' );

			// Enqueue script for in-view loading.
			wp_enqueue_script( 'plugins.animatecss.animate' );
		}
	}

	// Hook the function to 'wp_enqueue_scripts' and use theme setting "product_chimp_enable_animatecss".
	add_action( 'wp_enqueue_scripts', 'product_chimp_enable_animatecss' );
}


if ( get_theme_mod( 'product_chimp_disable_users_rest_api', false ) ) {

	if ( ! function_exists( 'product_chimp_disable_rest_users_get' ) ) {
		/**
		 * Disable WordPress REST API `users` Endpoint for GET requests.
		 * Returns a WordFence-style error response.
		 * Uses theme customizer setting `product_chimp_disable_users_rest_api`.
		 *
		 * @param mixed           $result  Response to replace the requested version with.
		 * @param WP_REST_Server  $server  Server instance.
		 * @param WP_REST_Request $request Request used to generate the response.
		 *
		 * @return mixed Original result or WP_Error if users endpoint.
		 */
		function product_chimp_disable_rest_users_get( $result, $server, $request ) {

			// Get the requested route.
			$route = $request->get_route();

			// Check if this is a users' endpoint.
			if ( strpos( $route, '/wp/v2/users' ) === 0 ) {
				return new WP_Error(
					'rest_user_cannot_view',
					__( 'Sorry, you are not allowed to list users.', 'product-chimp' ),
					array( 'status' => 401 )
				);
			}

			return $result;
		}
	}

	// Hook the function into the 'rest_pre_dispatch' filter.
	add_filter( 'rest_pre_dispatch', 'product_chimp_disable_rest_users_get', 10, 3 );
}


if ( get_theme_mod( 'product_chimp_disable_wp_version', false ) ) {

	if ( ! function_exists( 'product_chimp_remove_wp_version_from_assets' ) ) {
		/**
		 * Function to remove WordPress version query string from scripts and styles.
		 * Replace it instead with Theme Version Constant.
		 *
		 * @param string $src style or script src link.
		 * @return string
		 */
		function product_chimp_remove_wp_version_from_assets( string $src ): string {

			// Get the installed WordPress version.
			$wp_version = get_bloginfo( 'version' );

			// Check if the URL contains the "ver=" parameter.
			if ( str_contains( $src, 'ver=' ) ) {
				// Extract the query string (everything after "?").
				$original_version = wp_parse_url( $src, PHP_URL_QUERY );

				// Convert query string into an associative array.
				parse_str( $original_version, $query_params );

				// If the 'ver' parameter exists and matches the WordPress version, replace it.
				if ( isset( $query_params['ver'] ) && $query_params['ver'] === $wp_version ) {
					$src = add_query_arg( 'ver', PRODUCT_CHIMP_VERSION, remove_query_arg( 'ver', $src ) );
				}
			}

			return $src;
		}
	}

	if ( ! function_exists( 'product_chimp_disable_wp_version' ) ) {
		/**
		 * Function to remove WordPress version generator from <head>
		 *     Also calls style/script loader src ver replacer.
		 *
		 * @return void
		 */
		function product_chimp_disable_wp_version(): void {
			// Remove WordPress version meta tag from the header.
			remove_action( 'wp_head', 'wp_generator' );

			// Remove WordPress version from RSS feeds.
			add_filter( 'the_generator', '__return_empty_string' );

			// Remove WordPress version from script and style URLs.
			add_filter( 'style_loader_src', 'product_chimp_remove_wp_version_from_assets', 9999 );
			add_filter( 'script_loader_src', 'product_chimp_remove_wp_version_from_assets', 9999 );
		}
	}

	add_action( 'init', 'product_chimp_disable_wp_version' );
}


if ( ! function_exists( 'product_chimp_manage_thumbnail_sizes' ) ) {
	/**
	 * Manage thumbnail sizes - remove disabled, ensure enabled are registered.
	 * Runs after theme/plugin setup to handle size registration.
	 *
	 * @return void
	 */
	function product_chimp_manage_thumbnail_sizes(): void {

		$stores_persistent_sizes = get_option( 'product_chimp_all_thumbnail_sizes', array() );

		foreach ( $stores_persistent_sizes as $size => $attributes ) {
			$size_setting_name = 'product_chimp_disable_thumbnail_' . $size;
			$is_disabled       = get_theme_mod( $size_setting_name, false );

			if ( ! $is_disabled ) {
				// Ensure enabled size is registered with correct dimensions.
				// This handles re-enabling sizes that were previously disabled.
				if ( ! empty( $attributes['width'] ) || ! empty( $attributes['height'] ) ) {
					add_image_size(
						$size,
						(int) $attributes['width'],
						(int) $attributes['height'],
						(bool) $attributes['crop']
					);
				}
			} else {
				// Remove disabled size to prevent on-the-fly generation.
				remove_image_size( $size );
			}
		}
	}
}

if ( ! function_exists( 'product_chimp_prevent_disabled_thumbnail_creation' ) ) {
	/**
	 * Prevent creation of disabled thumbnail sizes at the image editor level.
	 * This catches WooCommerce and other on-the-fly generation attempts.
	 *
	 * @param array $sizes Array of image sizes to create.
	 * @return array Filtered array with disabled sizes removed.
	 */
	function product_chimp_prevent_disabled_thumbnail_creation( $sizes ): array {

		$stores_persistent_sizes = get_option( 'product_chimp_all_thumbnail_sizes', array() );

		foreach ( $stores_persistent_sizes as $size => $attributes ) {
			$size_setting_name = 'product_chimp_disable_thumbnail_' . $size;
			$is_disabled       = get_theme_mod( $size_setting_name, false );

			if ( $is_disabled && isset( $sizes[ $size ] ) ) {
				unset( $sizes[ $size ] );
			}
		}

		return $sizes;
	}
}

if ( ! function_exists( 'product_chimp_disable_thumbnail_sizes' ) ) {
	/**
	 * Handle disabling sizes defined from Customizer.
	 * Filters sizes during upload/regeneration.
	 *
	 * @param array $sizes current sizes available.
	 *
	 * @return array
	 */
	function product_chimp_disable_thumbnail_sizes( array $sizes ): array {

		$stores_persistent_sizes = get_option( 'product_chimp_all_thumbnail_sizes', array() );

		foreach ( $stores_persistent_sizes as $size => $attributes ) {
			$size_setting_name = 'product_chimp_disable_thumbnail_' . $size;
			$size_setting      = get_theme_mod( $size_setting_name, false );

			if ( $size_setting ) {
				if ( is_array( $sizes ) && in_array( $size, $sizes, true ) ) {
					$sizes = array_values( array_diff( $sizes, array( $size ) ) );
				}
			}
		}

		return $sizes;
	}
}

// Manage size registration/removal after theme setup (priority 99 = late).
add_action( 'after_setup_theme', 'product_chimp_manage_thumbnail_sizes', 99 );

// Filter sizes during upload/regeneration.
add_filter( 'intermediate_image_sizes', 'product_chimp_disable_thumbnail_sizes' );
add_filter( 'intermediate_image_sizes_advanced', 'product_chimp_disable_thumbnail_sizes' );

// Prevent disabled sizes at image editor level (catches on-the-fly generation).
add_filter( 'intermediate_image_sizes_advanced', 'product_chimp_prevent_disabled_thumbnail_creation', 10 );

// WooCommerce-specific filters (only if WooCommerce is active).
if ( class_exists( 'WooCommerce' ) ) {
	add_filter( 'woocommerce_regenerate_images_intermediate_image_sizes', 'product_chimp_disable_thumbnail_sizes' );
	add_filter( 'woocommerce_image_sizes_to_resize', 'product_chimp_prevent_disabled_thumbnail_creation', 10 );
}


if ( get_theme_mod( 'product_chimp_html_header_code', '' ) ) {

	if ( ! function_exists( 'product_chimp_add_html_header_code' ) ) {
		/**
		 * Output custom HTML header code inside <head>.
		 *
		 * @return void
		 */
		function product_chimp_add_html_header_code(): void {
			echo wp_kses(
				get_theme_mod( 'product_chimp_html_header_code', '' ),
				product_chimp_allowed_html_head_tags()
			);
		}
	}

	add_action( 'wp_head', 'product_chimp_add_html_header_code' );
}
