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
        $button  = $fields["{$block_name}__button"] ?? null;
        $text    = $fields["{$block_name}__text"] ?? null;
        ?>
        <div class="container">
            <div class="<?= $block_name ?>-section-wrapper">

                <?php if ($heading) : ?>
                    <h2 class="<?= $block_name ?>-section__heading">
                        <?= $heading ?>
                    </h2>
                <?php endif; ?>

                <?php if ($button) : ?>
                    <a class="<?= $block_name ?>-section__button"
                       href="<?= esc_url($button['url']) ?>"
                       target="<?= $button['target'] ?: '_self' ?>">
                        <span><?= esc_html($button['title']) ?></span>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.16667 10H15.8333M15.8333 10L10 4.16667M15.8333 10L10 15.8333" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                <?php endif; ?>

                <?php if ($text) : ?>
                    <p class="<?= $block_name ?>-section__text">
                        <?= $text ?>
                    </p>
                <?php endif; ?>

            </div>
        </div>
    </section>
<?php
endif; ?>
