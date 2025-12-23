<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Product_Chimp
 */

/**
 * /inc/ directory override core functions
 */
if ( file_exists( get_template_directory() . '/inc/template-functions.php' ) ) {
	require get_template_directory() . '/inc/template-functions.php';
}

if ( ! function_exists( 'product_chimp_body_classes' ) ) {
	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array
	 */
	function product_chimp_body_classes( $classes ) {
		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		// Adds a class of no-sidebar when there is no sidebar present.
		if ( ! is_active_sidebar( 'sidebar-1' ) ) {
			$classes[] = 'no-sidebar';
		}

		return $classes;
	}
}
add_filter( 'body_class', 'product_chimp_body_classes' );

if ( ! function_exists( 'product_chimp_pingback_header' ) ) {
	/**
	 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
	 */
	function product_chimp_pingback_header() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}
}
add_action( 'wp_head', 'product_chimp_pingback_header' );

if ( ! function_exists( 'product_chimp_allowed_html_head_tags' ) ) {
	/**
	 * Allowed tags for custom HTML in <head>.
	 *
	 * @return array
	 */
	function product_chimp_allowed_html_head_tags(): array {
		return array(
			'script'   => array(
				'type'  => true,
				'src'   => true,
				'async' => true,
				'defer' => true,
			),
			'style'    => array(
				'type'  => true,
				'media' => true,
			),
			'link'     => array(
				'rel'  => true,
				'href' => true,
				'type' => true,
			),
			'meta'     => array(
				'name'    => true,
				'content' => true,
				'charset' => true,
			),
			'noscript' => array(),
			'title'    => array(),
			'base'     => array(
				'href'   => true,
				'target' => true,
			),
		);
	}
}

if ( ! function_exists( 'product_chimp_wp_kses_header_code' ) ) {
	/**
	 * Custom Sanitize for HTML `<head>`.
	 *
	 * @param string $input // Code to be sanitized.
	 * @return string
	 */
	function product_chimp_wp_kses_header_code( string $input ): string {
		return wp_kses( $input, product_chimp_allowed_html_head_tags() );
	}
}

if ( ! function_exists( 'st_get_block_attributes' ) ) {
	/**
	 * Generate and return block attributes.
	 *
	 * @param string $block_id // unique block ID or anchor.
	 * @param string $block_class // additional block classes.
	 *
	 * @return string
	 */
	function st_get_block_attributes( string $block_id, string $block_class ): string {
		return get_block_wrapper_attributes(
			array(
				'id'    => $block_id,
				'class' => $block_class,
			)
		);
	}
}

if ( ! function_exists( 'product_chimp_nav_menu_add_screen_option_columns' ) ) {
	/**
	 * Adds extra check‑boxes to Appearance ▸ Menus ▸ Screen Options
	 * – Unlink (text with no anchor)
	 * – Image  (media uploader)
	 *
	 * @param array $cols Columns.
	 * @return array
	 */
	function product_chimp_nav_menu_add_screen_option_columns( $cols ) {
		$cols['unlink'] = __( 'Unlink', 'product-chimp' );
		$cols['image']  = __( 'Image', 'product-chimp' );
		return $cols;
	}
}
add_filter( 'manage_nav-menus_columns', 'product_chimp_nav_menu_add_screen_option_columns', 20 );

if ( ! function_exists( 'product_chimp_nav_menu_custom_fields' ) ) {
	/**
	 * Output the extra controls inside each menu‑item box
	 *
	 * @param int $item_id Menu item ID.
	 * @return void
	 */
	function product_chimp_nav_menu_custom_fields( $item_id ) {
		// fetch saved values (if any).
		$unlink   = get_post_meta( $item_id, '_menu_item_unlink', true );
		$image_id = get_post_meta( $item_id, '_menu_item_image_id', true );
		$preview  = $image_id ? wp_get_attachment_image( $image_id, 'thumbnail' ) : '';

		/* ---------- Unlink (checkbox) ---------- */
		?>
		<p class="field-unlink description description-thin"><!-- class matches Screen‑Option slug -->
			<label for="edit-menu-item-unlink-<?php echo esc_attr( $item_id ); ?>">
				<input type="checkbox"
						id="edit-menu-item-unlink-<?php echo esc_attr( $item_id ); ?>"
						name="menu-item-unlink[<?php echo esc_attr( $item_id ); ?>]"
						value="1" <?php checked( $unlink ); ?> />
				<?php esc_html_e( 'Unlink', 'product-chimp' ); ?>
			</label>
		</p>
		<?php

		/* ---------- Image uploader ---------- */
		$has_image = (bool) $image_id;
		?>
		<p class="field-image description description-wide">
			<label>
				<?php esc_html_e( 'Image', 'product-chimp' ); ?>
			</label><br>

			<!-- hidden ID -->
			<input type="hidden"
					class="menu-item-image-id"
					name="menu-item-image[<?php echo esc_attr( $item_id ); ?>]"
					value="<?php echo esc_attr( $image_id ); ?>"/>

			<!-- preview / actions -->
			<span class="menu-image-wrapper <?php echo $has_image ? 'has-image' : ''; ?>">
				<span class="menu-image-preview"><?php echo wp_kses_post( $preview ); ?></span>

				<button type="button"
						class="button button-small upload-menu-image">
					<?php
					echo esc_html(
						$has_image
							? __( 'Change', 'product-chimp' )
							: __( 'Set image', 'product-chimp' )
					);
					?>
				</button>

				<button type="button"
						class="button button-small remove-menu-image"
						style="<?php echo $has_image ? '' : 'display:none'; ?>">
					<?php esc_html_e( 'Remove', 'product-chimp' ); ?>
				</button>
			</span>
		</p>
		<?php
	}
}
add_action( 'wp_nav_menu_item_custom_fields', 'product_chimp_nav_menu_custom_fields', 10, 2 );

if ( ! function_exists( 'product_chimp_nav_menu_save_custom_fields' ) ) {
	/**
	 * Save the two custom fields when the menu is saved
	 *
	 * @param int $menu_id Menu ID.
	 * @param int $menu_item_db_id Menu item DB ID.
	 * @return void
	 */
	function product_chimp_nav_menu_save_custom_fields( $menu_id, $menu_item_db_id ) {
		/*
		 * If this is the full "Save Menu" form submission, the nonce we want
		 * **is present** and should be checked.
		 * During the AJAX 'add-menu-item' call the field is absent, so we
		 * just skip this block (core has already verified a different nonce).
		 */
		if ( wp_doing_ajax() ) {
			check_ajax_referer( 'add-menu_item', 'menu-settings-column-nonce' );
		} else {
			check_admin_referer( 'update-nav_menu', 'update-nav-menu-nonce' );
		}

		/* -------- Unlink checkbox -------- */
		if ( isset( $_POST['menu-item-unlink'][ $menu_item_db_id ] ) ) {
			update_post_meta( $menu_item_db_id, '_menu_item_unlink', 1 );
		} else {
			delete_post_meta( $menu_item_db_id, '_menu_item_unlink' );
		}

		/* -------- Image ID field --------- */
		if ( isset( $_POST['menu-item-image'][ $menu_item_db_id ] ) ) {
			$image_id = absint( $_POST['menu-item-image'][ $menu_item_db_id ] );

			if ( $image_id ) {
				update_post_meta( $menu_item_db_id, '_menu_item_image_id', $image_id );
			} else {
				delete_post_meta( $menu_item_db_id, '_menu_item_image_id' );
			}
		}
	}
}
add_action( 'wp_update_nav_menu_item', 'product_chimp_nav_menu_save_custom_fields', 10, 2 );
