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
        $fields      = get_field($block_name);
        $heading     = $fields["{$block_name}__heading"] ?? null;
        $promo_items = $fields["{$block_name}-items"] ?? null; ?>
        <div class="container">
            <div class="<?= $block_name ?>-section-wrapper">

                <?php
                if ($heading) : ?>
                    <h3 class="<?= $block_name ?>-section__heading">
                        <?= $heading ?>
                    </h3>
                <?php
                endif; ?>

                <div class="<?= $block_name ?>-section-content enter-view">

                    <?php
                    foreach ($promo_items as $key => $item) :
                        $item_image = $item["{$block_name}-item__image"] ?? null;
                        $item_heading = $item["{$block_name}-item__heading"] ?? null;
                        $item_desc = $item["{$block_name}-item__desc"] ?? null; ?>

                        <div class="<?= $block_name ?>-section-item animate__animated opacity-0"
                            data-animate-class="animate__pulse"
                             style="animation-delay: <?= 200 + (200 * ($key * 2)) ?>ms">
                            <?php
                            if ($item_image) : ?>
                                <div class="<?= $block_name ?>-section-item__img">
                                    <img class=""
                                         src="<?= $item_image['url'] ?>"
                                         alt="<?= $item_image['title'] ?>"
                                         width="<?= $item_image['width'] ?>"
                                         height="<?= $item_image['height'] ?>"/>
                                </div>
                            <?php
                            endif;

                            if ($item_heading) : ?>
                                <h4 class="<?= $block_name ?>-section-item__heading">
                                    <?= $item_heading ?>
                                </h4>
                            <?php
                            endif;

                            if ($item_desc) : ?>
                                <p class="<?= $block_name ?>-section-item__desc">
                                    <?= $item_desc ?>
                                </p>
                            <?php
                            endif; ?>
                        </div>
                    <?php
                    endforeach; ?>
                </div>
            </div>
        </div>
    </section>
<?php
endif; ?>
