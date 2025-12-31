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

$block_name = str_replace('pc/', '', $block[ 'name' ]);
$id = $block_name . '-section-' . $block[ 'id' ];

wp_enqueue_style('block-' . $block_name);

if (!empty($block[ 'anchor' ])) {
    $id = $block[ 'anchor' ];
}

$classes = "{$block_name}-section builder-section";
if (!empty($block[ 'className' ])) {
    $classes .= ' ' . $block[ 'className' ];
}
if (!empty($block[ 'align' ])) {
    $classes .= ' align' . $block[ 'align' ];
}

$wrapper_attributes = get_block_wrapper_attributes([
        'class' => $classes
]);

if (isset($block[ 'data' ][ 'preview_image_help' ])) :
    $fileUrl = str_replace(get_stylesheet_directory(), '', dirname(__FILE__)); ?>
    <img src="<?= get_stylesheet_directory_uri()
    . $fileUrl . '/'
    . $block[ 'data' ][ 'preview_image_help' ]
    ?>" style="width:100%; height:auto;"/>
<?php
else: ?>
    <section id="<?= esc_attr($id); ?>" <?= $wrapper_attributes; ?>>
        <?php
        $fields = get_field($block_name);
        $heading = $fields[ "{$block_name}__heading" ] ?? null;
        $subheading = $fields[ "{$block_name}__subheading" ] ?? null;
        $toggle_annual = $fields[ "{$block_name}__toggle_annual" ] ?? 'Annual (Save 20%)';
        $toggle_monthly = $fields[ "{$block_name}__toggle_monthly" ] ?? 'Monthly';
        $plans = $fields[ "{$block_name}-plans" ] ?? [];
        $disclaimer = $fields[ "{$block_name}__disclaimer" ] ?? null;
        ?>
        <div class="container">
            <div class="<?= $block_name ?>-section-wrapper">

                <?php if ($heading) : ?>
                    <h2 class="<?= $block_name ?>-section__heading">
                        <?= wp_kses_post($heading) ?>
                    </h2>
                <?php endif;

                if ($subheading) : ?>
                    <p class="<?= $block_name ?>-section__subheading">
                        <?= wp_kses_post($subheading) ?>
                    </p>
                <?php endif; ?>

                <div class="<?= $block_name ?>-section__toggle">
                    <button type="button"
                            class="<?= $block_name ?>-section__toggle-btn <?= $block_name ?>-section__toggle-btn--annual active"
                            data-billing="annual">
                        <?= esc_html($toggle_annual) ?>
                    </button>
                    <span class="<?= $block_name ?>-section__toggle-divider">|</span>
                    <button type="button"
                            class="<?= $block_name ?>-section__toggle-btn <?= $block_name ?>-section__toggle-btn--monthly"
                            data-billing="monthly">
                        <?= esc_html($toggle_monthly) ?>
                    </button>
                </div>

                <?php if (!empty($plans)) :
                    $plan_names = array_column($plans, "{$block_name}-plan__name");
                    ?>
                    <div class="<?= $block_name ?>-section-grid">
                        <?php foreach ($plans as $key => $plan) :
                            $plan_name = $plan[ "{$block_name}-plan__name" ] ?? null;
                            $is_popular = $plan[ "{$block_name}-plan__is_popular" ] ?? false;
                            $popular_text = $plan[ "{$block_name}-plan__popular_text" ] ?? 'Most Popular';
                            $price_annual = $plan[ "{$block_name}-plan__price_annual" ] ?? null;
                            $price_monthly = $plan[ "{$block_name}-plan__price_monthly" ] ?? null;
                            $savings = null;
                            $billing_annual = null;

                            if ($price_annual && $price_monthly) {
                                $annual_num = (float)preg_replace('/[^0-9.]/', '', $price_annual);
                                $monthly_num = (float)preg_replace('/[^0-9.]/', '', $price_monthly);
                                if ($annual_num > 0) {
                                    $yearly_total = $annual_num * 12;
                                    $billing_annual = '$' . number_format($yearly_total, 0) . ' billed annually';
                                }
                                if ($monthly_num > 0 && $monthly_num > $annual_num) {
                                    $yearly_if_monthly = $monthly_num * 12;
                                    $saved = $yearly_if_monthly - $yearly_total;
                                    if ($saved > 0) {
                                        $savings = 'Save $' . number_format($saved, 0);
                                    }
                                }
                            }
                            $cta = $plan[ "{$block_name}-plan__cta" ] ?? null;
                            $trial_text = $plan[ "{$block_name}-plan__trial_text" ] ?? null;
                            $usage_title = $plan[ "{$block_name}-plan__usage_title" ] ?? null;
                            $usage_items = $plan[ "{$block_name}-plan__usage_items" ] ?? [];
                            $features_title = $plan[ "{$block_name}-plan__features_title" ] ?? null;
                            $features = $plan[ "{$block_name}-plan__features" ] ?? [];

                            $prev_plan_name = $key > 0 ? ($plan_names[ $key - 1 ] ?? null) : null;
                            if (!$features_title && $prev_plan_name) {
                                $features_title = "Everything in {$prev_plan_name}, plus:";
                            }
                            ?>

                            <div class="<?= $block_name ?>-section-card<?= $is_popular ? ' ' . $block_name . '-section-card--popular' : '' ?>">
                                <?php if ($is_popular) : ?>
                                    <div class="<?= $block_name ?>-section-card__badge">
                                        <?= esc_html($popular_text) ?>
                                    </div>
                                <?php endif; ?>

                                <div class="<?= $block_name ?>-section-card__inner">
                                    <?php if ($plan_name) : ?>
                                        <h3 class="<?= $block_name ?>-section-card__name">
                                            <?= esc_html($plan_name) ?>
                                        </h3>
                                    <?php endif; ?>

                                    <div class="<?= $block_name ?>-section-card__price-wrap">
                                        <span class="<?= $block_name ?>-section-card__price <?= $block_name ?>-section-card__price--annual">
                                            <?= esc_html($price_annual) ?>
                                        </span>
                                        <span class="<?= $block_name ?>-section-card__price <?= $block_name ?>-section-card__price--monthly"
                                              style="display: none;">
                                            <?= esc_html($price_monthly) ?>
                                        </span>
                                        <span class="<?= $block_name ?>-section-card__price-original">
                                            <?= esc_html($price_monthly) ?>
                                        </span>
                                        <span class="<?= $block_name ?>-section-card__price-suffix">/mo</span>
                                    </div>

                                    <div class="<?= $block_name ?>-section-card__billing">
                                        <span class="<?= $block_name ?>-section-card__billing-text <?= $block_name ?>-section-card__billing-text--annual">
                                            <?= esc_html($billing_annual) ?>
                                        </span>
                                        <?php if ($savings) : ?>
                                            <span class="<?= $block_name ?>-section-card__savings">
                                                <?= esc_html($savings) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($cta) : ?>
                                        <a class="<?= $block_name ?>-section-card__cta<?= $is_popular ? ' ' . $block_name . '-section-card__cta--primary' : '' ?>"
                                           href="<?= esc_url($cta[ 'url' ]) ?>"
                                           target="<?= esc_attr($cta[ 'target' ] ?: '_self') ?>">
                                            <?= esc_html($cta[ 'title' ]) ?>
                                        </a>
                                    <?php endif;

                                    if ($trial_text) : ?>
                                        <p class="<?= $block_name ?>-section-card__trial">
                                            <?= esc_html($trial_text) ?>
                                        </p>
                                    <?php endif;

                                    if ($usage_title || !empty($usage_items)) : ?>
                                        <div class="<?= $block_name ?>-section-card__usage">
                                            <?php if ($usage_title) : ?>
                                                <h4 class="<?= $block_name ?>-section-card__usage-title">
                                                    <?= esc_html($usage_title) ?>
                                                </h4>
                                            <?php endif;

                                            if (!empty($usage_items)) : ?>
                                                <ul class="<?= $block_name ?>-section-card__usage-list">
                                                    <?php foreach ($usage_items as $usage_item) :
                                                        $usage_label = $usage_item[ 'usage_item__label' ] ?? null;
                                                        $usage_value = $usage_item[ 'usage_item__value' ] ?? null;
                                                        ?>
                                                        <li class="<?= $block_name ?>-section-card__usage-item">
                                                            <span class="<?= $block_name ?>-section-card__usage-label"><?= esc_html($usage_label) ?></span>
                                                            <span class="<?= $block_name ?>-section-card__usage-value"><?= wp_kses_post($usage_value) ?></span>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif;

                                    if ($features_title || !empty($features)) : ?>
                                        <div class="<?= $block_name ?>-section-card__features">
                                            <?php if ($features_title) : ?>
                                                <h4 class="<?= $block_name ?>-section-card__features-title">
                                                    <?= esc_html($features_title) ?>
                                                </h4>
                                            <?php endif;

                                            if (!empty($features)) : ?>
                                                <ul class="<?= $block_name ?>-section-card__features-list">
                                                    <?php foreach ($features as $feature) :
                                                        $feature_label = $feature[ 'feature__label' ] ?? null;
                                                        $feature_included = $feature[ 'feature__included' ] ?? true;
                                                        $feature_has_info = $feature[ 'feature__has_info' ] ?? false;
                                                        $feature_info = $feature[ 'feature__info' ] ?? null;
                                                        ?>
                                                        <li class="<?= $block_name ?>-section-card__feature<?= !$feature_included ? ' ' . $block_name . '-section-card__feature--excluded' : '' ?>">
                                                            <span class="<?= $block_name ?>-section-card__feature-icon">
                                                                <?php if ($feature_included) : ?>
                                                                    <svg width="16" height="16" viewBox="0 0 16 16"
                                                                         fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M13.3 4.3L6 11.6L2.7 8.3L3.4 7.6L6 10.2L12.6 3.6L13.3 4.3Z"
                                                                              fill="#7FB859"/>
                                                                    </svg>
                                                                <?php else : ?>
                                                                    <svg width="16" height="16" viewBox="0 0 16 16"
                                                                         fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M12 4.7L11.3 4L8 7.3L4.7 4L4 4.7L7.3 8L4 11.3L4.7 12L8 8.7L11.3 12L12 11.3L8.7 8L12 4.7Z"
                                                                              fill="#B0B0B0"/>
                                                                    </svg>
                                                                <?php endif; ?>
                                                            </span>
                                                            <span class="<?= $block_name ?>-section-card__feature-label">
                                                                <?= esc_html($feature_label) ?>
                                                            </span>
                                                            <?php if ($feature_has_info && $feature_info) : ?>
                                                                <span class="<?= $block_name ?>-section-card__feature-info"
                                                                      data-tooltip="<?= esc_attr($feature_info) ?>">
                                                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <g clip-path="url(#clip0_2212_1172)">
                                                                            <path d="M10 0C8.02219 0 6.08879 0.58649 4.4443 1.6853C2.79981 2.78412 1.51809 4.3459 0.761209 6.17317C0.00433286 8.00043 -0.193701 10.0111 0.192152 11.9509C0.578004 13.8907 1.53041 15.6725 2.92894 17.0711C4.32746 18.4696 6.10929 19.422 8.0491 19.8079C9.98891 20.1937 11.9996 19.9957 13.8268 19.2388C15.6541 18.4819 17.2159 17.2002 18.3147 15.5557C19.4135 13.9112 20 11.9778 20 10C19.9971 7.34872 18.9426 4.80684 17.0679 2.9321C15.1932 1.05736 12.6513 0.00286757 10 0ZM10 18.3333C8.35183 18.3333 6.74066 17.8446 5.37025 16.9289C3.99984 16.0132 2.93174 14.7117 2.30101 13.189C1.67028 11.6663 1.50525 9.99076 1.82679 8.37425C2.14834 6.75774 2.94201 5.27288 4.10745 4.10744C5.27289 2.94201 6.75774 2.14833 8.37425 1.82679C9.99076 1.50525 11.6663 1.67027 13.189 2.301C14.7118 2.93173 16.0132 3.99984 16.9289 5.37025C17.8446 6.74066 18.3333 8.35182 18.3333 10C18.3309 12.2094 17.4522 14.3276 15.8899 15.8899C14.3276 17.4522 12.2094 18.3309 10 18.3333Z"
                                                                              fill="#BBBBBB"/>
                                                                            <path d="M10.0026 8.33301H9.16927C8.94826 8.33301 8.7363 8.42081 8.58002 8.57709C8.42374 8.73337 8.33594 8.94533 8.33594 9.16634C8.33594 9.38735 8.42374 9.59932 8.58002 9.7556C8.7363 9.91188 8.94826 9.99967 9.16927 9.99967H10.0026V14.9997C10.0026 15.2207 10.0904 15.4327 10.2467 15.5889C10.403 15.7452 10.6149 15.833 10.8359 15.833C11.057 15.833 11.2689 15.7452 11.4252 15.5889C11.5815 15.4327 11.6693 15.2207 11.6693 14.9997V9.99967C11.6693 9.55765 11.4937 9.13372 11.1811 8.82116C10.8686 8.5086 10.4446 8.33301 10.0026 8.33301Z"
                                                                              fill="#BBBBBB"/>
                                                                            <path d="M10 6.66699C10.6904 6.66699 11.25 6.10735 11.25 5.41699C11.25 4.72664 10.6904 4.16699 10 4.16699C9.30964 4.16699 8.75 4.72664 8.75 5.41699C8.75 6.10735 9.30964 6.66699 10 6.66699Z"
                                                                              fill="#BBBBBB"/>
                                                                        </g>
                                                                        <defs>
                                                                            <clipPath id="clip0_2212_1172">
                                                                                <rect width="20" height="20" fill="white"/>
                                                                            </clipPath>
                                                                        </defs>
                                                                    </svg>
                                                                </span>
                                                            <?php endif; ?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif;

                if ($disclaimer) : ?>
                    <p class="<?= $block_name ?>-section__disclaimer">
                        <?= wp_kses_post($disclaimer) ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <script id="scripts-<?= esc_attr($id); ?>" type="text/javascript">
			document.addEventListener('DOMContentLoaded', function () {
				const section = document.getElementById('<?= esc_js($id); ?>');
				if (!section) return;

				const toggleBtns = section.querySelectorAll('.<?= $block_name ?>-section__toggle-btn');
				const annualPrices = section.querySelectorAll('.<?= $block_name ?>-section-card__price--annual');
				const monthlyPrices = section.querySelectorAll('.<?= $block_name ?>-section-card__price--monthly');
				const originalPrices = section.querySelectorAll('.<?= $block_name ?>-section-card__price-original');
				const billingTexts = section.querySelectorAll('.<?= $block_name ?>-section-card__billing');

				toggleBtns.forEach(btn => {
					btn.addEventListener('click', function () {
						const billing = this.dataset.billing;

						toggleBtns.forEach(b => b.classList.remove('active'));
						this.classList.add('active');

						if (billing === 'annual') {
							annualPrices.forEach(el => el.style.display = '');
							monthlyPrices.forEach(el => el.style.display = 'none');
							originalPrices.forEach(el => el.style.display = '');
							billingTexts.forEach(el => el.style.display = '');
						} else {
							annualPrices.forEach(el => el.style.display = 'none');
							monthlyPrices.forEach(el => el.style.display = '');
							originalPrices.forEach(el => el.style.display = 'none');
							billingTexts.forEach(el => el.style.display = 'none');
						}
					});
				});
			});
    </script>
<?php
endif; ?>
