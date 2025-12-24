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
wp_enqueue_script('plugins.splidejs.splide-extension-auto-scroll');

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
        $fields = get_field($block_name); ?>
        <div class="container">
            <div class="<?= $block_name ?>-section-wrapper">
                <div class="<?= $block_name ?>-section-static">
                    <?php
                    foreach ($fields as $key => $item) :
                        $logo = $item["{$block_name}__image"] ?? null;
                        if ($logo) : ?>
                            <div class="<?= $block_name ?>-section-static__item"
                                 style="width: <?= $logo['width'] ?>">
                                <img class="img"
                                     src="<?= $logo['url'] ?>"
                                     alt="<?= $logo['title'] ?>"
                                     width="<?= $logo['width'] ?>"
                                     height="<?= $logo['height'] ?>"/>
                            </div>
                        <?php
                        endif;
                    endforeach; ?>
                </div>


                <div id="splide-<?= esc_attr($id); ?>" class="splide <?= $block_name ?>-section-splide"
                     aria-label="logo-carousel">
                    <div class="splide__track <?= $block_name ?>-section-splide__track">
                        <ul class="splide__list <?= $block_name ?>-section-splide__list">
                            <?php
                            foreach ($fields as $key => $item) :
                                $logo = $item["{$block_name}__image"] ?? null;
                                if ($logo) : ?>
                                    <li class="splide__slide <?= $block_name ?>-section-splide__slide"
                                        style="width: <?= $logo['width'] ?>">
                                        <img class="img"
                                             src="<?= $logo['url'] ?>"
                                             alt="<?= $logo['title'] ?>"
                                             width="<?= $logo['width'] ?>"
                                             height="<?= $logo['height'] ?>"/>
                                    </li>
                                <?php
                                endif;
                            endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script id="scripts-<?= esc_attr($id); ?>" type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            const splide = new Splide('#splide-<?= esc_attr($id); ?>', {
                type: 'loop',
                drag: 'free',
                height: '64px',
                focus: 'center',
                arrows: false,
                pagination: false,
                autoWidth: true,
                autoScroll: {
                    speed: 1,
                },
            });

            splide.mount(window.splide.Extensions);
        });
    </script>
<?php
endif; ?>
