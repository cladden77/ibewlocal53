<?php
/**
 * Single News Template
 *
 * @package IBEW_Local_53
 */

get_header();
?>

<?php while (have_posts()) : the_post(); ?>
    <article class="single-news">
        <div class="single-container">
            <div class="single-header">
                <?php
                $categories = get_the_terms(get_the_ID(), 'news_category');
                $category_name = !empty($categories) ? $categories[0]->name : '';
                ?>
                <?php if ($category_name) : ?>
                    <span class="news-badge"><?php echo esc_html($category_name); ?></span>
                <?php endif; ?>
                <p class="single-date"><?php echo get_the_date('F j, Y'); ?></p>
                <h1 class="single-title"><?php the_title(); ?></h1>
            </div>
            
            <?php if (has_post_thumbnail()) : ?>
                <div class="single-featured-image">
                    <?php the_post_thumbnail('featured-16-12'); ?>
                </div>
            <?php endif; ?>
            
            <div class="single-content">
                <?php the_content(); ?>
            </div>
            
            <div class="single-footer">
                <a href="<?php echo home_url('/news'); ?>" class="btn btn-tertiary">← Back to News</a>
            </div>
        </div>
    </article>
<?php endwhile; ?>

<?php
get_footer();

