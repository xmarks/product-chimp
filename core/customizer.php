<?php
/**
 * Product Chimp Theme Customizer
 *
 * @package Product_Chimp
 */

/**
 * /inc/ directory override core functions
 */
if ( file_exists( get_template_directory() . '/inc/customizer.php' ) ) {
	require get_template_directory() . '/inc/customizer.php';
}

if ( ! function_exists( 'product_chimp_customize_register' ) ) {
	/**
	 * Theme Settings Customizer
	 *
	 * @param WP_Customize_Manager $wp_customize The WP_Customize_Manager instance for handling the customizer options.
	 *
	 * @return void
	 */
	function product_chimp_customize_register( WP_Customize_Manager $wp_customize ): void {

		// Add main panel - "Theme Settings".
		$wp_customize->add_panel(
			'product_chimp_theme_settings_panel',
			array(
				'title'    => __( 'Theme Settings', 'product-chimp' ),
				'priority' => 30,
			)
		);

		/**
		 * Subsection - `Options`
		 */
		$wp_customize->add_section(
			'product_chimp_options',
			array(
				'title'    => __( 'Options', 'product-chimp' ),
				'priority' => 31,
				'panel'    => 'product_chimp_theme_settings_panel', // Attach this section to the panel.
			)
		);

		// Add subsection "Options" setting for "Disable Emojis" checkbox.
		$wp_customize->add_setting(
			'product_chimp_disable_emojis',
			array(
				'default'   => true,
				'transport' => 'refresh', // or 'postMessage' if using live preview.
			)
		);

		// Add control for "Disable Emojis" checkbox (styled as a toggle).
		$wp_customize->add_control(
			'product_chimp_disable_emojis_control',
			array(
				'label'    => __( 'Disable Emojis', 'product-chimp' ),
				'section'  => 'product_chimp_options',
				'settings' => 'product_chimp_disable_emojis',
				'type'     => 'checkbox', // TRUE/FALSE checkbox.
				'priority' => 10,
			)
		);

		// Add subsection "Options" setting for "Enable AnimateCSS" checkbox.
		$wp_customize->add_setting(
			'product_chimp_enable_animatecss',
			array(
				'default'   => false, // unchecked by default.
				'transport' => 'refresh', // or 'postMessage' if using live preview.
			)
		);

		// Add control for "Enable AnimateCSS" checkbox.
		$wp_customize->add_control(
			'product_chimp_enable_animatecss_control',
			array(
				'label'    => __( 'Enable AnimateCSS', 'product-chimp' ),
				'section'  => 'product_chimp_options',
				'settings' => 'product_chimp_enable_animatecss',
				'type'     => 'checkbox',
				'priority' => 11,
			)
		);

		/**
		 * Subsection - `Security`
		 */
		$wp_customize->add_section(
			'product_chimp_security',
			array(
				'title'    => __( 'Security', 'product-chimp' ),
				'priority' => 32,
				'panel'    => 'product_chimp_theme_settings_panel', // Attach this section to the panel.
			)
		);

		// Add setting for "Disable Users REST API" checkbox.
		$wp_customize->add_setting(
			'product_chimp_disable_users_rest_api',
			array(
				'default'   => false,
				'transport' => 'refresh', // or 'postMessage' if using live preview.
			)
		);

		// Add control for "Disable Users REST API" checkbox (styled as a toggle).
		$wp_customize->add_control(
			'product_chimp_disable_users_rest_api_control',
			array(
				'label'    => __( 'Disable Users REST API', 'product-chimp' ),
				'section'  => 'product_chimp_security',
				'settings' => 'product_chimp_disable_users_rest_api',
				'type'     => 'checkbox', // TRUE/FALSE checkbox.
				'priority' => 10,
			)
		);

		// Add setting for "Disable WordPress Version" checkbox.
		$wp_customize->add_setting(
			'product_chimp_disable_wp_version',
			array(
				'default'   => false,
				'transport' => 'refresh', // or 'postMessage' if using live preview.
			)
		);

		// Add control for "Disable WordPress Version" checkbox (styled as a toggle).
		$wp_customize->add_control(
			'product_chimp_disable_wp_version_control',
			array(
				'label'    => __( 'Disable WordPress Version', 'product-chimp' ),
				'section'  => 'product_chimp_security',
				'settings' => 'product_chimp_disable_wp_version',
				'type'     => 'checkbox', // TRUE/FALSE checkbox.
				'priority' => 11,
			)
		);

		/**
		 * Subsection - `Thumbnails`.
		 *
		 * Create a persistent list of sizes as they become available to the Theme.
		 */
		function product_chimp_scan_and_store_thumbnail_sizes(): void {
			$existing_sizes = get_option( 'product_chimp_all_thumbnail_sizes', array() );
			$new_sizes      = array();

			global $_wp_additional_image_sizes;

			foreach ( get_intermediate_image_sizes() as $size ) {
				if ( isset( $_wp_additional_image_sizes[ $size ] ) ) {
					$new_sizes[ $size ] = array(
						'width'  => $_wp_additional_image_sizes[ $size ]['width'],
						'height' => $_wp_additional_image_sizes[ $size ]['height'],
						'crop'   => $_wp_additional_image_sizes[ $size ]['crop'],
					);
				} else {
					$new_sizes[ $size ] = array(
						'width'  => get_option( "{$size}_size_w" ),
						'height' => get_option( "{$size}_size_h" ),
						'crop'   => get_option( "{$size}_crop" ),
					);
				}
			}

			// Merge old + new sizes (old sizes remain if not detected anymore).
			$merged_sizes = array_merge( $existing_sizes, $new_sizes );

			update_option( 'product_chimp_all_thumbnail_sizes', $merged_sizes );
		}

		// Rescan and refresh the list.
		product_chimp_scan_and_store_thumbnail_sizes();

		// Store sizes to a variable.
		$thumbnail_sizes = get_option( 'product_chimp_all_thumbnail_sizes', array() );

		// Create Thumbnails subsection.
		$wp_customize->add_section(
			'product_chimp_thumbnails',
			array(
				'title'    => __( 'Thumbnails', 'product-chimp' ),
				'priority' => 33,
				'panel'    => 'product_chimp_theme_settings_panel',
			)
		);

		foreach ( $thumbnail_sizes as $size => $size_data ) {
			$setting_id = 'product_chimp_disable_thumbnail_' . $size;

			// Add setting for each thumbnail checkbox.
			$wp_customize->add_setting(
				$setting_id,
				array(
					'default'   => false,
					'transport' => 'refresh',
				)
			);

			// Add control for each thumbnail checkbox.
			$wp_customize->add_control(
				$setting_id . '_control',
				array(
					// translators: %1$s: Thumbnail size name, %2$d: Width in pixels, %3$d: Height in pixels.
					'label'    => sprintf( __( 'Disable %1$s (%2$d×%3$d)', 'product-chimp' ), $size, $size_data['width'], $size_data['height'] ),
					'section'  => 'product_chimp_thumbnails',
					'settings' => $setting_id,
					'type'     => 'checkbox',
					'priority' => 10,
				)
			);
		}

		/**
		 * Subsection - `Custom Code`.
		 *
		 * Code additions for site `<head>` section.
		 */

		$wp_customize->add_section(
			'product_chimp_custom_code',
			array(
				'title'    => __( 'Custom Code', 'product-chimp' ),
				'priority' => 33,
				'panel'    => 'product_chimp_theme_settings_panel',
			)
		);

		// Add setting for HTML Header code.
		$wp_customize->add_setting(
			'product_chimp_html_header_code',
			array(
				'default'           => '',
				'sanitize_callback' => 'product_chimp_wp_kses_header_code',
				'transport'         => 'refresh',
			)
		);

		// Add CodeMirror Highlighted control.
		$wp_customize->add_control(
			new WP_Customize_Code_Editor_Control(
				$wp_customize,
				'product_chimp_html_header_code_control',
				array(
					'label'     => __( 'HTML Header Code', 'product-chimp' ),
					'section'   => 'product_chimp_custom_code',
					'settings'  => 'product_chimp_html_header_code',
					'code_type' => 'text/html',
				)
			)
		);
	}
}
add_action( 'customize_register', 'product_chimp_customize_register' );

if ( ! function_exists( 'product_chimp_customize_preview_js' ) ) {
	/**
	 * Scripts load on Theme Customizer.
	 *
	 * Add Bind JS handlers to make Theme Customizer preview reload changes asynchronously.
	 */
	function product_chimp_customize_preview_js(): void {
		wp_enqueue_script( 'underscore-customizer', get_template_directory_uri() . '/assets/js/customizer.min.js', array( 'customize-preview' ), PRODUCT_CHIMP_VERSION, true );
	}
}
add_action( 'customize_preview_init', 'product_chimp_customize_preview_js' );
