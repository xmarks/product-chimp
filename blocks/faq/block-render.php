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
        $fields  = get_field($block_name);
        $heading = $fields["{$block_name}__heading"] ?? null;
        $items   = $fields["{$block_name}-items"] ?? [];
        $cta     = $fields["{$block_name}__cta"] ?? null; ?>
        <div class="container">
            <div class="<?= $block_name ?>-section-wrapper">

                <div class="<?= $block_name ?>-section-content">
                    <?php
                    if ($heading) : ?>
                        <h2 class="<?= $block_name ?>-section__heading">
                            <?= $heading ?>
                        </h2>
                    <?php
                    endif;

                    if (!empty($items)) : ?>
                        <div class="<?= $block_name ?>-section-grid">
                            <?php
                            foreach ($items as $key => $item) :
                                $question = $item["{$block_name}-item__question"] ?? null;
                                $answer = $item["{$block_name}-item__answer"] ?? null; ?>

                                <div class="<?= $block_name ?>-section-grid-item">
                                    <?php
                                    if ($question) : ?>
                                        <input id="faqAccordionController-<?= $key ?>"
                                               class="<?= $block_name ?>-section-grid-item__controller"
                                               type="checkbox" name="checkbox-faq"
                                            <?= !$is_preview && $key === 0 ? 'checked' : '' ?>/>
                                        <label for="faqAccordionController-<?= $key ?>"
                                               class="<?= $block_name ?>-section-grid-item__question <?= !$is_preview && $key === 0 ? 'close' : '' ?>">
                                            <span class="<?= $block_name ?>-section-grid-item__question-text <?= !$is_preview && $key === 0 ? 'close' : '' ?>">
                                                <?= wp_kses_post($question) ?>
                                            </span>

                                            <span class="<?= $block_name ?>-section-grid-item__icon">
                                                <?php sprite_svg('icon-faq-closed', 36, 36) ?>

                                                <?php sprite_svg('icon-faq-open', 36, 36) ?>
                                            </span>
                                        </label>
                                    <?php
                                    endif ?>

                                    <?php
                                    if ($answer) : ?>
                                        <div class="<?= $block_name ?>-section-grid-item__answer">
                                            <div>
                                                <?= wp_kses_post($answer) ?>
                                            </div>
                                        </div>
                                    <?php
                                    endif; ?>
                                </div>
                            <?php
                            endforeach; ?>
                        </div>
                    <?php
                    endif;

                    if ($cta) : ?>
                        <a class="<?= $block_name ?>-section__cta btn btn--big btn--orange btn--has-svg"
                           href="<?= $cta['url'] ?>"
                           target="<?= $cta['target'] ?>">
                            <span class="inside">
                                <span><?= $cta['title'] ?></span>

                            <?= sprite_svg('icon-arrow-right', 17, 17, TRUE) ?>
                            </span>
                        </a>
                    <?php
                    endif; ?>
                </div>
            </div>
        </div>
    </section>

    <script id="scripts-<?= esc_attr($id); ?>" type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            // Select all FAQ checkboxes
            const checkboxes = document.querySelectorAll('input[name="checkbox-faq"]');

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    if (this.checked) {
                        // Label Under Checkbox
                        this.nextElementSibling.classList.add('close');
                        // First Span inside Label
                        this.nextElementSibling.querySelector("span").classList.add('close');

                        // Uncheck all other checkboxes
                        checkboxes.forEach(other => {
                            if (other !== this) {
                                other.checked = false;

                                // Label Under Checkbox
                                other.nextElementSibling.classList.remove('close');

                                // First Span inside Label
                                other.nextElementSibling.querySelector("span").classList.remove('close');
                            }
                        });
                    } else {
                        if(this.nextElementSibling.classList.contains('close')) {
                            // Label Under Checkbox
                            this.nextElementSibling.classList.remove('close');

                            // First Span inside Label
                            this.nextElementSibling.querySelector("span").classList.remove('close');
                        }
                    }
                });
            });
        });
    </script>
<?php
endif; ?>
