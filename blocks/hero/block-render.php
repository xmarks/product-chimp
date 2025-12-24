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

?>

<?php
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
        $fields = get_field($block_name);

        $field_heading = $fields["{$block_name}__heading"] ?? null;
        $field_tagline = $fields["{$block_name}__tagline"] ?? null;
        $field_cta     = $fields["{$block_name}__cta"] ?? null;
        $img_desktop   = $fields["{$block_name}__img_desktop"] ?? null;
        $img_mobile    = $fields["{$block_name}__img_mobile"] ?? null; ?>
        <div class="container">
            <div class="<?= $block_name ?>-section-wrapper">

                <div class="<?= $block_name ?>-section-content enter-view">
                    <?php
                    if ($field_heading) : ?>
                        <h1 class="<?= $block_name ?>-section__heading">
                            <?= $field_heading ?>
                        </h1>
                    <?php
                    endif; ?>

                    <?php
                    if ($field_tagline) : ?>
                        <h2 class="<?= $block_name ?>-section__tagline">
                            <?= $field_tagline ?>
                        </h2>
                    <?php
                    endif; ?>

                    <?php
                    if ($field_cta) : ?>
                        <a class="<?= $block_name ?>-section__cta btn btn--big btn--has-svg"
                           href="<?= $field_cta['url'] ?>"
                           target="<?= $field_cta['target'] ?>">
                            <span class="inside">
                                <span><?= $field_cta['title'] ?></span>

                            <?php sprite_svg('icon-arrow-right', 17, 17 ) ?>
                            </span>
                        </a>
                    <?php
                    endif; ?>

                    <?php
                    if ($img_desktop) : ?>
                        <img class="<?= $block_name ?>-section__img <?= $block_name ?>-section__img--desktop animate__animated opacity-0"
                             data-animate-class="animate__fadeIn"
                             style="animation-delay: 200ms"
                             src="<?= $img_desktop['url'] ?>"
                             alt="<?= $img_desktop['title'] ?>"
                             width="<?= $img_desktop['width'] ?>"
                             height="<?= $img_desktop['height'] ?>"/>
                    <?php
                    endif; ?>

                    <?php
                    if ($img_mobile) : ?>
                        <img class="<?= $block_name ?>-section__img <?= $block_name ?>-section__img--mobile animate__animated opacity-0"
                             data-animate-class="animate__fadeIn"
                             style="animation-delay: 200ms"
                             src="<?= $img_mobile['url'] ?>"
                             alt="<?= $img_mobile['title'] ?>"
                             width="<?= $img_mobile['width'] ?>"
                             height="<?= $img_mobile['height'] ?>"/>
                    <?php
                    endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php
endif; ?>
