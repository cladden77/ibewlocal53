<?php
/**
 * News Archive Template
 *
 * @package IBEW_Local_53
 */

get_header();

$current_category = isset($_GET['news_category']) ? sanitize_text_field($_GET['news_category']) : '';
$search_query = get_search_query();
?>

<!-- News Archive Hero -->
<section class="archive-hero">
    <div class="hero-card gradient-hero">
        <div class="hero-pill">NEWS & UPDATES</div>
        <h1 class="hero-title">
            Latest from <span class="gold-text">Local 53</span>
        </h1>
        <p class="hero-subtext">Stay informed about union news, contract updates, and member announcements.</p>
    </div>
</section>

<div class="archive-container">
    <div class="archive-layout">
        <!-- Left Sidebar -->
        <aside class="archive-sidebar">
            <!-- Search Card -->
            <div class="sidebar-card">
                <h3 class="sidebar-card-title">Search News</h3>
                <form method="get" action="<?php echo home_url('/news'); ?>" class="search-form">
                    <input type="search" name="s" value="<?php echo esc_attr($search_query); ?>" placeholder="Search news..." class="search-input" />
                    <button type="submit" class="search-submit">Search</button>
                </form>
            </div>
            
            <!-- Categories Card -->
            <div class="sidebar-card">
                <h3 class="sidebar-card-title">Categories</h3>
                <ul class="category-list">
                    <li>
                        <a href="<?php echo home_url('/news'); ?>" class="<?php echo empty($current_category) ? 'active' : ''; ?>">
                            All News <span class="count">(<?php echo wp_count_posts('news')->publish; ?>)</span>
                        </a>
                    </li>
                    <?php
                    $categories = get_terms(array(
                        'taxonomy' => 'news_category',
                        'hide_empty' => true,
                    ));
                    
                    foreach ($categories as $category) :
                        $is_active = $current_category === $category->slug;
                        $category_url = add_query_arg('news_category', $category->slug, home_url('/news'));
                    ?>
                        <li>
                            <a href="<?php echo esc_url($category_url); ?>" class="<?php echo $is_active ? 'active' : ''; ?>">
                                <?php echo esc_html($category->name); ?>
                                <span class="count">(<?php echo $category->count; ?>)</span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <!-- Resources Promo Card -->
            <div class="sidebar-card promo-card">
                <h3 class="sidebar-card-title">Resources</h3>
                <p class="promo-text">Access our document library and member resources.</p>
                <a href="#" class="btn btn-primary btn-small">Visit Document Library →</a>
            </div>
        </aside>
        
        <!-- Right Content -->
        <div class="archive-content">
            <!-- Featured Story -->
            <?php
            $featured_query = new WP_Query(array(
                'post_type' => 'news',
                'posts_per_page' => 1,
                'orderby' => 'date',
                'order' => 'DESC',
            ));
            
            $featured_post_id = 0;
            if ($featured_query->have_posts()) :
                $featured_query->the_post();
                $featured_post_id = get_the_ID();
                $featured_categories = get_the_terms($featured_post_id, 'news_category');
                $featured_category = !empty($featured_categories) ? $featured_categories[0]->name : '';
            ?>
                <div class="featured-story-section">
                    <div class="section-label">Featured Story</div>
                    <article class="featured-story-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="featured-story-image">
                                <?php the_post_thumbnail('large'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="featured-story-content">
                            <?php if ($featured_category) : ?>
                                <span class="featured-badge badge-red"><?php echo esc_html(strtoupper($featured_category)); ?></span>
                            <?php endif; ?>
                            <p class="featured-date"><?php echo get_the_date('F j, Y'); ?></p>
                            <h2 class="featured-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <p class="featured-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 30); ?></p>
                            <a href="<?php the_permalink(); ?>" class="featured-link">Read Full Story →</a>
                        </div>
                    </article>
                </div>
            <?php
                wp_reset_postdata();
            endif;
            ?>
            
            <!-- News Grid -->
            <div class="news-archive-grid">
                <?php
                $paged = get_query_var('paged') ? get_query_var('paged') : 1;
                
                $args = array(
                    'post_type' => 'news',
                    'posts_per_page' => 9,
                    'paged' => $paged,
                    'orderby' => 'date',
                    'order' => 'DESC',
                );
                
                if (!empty($current_category)) {
                    $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'news_category',
                            'field' => 'slug',
                            'terms' => $current_category,
                        ),
                    );
                }
                
                if (!empty($search_query)) {
                    $args['s'] = $search_query;
                }
                
                // Exclude featured post
                if ($featured_post_id > 0) {
                    $args['post__not_in'] = array($featured_post_id);
                }
                
                $news_query = new WP_Query($args);
                
                if ($news_query->have_posts()) :
                    while ($news_query->have_posts()) : $news_query->the_post();
                        $categories = get_the_terms(get_the_ID(), 'news_category');
                        $category_name = !empty($categories) ? $categories[0]->name : '';
                ?>
                    <article class="news-archive-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="news-archive-image">
                                <?php the_post_thumbnail('medium'); ?>
                                <?php if ($category_name) : ?>
                                    <span class="news-badge"><?php echo esc_html($category_name); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <div class="news-archive-content">
                            <p class="news-date"><?php echo get_the_date('F j, Y'); ?></p>
                            <h3 class="news-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p class="news-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                            <a href="<?php the_permalink(); ?>" class="news-link">Read Story →</a>
                        </div>
                    </article>
                <?php
                    endwhile;
                else :
                ?>
                    <p class="no-posts">No news items found.</p>
                <?php endif; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($news_query->max_num_pages > 1) : ?>
                <div class="pagination">
                    <?php
                    $prev_link = get_previous_posts_link('Previous');
                    $next_link = get_next_posts_link('Next', $news_query->max_num_pages);
                    ?>
                    <div class="pagination-nav">
                        <?php if ($prev_link) : ?>
                            <div class="pagination-prev"><?php echo $prev_link; ?></div>
                        <?php endif; ?>
                        
                        <div class="pagination-numbers">
                            <?php
                            echo paginate_links(array(
                                'total' => $news_query->max_num_pages,
                                'current' => $paged,
                                'prev_next' => false,
                                'type' => 'list',
                                'before_page_number' => '<span class="page-number">',
                                'after_page_number' => '</span>',
                            ));
                            ?>
                        </div>
                        
                        <?php if ($next_link) : ?>
                            <div class="pagination-next"><?php echo $next_link; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php wp_reset_postdata(); ?>
        </div>
    </div>
</div>

<?php
get_footer();

