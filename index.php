<?php
/**
 * Main Index Template (Fallback)
 *
 * @package IBEW_Local_53
 */

get_header();
?>

<?php if (have_posts()) : ?>
    <div class="archive-container">
        <div class="archive-content">
            <?php while (have_posts()) : the_post(); ?>
                <article class="post-item">
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <p class="post-date"><?php echo get_the_date(); ?></p>
                    <div class="post-excerpt">
                        <?php the_excerpt(); ?>
                    </div>
                    <a href="<?php the_permalink(); ?>" class="read-more">Read More →</a>
                </article>
            <?php endwhile; ?>
        </div>
    </div>
<?php else : ?>
    <div class="archive-container">
        <p class="no-posts">No posts found.</p>
    </div>
<?php endif; ?>

<?php
get_footer();

