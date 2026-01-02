<?php
/**
 * The header for our theme
 *
 * This is the template that displays all the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Product_Chimp
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary">
        <?php esc_html_e('Skip to content', 'product-chimp'); ?>
    </a>

    <header id="masthead" class="site-header">
        <div class="union"></div>

        <div class="container">
            <div class="site-branding">
                <?php
                the_custom_logo(); ?>
            </div><!-- .site-branding -->

            <nav id="site-navigation" class="site-navigation">

                <div class="navigation__burger">
                    <input type="checkbox" id="navBurgerController"
                           class="navigation__burger-controller screen-reader-text">

                    <label for="navBurgerController" aria-label="toggle menu">
                        <div class="navigation__burger-bars">
                            <span class="bar bar1"></span>
                            <span class="bar bar2"></span>
                            <span class="bar bar3"></span>
                            <span class="bar bar4"></span>
                        </div>
                    </label>
                </div>

                <?php
                wp_nav_menu(
                    array(
                        'theme_location'  => 'menu-1',
                        'menu_id'         => 'primary-menu',
                        'container_class' => 'site-navigation-primary'
                    )
                );

                wp_nav_menu(
                    array(
                        'theme_location'  => 'menu-2',
                        'menu_id'         => 'login-menu',
                        'container_class' => 'site-navigation-login'
                    )
                );

                wp_nav_menu(
                    array(
                        'theme_location'  => 'menu-3',
                        'menu_id'         => 'getting-started-menu',
                        'container_class' => 'site-navigation-gstarted'
                    )
                );
                ?>
            </nav><!-- #site-navigation -->
        </div>
    </header><!-- #masthead -->
