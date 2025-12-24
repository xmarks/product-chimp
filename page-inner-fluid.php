<?php
/**
 * Template Name: Inner Pages [Fluid]
 * The template for pages that do nto have a Hero Block
 *
 * @package Product_Chimp
 */

get_header();

$cy_template_key = 'cf-page-inner-fluid';
$fields = get_field($cy_template_key);
$custom_union = $fields[$cy_template_key . '__union-img'] ?? null;  ?>

<?php // Custom styles to replace Union Image
if( $custom_union ) : ?>
<style type="text/css">
    .union::before {
        background-image: url("<?= $custom_union['url'] ?>");
    }
</style>
<?php
endif; ?>


    <main id="primary" class="site-main">
        <div class="container">
            <div class="union"></div>
        </div>

        <?php
        while (have_posts()) :
            the_post();

            get_template_part('template-parts/content', 'page-inner-fluid');

            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;

        endwhile; // End of the loop.
        ?>
    </main><!-- #main -->

<?php
get_footer();
