<?php
/**
 * Product Chimp widgets functions
 *
 * Register widget area
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 *
 * @package Product_Chimp
 */

/**
 * /inc/ directory override core functions
 */
if ( file_exists( get_template_directory() . '/inc/widgets.php' ) ) {
	require get_template_directory() . '/inc/widgets.php';
}

if ( ! function_exists( 'product_chimp_widgets_init' ) ) {
	/**
	 * Register sidebar
	 *
	 * @return void
	 */
	function product_chimp_widgets_init(): void {

        register_sidebar(
            array(
                'name'          => esc_html__('Blog Post Sidebar Left', 'sweept'),
                'id'            => 'bp-sidebar-left',
                'description'   => esc_html__('Add widgets here.', 'sweept'),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__('Blog Post Sidebar Right', 'sweept'),
                'id'            => 'bp-sidebar-right',
                'description'   => esc_html__('Add widgets here.', 'sweept'),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__('Blog Post Bottom Widgets', 'sweept'),
                'id'            => 'bp-widget-bottom',
                'description'   => esc_html__('Add widgets here.', 'sweept'),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__('Blog / Archive Bottom Widgets', 'sweept'),
                'id'            => 'archive-widget-bottom',
                'description'   => esc_html__('Add widgets here.', 'sweept'),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__('Footer - Widget 1', 'sweept'),
                'id'            => 'footer-widget-1',
                'description'   => esc_html__('Add widgets here.', 'sweept'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h4 class="widget-title">',
                'after_title'   => '</h4>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__('Footer - Widget 2', 'sweept'),
                'id'            => 'footer-widget-2',
                'description'   => esc_html__('Add widgets here.', 'sweept'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h4 class="widget-title">',
                'after_title'   => '</h4>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__('Footer - Widget 3', 'sweept'),
                'id'            => 'footer-widget-3',
                'description'   => esc_html__('Add widgets here.', 'sweept'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h4 class="widget-title">',
                'after_title'   => '</h4>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__('Footer - Widget 4', 'sweept'),
                'id'            => 'footer-widget-4',
                'description'   => esc_html__('Add widgets here.', 'sweept'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h4 class="widget-title">',
                'after_title'   => '</h4>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__('Footer - Widget 5', 'sweept'),
                'id'            => 'footer-widget-5',
                'description'   => esc_html__('Add widgets here.', 'sweept'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h4 class="widget-title">',
                'after_title'   => '</h4>',
            )
        );
	}
}
add_action( 'widgets_init', 'product_chimp_widgets_init' );
