<?php
/**
 * Default Page Template
 *
 * @package IBEW_Local_53
 */

get_header();
?>

<?php while (have_posts()) : the_post(); ?>
    <article class="page-content">
        <div class="page-container">
            <h1 class="page-title"><?php the_title(); ?></h1>
            <div class="page-content-wrapper">
                <?php the_content(); ?>
            </div>
        </div>
    </article>
<?php endwhile; ?>

<?php
get_footer();

