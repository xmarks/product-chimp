<?php
/**
 * Block template file: block-render.php
 *
 * @var array $block The block settings and attributes.
 * @var string $content The block inner HTML (empty).
 * @var bool $is_preview True during AJAX preview.
 * @var (int|string) $post_id The post ID this block is saved to.
 */

$block_name = str_replace('pc/', '', $block['name']);
$id         = $block_name . '-section-' . $block['id'];

wp_enqueue_style('block-' . $block_name);

if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Get items count early to determine if we need the slider
$fields_check = get_field($block_name);
$items_check  = $fields_check["{$block_name}-items"] ?? [];
$items_count  = is_array($items_check) ? count($items_check) : 0;
$use_slider   = $items_count >= 4;

if ($use_slider) {
    wp_enqueue_style('plugins.splidejs.core');
    wp_enqueue_script('plugins.splidejs.splide');
}

$classes = "{$block_name}-section builder-section";
if ($use_slider) {
    $classes .= ' has-mobile-slider';
}
if (!empty($block['className'])) {
    $classes .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $classes .= ' align' . $block['align'];
}

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => $classes
]);

if (isset($block['data']['preview_image_help'])) :
    $fileUrl = str_replace(get_stylesheet_directory(), '', dirname(__FILE__),); ?>
    <img src="<?= get_stylesheet_directory_uri()
    . $fileUrl . '/'
    . $block['data']['preview_image_help']
    ?>" style="width:100%; height:auto;"/>
<?php
else: ?>
    <section id="<?= esc_attr($id); ?>" <?= $wrapper_attributes; ?>>
        <?php
        $fields  = get_field($block_name);
        $heading = $fields["{$block_name}__heading"] ?? null;
        $items   = $fields["{$block_name}-items"] ?? null; ?>
        <div class="container">
            <div class="<?= $block_name ?>-section-wrapper">

                <?php if ($heading) : ?>
                    <h2 class="<?= $block_name ?>-section__heading">
                        <?= $heading ?>
                    </h2>
                <?php endif; ?>

                <?php if ($items) : ?>
                    <div class="<?= $block_name ?>-section-grid">
                        <?php foreach ($items as $key => $item) :
                            $icon  = $item["{$block_name}-item__icon"] ?? null;
                            $title = $item["{$block_name}-item__title"] ?? null;
                            $desc  = $item["{$block_name}-item__description"] ?? null;
                            ?>
                            <div class="<?= $block_name ?>-section-grid__card">
                                <?php if ($icon) : ?>
                                    <div class="<?= $block_name ?>-section-grid__icon">
                                        <img src="<?= $icon['url'] ?>"
                                             alt="<?= $icon['alt'] ?: $title ?>"
                                             width="<?= $icon['width'] ?>"
                                             height="<?= $icon['height'] ?>"/>
                                    </div>
                                <?php endif; ?>

                                <?php if ($title) : ?>
                                    <h3 class="<?= $block_name ?>-section-grid__title">
                                        <?= $title ?>
                                    </h3>
                                <?php endif; ?>

                                <?php if ($desc) : ?>
                                    <p class="<?= $block_name ?>-section-grid__desc">
                                        <?= $desc ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if ($use_slider) : ?>
                        <div id="splide-<?= esc_attr($id); ?>" class="splide <?= $block_name ?>-section-splide"
                             aria-label="platforms">
                            <div class="splide__track <?= $block_name ?>-section-splide__track">
                                <ul class="splide__list <?= $block_name ?>-section-splide__list">
                                    <?php foreach ($items as $key => $item) :
                                        $icon  = $item["{$block_name}-item__icon"] ?? null;
                                        $title = $item["{$block_name}-item__title"] ?? null;
                                        $desc  = $item["{$block_name}-item__description"] ?? null;
                                        ?>
                                        <li class="splide__slide <?= $block_name ?>-section-splide__slide">
                                            <div class="<?= $block_name ?>-section-splide__card">
                                                <?php if ($icon) : ?>
                                                    <div class="<?= $block_name ?>-section-splide__icon">
                                                        <img src="<?= $icon['url'] ?>"
                                                             alt="<?= $icon['alt'] ?: $title ?>"
                                                             width="<?= $icon['width'] ?>"
                                                             height="<?= $icon['height'] ?>"/>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ($title) : ?>
                                                    <h3 class="<?= $block_name ?>-section-splide__title">
                                                        <?= $title ?>
                                                    </h3>
                                                <?php endif; ?>

                                                <?php if ($desc) : ?>
                                                    <p class="<?= $block_name ?>-section-splide__desc">
                                                        <?= $desc ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php if ($use_slider) : ?>
        <script id="scripts-<?= esc_attr($id); ?>" type="text/javascript">
            document.addEventListener('DOMContentLoaded', function () {
                const splide = new Splide('#splide-<?= esc_attr($id); ?>', {
                    type: 'slide',
                    perPage: 1,
                    perMove: 1,
                    gap: '16px',
                    padding: { right: '20%' },
                    arrows: false,
                    pagination: false,
                });
                splide.mount();
            });
        </script>
    <?php endif; ?>
<?php
endif; ?>
