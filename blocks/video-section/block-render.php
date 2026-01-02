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

if (!function_exists('pc_extract_vimeo_id')) {
    function pc_extract_vimeo_id($url) {
        $patterns = [
            '/vimeo\.com\/(\d+)/',
            '/vimeo\.com\/video\/(\d+)/',
            '/player\.vimeo\.com\/video\/(\d+)/'
        ];
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
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
        <?php
        $fields      = get_field($block_name);
        $thumbnail   = $fields["{$block_name}__thumbnail"] ?? null;
        $video_url   = $fields["{$block_name}__video_url"] ?? null;
        $button_text = $fields["{$block_name}__button_text"] ?? null;
        $vimeo_id    = $video_url ? pc_extract_vimeo_id($video_url) : null;

        if ($vimeo_id) {
            wp_enqueue_script('vimeo-player', 'https://player.vimeo.com/api/player.js', [], null, true);
        }
        ?>
        <div class="container">
            <div class="<?= $block_name ?>-section-wrapper">
                <div class="<?= $block_name ?>-section__video-container">

                    <?php if ($vimeo_id) : ?>
                        <iframe class="<?= $block_name ?>-section__iframe"
                                src="https://player.vimeo.com/video/<?= esc_attr($vimeo_id) ?>?title=0&byline=0&portrait=0"
                                frameborder="0"
                                allow="autoplay; fullscreen; picture-in-picture"
                                allowfullscreen></iframe>
                    <?php endif; ?>

                    <div class="<?= $block_name ?>-section__overlay">
                        <?php if ($thumbnail) : ?>
                            <img class="<?= $block_name ?>-section__thumbnail"
                                 src="<?= esc_url($thumbnail['url']) ?>"
                                 alt="<?= esc_attr($thumbnail['alt'] ?: $thumbnail['title']) ?>"
                                 width="<?= esc_attr($thumbnail['width']) ?>"
                                 height="<?= esc_attr($thumbnail['height']) ?>"/>
                        <?php endif; ?>

                        <?php if ($vimeo_id) : ?>
                            <button type="button" class="<?= $block_name ?>-section__play-button">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5 4.99001C5 4.01989 5 3.53484 5.20249 3.26386C5.37889 3.02861 5.64852 2.87999 5.94036 2.85574C6.27572 2.82818 6.67756 3.0762 7.48126 3.57222L18.0684 10.0833C18.7862 10.526 19.145 10.7474 19.2694 11.0325C19.3775 11.2819 19.3775 11.5626 19.2694 11.8119C19.145 12.0971 18.7862 12.3185 18.0684 12.7612L7.48126 19.2722C6.67756 19.7683 6.27572 20.0163 5.94036 19.9888C5.64852 19.9645 5.37889 19.8159 5.20249 19.5806C5 19.3096 5 18.8246 5 17.8545V4.99001Z" fill="currentColor"/>
                                </svg>
                                <?php if ($button_text) : ?>
                                    <span><?= esc_html($button_text) ?></span>
                                <?php endif; ?>
                            </button>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </section>
<?php
endif; ?>
