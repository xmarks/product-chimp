<?php
/**
 * Template Name: Inner Pages
 * The template for inner pages - cookie, privacy, terms, etc.
 *
 * @package Product_Chimp
 */

get_header();
?>

    <main id="primary" class="site-main">
        <div class="container">
            <div class="union"></div>

            <?php
            while (have_posts()) :
                the_post();

                get_template_part('template-parts/content', 'page-inner');

                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;

            endwhile; // End of the loop.
            ?>
        </div>
    </main><!-- #main -->

<?php
get_footer();
