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
else:

    // Fields
    $fields = get_field($block_name);
    $variant        = $fields["{$block_name}__variant"] ?? 'default';
    $items          = $fields["{$block_name}-items"] ?? null;
    $cta            = $fields["{$block_name}-cta"] ?? null;

    if ($variant) {
        // Custom Classes
        $classes .= " {$block_name}-section--{$variant}";

        $wrapper_attributes = get_block_wrapper_attributes([
            'class' => $classes
        ]);
    } ?>
    <section id="<?= esc_attr($id); ?>" <?= $wrapper_attributes; ?>>
        <?php $count      = is_array($items) ? count($items) - 1 : 0;
        foreach ($items as $key => $item) :
            $item_label = $item["{$block_name}-item__label"] ?? null;
            $item_heading = $item["{$block_name}-item__heading"] ?? null;
            $item_desc    = $item["{$block_name}-item__desc"] ?? null;
            $item_image   = $item["{$block_name}-item__img"] ?? null; ?>

            <div class="<?= $block_name ?>-section-item <?= $block_name ?>-section-item--<?= $key % 2 === 1 ? 'odd' : 'even' ?>">
                <div class="container">
                    <div class="<?= $block_name ?>-section-item-wrapper">
                        <?php
                        if ($item_label || $item_heading || $item_desc) : ?>
                            <div class="<?= $block_name ?>-section-item-content">
                                <?php
                                if ($item_label) : ?>
                                    <span class="<?= $block_name ?>-section-item__label">
                                        <?= wp_kses_post($item_label) ?>
                                    </span>
                                <?php
                                endif;

                                if ($item_heading) : ?>
                                    <h3 class="<?= $block_name ?>-section-item__heading">
                                        <?= wp_kses_post($item_heading) ?>
                                    </h3>
                                <?php
                                endif;

                                if ($item_desc) : ?>
                                    <p class="<?= $block_name ?>-section-item__desc">
                                        <?= wp_kses_post($item_desc) ?>
                                    </p>
                                <?php
                                endif; ?>
                            </div>
                        <?php
                        endif;

                        if ($item_image) : ?>
                            <div class="<?= $block_name ?>-section-item__img">
                                <img class=""
                                     src="<?= $item_image['url'] ?>"
                                     alt="<?= $item_image['title'] ?>"
                                     width="<?= $item_image['width'] ?>"
                                     height="<?= $item_image['height'] ?>"/>
                            </div>
                        <?php
                        endif; ?>
                    </div>
                </div>
            </div>
        <?php
        endforeach; ?>

        <?php
        if ($cta) : ?>
            <a class="<?= $block_name ?>-section__cta btn btn--big btn--orange btn--has-svg"
               href="<?= $cta['url'] ?>"
               target="<?= $cta['target'] ?>">
                <span class="inside">
                    <span><?= $cta['title'] ?></span>

                    <?php sprite_svg('icon-arrow-right', '17', '17') ?>
                </span>
            </a>
        <?php
        endif; ?>
    </section>
<?php
endif; ?>
