<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Product_Chimp
 */

get_header('clean');

$fields  = get_field('settings-404', 'option');
$image   = $fields['settings-404__image'] ?? null;
$heading = $fields['settings-404__heading'] ?? null;
$text    = $fields['settings-404__text'] ?? null;
$cta     = $fields['settings-404__cta'] ?? null; ?>

    <main id="primary" class="site-main">

        <section class="error-404 not-found">
            <?php
            if($image) : ?>
                <img class="error-404__img"
                     src="<?= $image['url'] ?>"
                     alt="<?= $image['title'] ?>"
                     width="<?= $image['width'] ?>"
                     height="<?= $image['height'] ?>"/>
            <?php
            endif; ?>

            <header class="page-header">
                <?php
                if($heading) : ?>
                    <h1 class="error-404__heading">
                        <?php esc_html_e($heading); ?>
                    </h1>
                <?php
                endif; ?>
            </header><!-- .page-header -->

            <div class="page-content">
                <?php
                if($text) : ?>
                    <p class="error-404__desc">
                        <?php esc_html_e($text); ?>
                    </p>
                <?php
                endif;

                if($cta) : ?>
                    <a class="error-404__cta btn"
                       href="<?= $cta['url'] ?>"
                       target="<?= $cta['target'] ?>">
                        <span class="inside">
                            <span><?= $cta['title'] ?></span>
                        </span>
                    </a>
                <?php
                endif; ?>

            </div><!-- .page-content -->
        </section><!-- .error-404 -->
    </main><!-- #main -->

<?php
get_footer('clean');
