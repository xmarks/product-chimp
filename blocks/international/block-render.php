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
        $fields       = get_field($block_name);
        $heading      = $fields["{$block_name}__heading"] ?? null;
        $subtitle     = $fields["{$block_name}__subtitle"] ?? null;
        $marketplaces = $fields["{$block_name}-marketplaces"] ?? null;
        $bottom_text  = $fields["{$block_name}__bottom_text"] ?? null;
        ?>
        <div class="container">
            <div class="<?= $block_name ?>-section-wrapper">

                <?php if ($heading) : ?>
                    <h2 class="<?= $block_name ?>-section__heading">
                        <?= $heading ?>
                    </h2>
                <?php endif; ?>

                <?php if ($subtitle) : ?>
                    <p class="<?= $block_name ?>-section__subtitle">
                        <?= $subtitle ?>
                    </p>
                <?php endif; ?>

                <?php if ($marketplaces) : ?>
                    <div class="<?= $block_name ?>-section-marketplaces">
                        <?php foreach ($marketplaces as $key => $item) :
                            $flag  = $item["{$block_name}-marketplace__flag"] ?? null;
                            $label = $item["{$block_name}-marketplace__label"] ?? null;
                            ?>
                            <div class="<?= $block_name ?>-section-marketplace">
                                <?php if ($flag) : ?>
                                    <div class="<?= $block_name ?>-section-marketplace__flag">
                                        <img src="<?= $flag['url'] ?>"
                                             alt="<?= $flag['alt'] ?: $flag['title'] ?>"
                                             width="<?= $flag['width'] ?>"
                                             height="<?= $flag['height'] ?>"/>
                                    </div>
                                <?php endif; ?>

                                <?php if ($label) : ?>
                                    <span class="<?= $block_name ?>-section-marketplace__label">
                                        <?= $label ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($bottom_text) : ?>
                    <p class="<?= $block_name ?>-section__bottom-text">
                        <?= $bottom_text ?>
                    </p>
                <?php endif; ?>

            </div>
        </div>
    </section>
<?php
endif; ?>
