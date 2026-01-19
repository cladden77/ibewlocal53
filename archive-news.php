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
    <div class="archive-hero-container">
        <div class="hero-pill">News & Updates</div>
        <h1 class="hero-title">Latest from Local 53</h1>
        <p class="hero-subtext">Staying informed is key to our solidarity. Find the latest announcements, contract updates, and community stories here.</p>
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
                    <div class="search-icon-wrapper">
                        <span class="material-icons">search</span>
                    </div>
                    <input type="search" name="s" value="<?php echo esc_attr($search_query); ?>" placeholder="Keywords..." class="search-input" />
                    <button type="submit" class="search-submit" aria-label="Search">Search</button>
                </form>
            </div>
            
            <!-- Categories Card -->
            <div class="sidebar-card">
                <h3 class="sidebar-card-title">Categories</h3>
                <ul class="category-list">
                    <li>
                        <a href="<?php echo home_url('/news'); ?>" class="category-link <?php echo empty($current_category) ? 'active' : ''; ?>">
                            <span class="category-name">All News</span>
                            <span class="category-count"><?php echo wp_count_posts('news')->publish; ?></span>
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
                            <a href="<?php echo esc_url($category_url); ?>" class="category-link <?php echo $is_active ? 'active' : ''; ?>">
                                <span class="category-name"><?php echo esc_html($category->name); ?></span>
                                <span class="category-count"><?php echo $category->count; ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <!-- Resources Promo Card -->
            <div class="sidebar-card promo-card">
                <h3 class="sidebar-card-title">Resources</h3>
                <p class="promo-text">Access the latest agreements, bylaws, and forms.</p>
                <a href="#" class="promo-link">Visit Document Library <span class="material-icons">arrow_forward</span></a>
            </div>
        </aside>
        
        <!-- Right Content -->
        <div class="archive-content" id="news-content">
            <?php
            // Get current page for featured story logic
            $current_news_page = isset($_GET['pg']) ? max(1, intval($_GET['pg'])) : 1;
            $featured_post_id = 0;
            
            // Only show featured story on page 1
            if ($current_news_page === 1) :
                $featured_query = new WP_Query(array(
                    'post_type' => 'news',
                    'posts_per_page' => 1,
                    'orderby' => 'date',
                    'order' => 'DESC',
                ));
                
                if ($featured_query->have_posts()) :
                    $featured_query->the_post();
                    $featured_post_id = get_the_ID();
                    $featured_categories = get_the_terms($featured_post_id, 'news_category');
                    $featured_category = !empty($featured_categories) ? $featured_categories[0]->name : '';
            ?>
                <!-- Featured Story -->
                <div class="featured-story-section">
                    <div class="section-label">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/featured-checkmark.svg" alt="Featured" class="featured-checkmark-icon">
                        <span>Featured Story</span>
                    </div>
                    <article class="featured-story-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="featured-story-image">
                                <?php the_post_thumbnail('large'); ?>
                                <?php if ($featured_category) : ?>
                                    <span class="featured-badge badge-red"><?php echo esc_html(strtoupper($featured_category)); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <div class="featured-story-content">
                            <div class="featured-date-wrapper">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/calendar-icon.svg" alt="Calendar" class="calendar-icon">
                                <span class="featured-date"><?php echo get_the_date('F j, Y'); ?></span>
                            </div>
                            <h2 class="featured-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <p class="featured-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 30); ?></p>
                            <a href="<?php the_permalink(); ?>" class="featured-link">Read Full Story <span class="material-icons">arrow_forward</span></a>
                        </div>
                    </article>
                </div>
            <?php
                    wp_reset_postdata();
                endif;
            endif;
            ?>
            
            <!-- News Grid -->
            <div class="news-archive-grid">
                <?php
                $paged = isset($_GET['pg']) ? max(1, intval($_GET['pg'])) : 1;
                
                $args = array(
                    'post_type' => 'news',
                    'posts_per_page' => 6,
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
                
                // Exclude featured post on page 1
                if ($featured_post_id > 0 && $paged === 1) {
                    $args['post__not_in'] = array($featured_post_id);
                }
                
                $news_query = new WP_Query($args);
                
                if ($news_query->have_posts()) :
                    while ($news_query->have_posts()) : $news_query->the_post();
                        $categories = get_the_terms(get_the_ID(), 'news_category');
                        $category_name = !empty($categories) ? $categories[0]->name : '';
                        // Determine link text based on category
                        $link_text = 'Read Story';
                        if ($category_name) {
                            $category_lower = strtolower($category_name);
                            if (strpos($category_lower, 'training') !== false || strpos($category_lower, 'apprenticeship') !== false) {
                                $link_text = 'Apply Now';
                            } elseif (strpos($category_lower, 'event') !== false || strpos($category_lower, 'meeting') !== false) {
                                $link_text = 'Get Details';
                            } elseif (strpos($category_lower, 'safety') !== false) {
                                $link_text = 'Read Guidelines';
                            } elseif (strpos($category_lower, 'political') !== false) {
                                $link_text = 'View List';
                            } elseif (strpos($category_lower, 'community') !== false || strpos($category_lower, 'volunteer') !== false) {
                                $link_text = 'Volunteer';
                            }
                        }
                ?>
                    <article class="news-archive-card">
                        <div class="news-archive-image">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium'); ?>
                            <?php else : ?>
                                <div class="news-archive-placeholder"></div>
                            <?php endif; ?>
                            <?php if ($category_name) : ?>
                                <span class="news-badge"><?php echo esc_html(strtoupper($category_name)); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="news-archive-content">
                            <div>
                                <p class="news-date"><?php echo get_the_date('M j, Y'); ?></p>
                                <h3 class="news-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p class="news-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="news-link"><?php echo esc_html($link_text); ?> <span class="material-icons">arrow_forward</span></a>
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
            <?php
            $total_pages = max(1, $news_query->max_num_pages);
            $current_page = max(1, $paged);
            
            // Build base URL for pagination
            $base_url = home_url('/news/');
            $url_params = array();
            if (!empty($current_category)) {
                $url_params['news_category'] = $current_category;
            }
            if (!empty($search_query)) {
                $url_params['s'] = $search_query;
            }
            ?>
            <div class="pagination">
                <div class="pagination-nav">
                    <?php
                    // Previous button
                    if ($current_page > 1) :
                        $prev_params = array_merge($url_params, array('pg' => $current_page - 1));
                        if ($current_page - 1 === 1) {
                            unset($prev_params['pg']); // Don't include pg=1
                        }
                        $prev_url = add_query_arg($prev_params, $base_url) . '#news-content';
                    ?>
                        <a href="<?php echo esc_url($prev_url); ?>" class="pagination-arrow" aria-label="Previous page">
                            <span class="material-icons">chevron_left</span>
                        </a>
                    <?php else : ?>
                        <span class="pagination-arrow pagination-disabled" aria-label="Previous page">
                            <span class="material-icons">chevron_left</span>
                        </span>
                    <?php endif; ?>
                    
                    <!-- Page numbers -->
                    <?php for ($i = 1; $i <= $total_pages; $i++) :
                        $page_params = array_merge($url_params, array('pg' => $i));
                        if ($i === 1) {
                            unset($page_params['pg']); // Don't include pg=1
                        }
                        $page_url = add_query_arg($page_params, $base_url) . '#news-content';
                        $is_current = ($i == $current_page);
                    ?>
                        <?php if ($is_current) : ?>
                            <span class="page-number current"><?php echo esc_html($i); ?></span>
                        <?php else : ?>
                            <a href="<?php echo esc_url($page_url); ?>" class="page-number"><?php echo esc_html($i); ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <!-- Next button -->
                    <?php if ($current_page < $total_pages) :
                        $next_params = array_merge($url_params, array('pg' => $current_page + 1));
                        $next_url = add_query_arg($next_params, $base_url) . '#news-content';
                    ?>
                        <a href="<?php echo esc_url($next_url); ?>" class="pagination-arrow" aria-label="Next page">
                            <span class="material-icons">chevron_right</span>
                        </a>
                    <?php else : ?>
                        <span class="pagination-arrow pagination-disabled" aria-label="Next page">
                            <span class="material-icons">chevron_right</span>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php wp_reset_postdata(); ?>
        </div>
    </div>
</div>

<?php
get_footer();

