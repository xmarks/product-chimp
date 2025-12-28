<?php
/**
 * Block template file: block-render.php
 *
 * @var array $block The block settings and attributes.
 *
 * @var string $content The block inner HTML (empty).
 * @var bool $is_preview True during AJAX preview.
 * @var (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$block_name = str_replace('pc/', '', $block['name']);
$id         = $block_name . '-section-' . $block['id'];

wp_enqueue_style('block-' . $block_name);

if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$classes = "{$block_name}-section builder-section";
if (!empty($block['className'])) {
    $classes .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $classes .= ' align' . $block['align'];
}

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => $classes
]);

// reset attributes for editor
if ($is_preview) {
    // $wrapper_attributes = '';
}

// Load SplideJS
wp_enqueue_style('plugins.splidejs.core');
wp_enqueue_script('plugins.splidejs.splide');

if (isset($block['data']['preview_image_help'])) :
    $fileUrl = str_replace(get_stylesheet_directory(), '', dirname(__FILE__),); ?>
    <img src="<?= get_stylesheet_directory_uri()
    . $fileUrl . '/'
    . $block['data']['preview_image_help']
    ?>" style="width:100%; height:auto;"/>
<?php
else: ?>
    <section id="<?= esc_attr($id); ?>" <?= $wrapper_attributes; ?>>
        <?php // Fields
        $fields  = get_field($block_name);
        $heading = $fields["{$block_name}__heading"] ?? null;
        $tagline = $fields["{$block_name}__tagline"] ?? null;
        $items   = $fields["{$block_name}-items"] ?? null; ?>
        <div class="container">
            <div class="<?= $block_name ?>-section-wrapper">

                <?php
                if ($heading) : ?>
                    <h3 class="<?= $block_name ?>-section__heading">
                        <?= $heading ?>
                    </h3>
                <?php
                endif;

                if ($tagline) : ?>
                    <span class="<?= $block_name ?>-section__tagline">
                        <?= $tagline ?>
                    </span>
                <?php
                endif; ?>

                <div id="splide-<?= esc_attr($id); ?>" class="splide <?= $block_name ?>-section-splide"
                     aria-label="testimonials">
                    <div class="splide__track <?= $block_name ?>-section-splide__track">
                        <ul class="splide__list <?= $block_name ?>-section-splide__list">
                            <?php
                            foreach ($items as $key => $testimonial) :
                                $testimonial_author = $testimonial["{$block_name}-item__author"] ?? null;
                                $testimonial_avatar = $testimonial["{$block_name}-item__author-avatar"] ?? null;
                                $testimonial_position = $testimonial["{$block_name}-item__author-position"] ?? null;
                                $testimonial_quote = $testimonial["{$block_name}-item__author-quote"] ?? null; ?>

                                <li class="splide__slide <?= $block_name ?>-section-splide__slide">
                                    <div class="author-grid">
                                        <?php
                                        if ($testimonial_avatar) : ?>
                                            <div class="<?= $block_name ?>-section-splide__img">
                                                <img class=""
                                                     src="<?= $testimonial_avatar['url'] ?>"
                                                     alt="<?= $testimonial_avatar['title'] ?>"
                                                     width="<?= $testimonial_avatar['width'] ?>"
                                                     height="<?= $testimonial_avatar['height'] ?>"/>
                                            </div>
                                        <?php
                                        endif;

                                        if ($testimonial_author) : ?>
                                            <span class="<?= $block_name ?>-section-splide__author">
                                                <?= $testimonial_author ?>
                                        </span>
                                        <?php
                                        endif;

                                        if ($testimonial_position) : ?>
                                            <span class="<?= $block_name ?>-section-splide__position">
                                                <?= $testimonial_position ?>
                                            </span>
                                        <?php
                                        endif; ?>

                                        <div class="<?= $block_name ?>-section-splide__stars">
                                            <?php sprite_svg('icon-star', 16, 16) ?>
                                            <?php sprite_svg('icon-star', 16, 16) ?>
                                            <?php sprite_svg('icon-star', 16, 16) ?>
                                            <?php sprite_svg('icon-star', 16, 16) ?>
                                            <?php sprite_svg('icon-star', 16, 16) ?>
                                        </div>
                                    </div>

                                    <?php
                                    if ($testimonial_quote) : ?>
                                        <p class="<?= $block_name ?>-section-splide__quote">
                                            <?= '“ ' . $testimonial_quote . ' “' ?>
                                        </p>
                                    <?php
                                    endif; ?>
                                </li>
                            <?php
                            endforeach; ?>
                        </ul>
                    </div>

                    <div class="splide__arrows <?= $block_name ?>-section-splide__arrows splide__arrows--ltr <?= $block_name ?>-section-splide__arrows-ltr">
                        <button
                                class="splide__arrow <?= $block_name ?>-section-splide__arrow splide__arrow--prev <?= $block_name ?>-section-splide__arrow--prev"
                                type="button"
                                aria-label="Previous slide"
                                aria-controls="splide01-track"
                        >
                            <?php sprite_svg('icon-arrow-right', 17, 17) ?>
                        </button>
                        <button
                                class="splide__arrow <?= $block_name ?>-section-splide__arrow splide__arrow--next <?= $block_name ?>-section-splide__arrow--next"
                                type="button"
                                aria-label="Next slide"
                                aria-controls="splide01-track"
                        >
                            <?php sprite_svg('icon-arrow-right', 17, 17) ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script id="scripts-<?= esc_attr($id); ?>" type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            const splide = new Splide('#splide-<?= esc_attr($id); ?>', {
                type: 'loop',
                perPage: 3,
                perMove: 1,
                rewind: false,
                pagination: false,
                breakpoints: {
                    1440: {
                        perPage: 2,
                    },
                    768: {
                        perPage: 1,
                    },
                }
            });
            splide.mount();
        });
    </script>
<?php
endif; ?>
