<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Product_Chimp
 */

// ACF Variables
$fields              = get_field('settings-footer', 'option');
$footer_socials      = $fields['settings-footer-socials'] ?? null;
$footer_copytext     = $fields['settings-footer__copytext'] ?? null;
$footer_copytext_mob = $fields['settings-footer__copytext--mob'] ?? null; ?>

<footer id="colophon" class="site-footer">

    <div class="container">
        <div class="site-footer-content">

            <div class="site-footer-content__branding">
                <?php
                the_custom_logo();

                if ($footer_socials) : ?>
                    <div class="site-footer-content__socials">
                        <?php
                        foreach ($footer_socials as $key => $social) :
                            $social_icon = $social['settings-footer-social__icon'] ?? null;
                            $social_link = $social['settings-footer-social__link'] ?? null;

                            if ($social_icon && $social_link) : ?>
                                <a href="<?= $social_link['url'] ?>"
                                   target="<?= $social_link['target'] ?>"
                                   aria-label="<?= $social_link['title'] ?>">
                                    <?= sprite_svg($social_icon['ID'], 31, 30, TRUE) ?>
                                </a>
                            <?php
                            endif;

                        endforeach; ?>
                    </div>
                <?php
                endif; ?>
            </div>

            <?php
            $widget_class = (is_active_sidebar('footer-widget-1')
                && is_active_sidebar('footer-widget-2')
                && is_active_sidebar('footer-widget-3')
                && is_active_sidebar('footer-widget-4')
                && is_active_sidebar('footer-widget-5'))
                ? 'site-footer-content__widgets--full'
                : 'site-footer-content__widgets--partial'; ?>

            <div class="site-footer-content__widgets <?= $widget_class ?>">
                <?php if (is_active_sidebar('footer-widget-1'))
                    dynamic_sidebar('footer-widget-1'); ?>

                <?php if (is_active_sidebar('footer-widget-2'))
                    dynamic_sidebar('footer-widget-2'); ?>

                <?php if (is_active_sidebar('footer-widget-3'))
                    dynamic_sidebar('footer-widget-3'); ?>

                <?php if (is_active_sidebar('footer-widget-4'))
                    dynamic_sidebar('footer-widget-4'); ?>

                <?php if (is_active_sidebar('footer-widget-5'))
                    dynamic_sidebar('footer-widget-5'); ?>
            </div>
        </div>

        <div class="site-footer-copyright">
            <?php
            if ($footer_copytext) : ?>
                <span class="site-footer-copyright__copytext site-footer-copyright__copytext--desktop">
                    <?= '©' . ' ' . date("Y") . ' ' . $footer_copytext ?>
                </span>
            <?php
            endif;

            if ($footer_copytext_mob) : ?>
                <span class="site-footer-copyright__copytext site-footer-copyright__copytext--mobile">
                    <?= '©' . ' ' . date("Y") . ' ' . $footer_copytext_mob ?>
                </span>
            <?php
            endif;

            wp_nav_menu(
                array(
                    'theme_location'  => 'menu-5',
                    'menu_id'         => 'copyright-menu',
                    'container_class' => 'copyright-navigation',
                    'depth'           => 1,
                )
            ); ?>
        </div>
    </div>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
