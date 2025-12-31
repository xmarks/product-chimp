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
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php
endif; ?>
