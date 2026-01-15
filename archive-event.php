<?php
/**
 * Events Archive Template
 *
 * @package IBEW_Local_53
 */

get_header();
?>

<!-- Events Archive Hero -->
<section class="archive-hero events-hero">
    <div class="events-hero-card">
        <div class="hero-pill">Get Involved</div>
        <h1 class="hero-title">Events & Calendar</h1>
        <p class="hero-subtext">Stay up to date with Local 53 meetings, training sessions, and community events. Your participation strengthens our brotherhood.</p>
    </div>
</section>

<div class="archive-container">
    <div class="archive-layout events-layout">
        <!-- Left Column -->
        <aside class="events-sidebar">
            <!-- Mini Calendar Card -->
            <div class="sidebar-card calendar-card">
                <div class="calendar-header">
                    <button class="calendar-nav prev-month" aria-label="Previous month"><span class="material-icons">chevron_left</span></button>
                    <h3 class="calendar-month-year" id="calendar-month-year">November 2023</h3>
                    <button class="calendar-nav next-month" aria-label="Next month"><span class="material-icons">chevron_right</span></button>
                </div>
                <div class="calendar-grid" id="calendar-grid">
                    <!-- Calendar will be populated by JavaScript -->
                </div>
            </div>
            
            <!-- Event Categories Card -->
            <div class="sidebar-card">
                <h3 class="sidebar-card-title">Event Categories</h3>
                <ul class="category-list">
                    <?php
                    $categories = get_terms(array(
                        'taxonomy' => 'event_category',
                        'hide_empty' => true,
                    ));
                    
                    foreach ($categories as $category) :
                        $color_class = sanitize_html_class($category->slug);
                    ?>
                        <li>
                            <a href="<?php echo get_term_link($category); ?>" class="category-item">
                                <span class="category-dot <?php echo esc_attr($color_class); ?>"></span>
                                <span class="category-label"><?php echo esc_html($category->name); ?></span>
                                <span class="category-count"><?php echo $category->count; ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </aside>
        
        <!-- Right Column -->
        <div class="events-content">
            <?php
            // Get current date/time in datetime-local format for comparison
            $current_datetime = date('Y-m-d\TH:i');
            
            $events_query = new WP_Query(array(
                'post_type' => 'event',
                'posts_per_page' => -1,
                'meta_key' => 'event_start_datetime',
                'orderby' => 'meta_value',
                'order' => 'ASC',
                'meta_query' => array(
                    array(
                        'key' => 'event_start_datetime',
                        'value' => $current_datetime,
                        'compare' => '>=',
                        'type' => 'CHAR',
                    ),
                ),
            ));
            
            if ($events_query->have_posts()) :
                $events_by_month = array();
                
                // Group events by month
                while ($events_query->have_posts()) : $events_query->the_post();
                    $start_datetime = get_post_meta(get_the_ID(), 'event_start_datetime', true);
                    if (empty($start_datetime)) {
                        continue;
                    }
                    // Convert datetime-local format to timestamp for date functions
                    $datetime_for_format = str_replace('T', ' ', $start_datetime);
                    if (strlen($datetime_for_format) === 16) {
                        $datetime_for_format .= ':00';
                    }
                    $month_key = date('F Y', strtotime($datetime_for_format));
                    if (!isset($events_by_month[$month_key])) {
                        $events_by_month[$month_key] = array();
                    }
                    $events_by_month[$month_key][] = get_the_ID();
                endwhile;
                
                // Display events grouped by month
                foreach ($events_by_month as $month => $event_ids) :
                    $month_time = strtotime('1 ' . $month);
                    $month_slug = $month_time ? strtolower(date('F', $month_time)) : 'default';
            ?>
                <div class="events-month-section">
                    <h2 class="month-heading month-heading--<?php echo esc_attr($month_slug); ?>"><?php echo esc_html($month); ?></h2>
                    
                    <?php foreach ($event_ids as $event_id) :
                        $post = get_post($event_id);
                        setup_postdata($post);
                        
                        $start_datetime = get_post_meta($event_id, 'event_start_datetime', true);
                        $end_datetime = get_post_meta($event_id, 'event_end_datetime', true);
                        $all_day = get_post_meta($event_id, 'event_all_day', true);
                        $location = get_post_meta($event_id, 'event_location', true);
                        $cta_label = get_post_meta($event_id, 'event_cta_label', true);
                        $cta_url = get_post_meta($event_id, 'event_cta_url', true);
                        
                        // Convert datetime-local format for date functions
                        $datetime_for_format = str_replace('T', ' ', $start_datetime);
                        if (strlen($datetime_for_format) === 16) {
                            $datetime_for_format .= ':00';
                        }
                        $date_badge_month = date('M', strtotime($datetime_for_format));
                        $date_badge_day = date('d', strtotime($datetime_for_format));
                        $date_badge_weekday = date('D', strtotime($datetime_for_format));
                        
                        $event_categories = get_the_terms($event_id, 'event_category');
                        $category_name = !empty($event_categories) ? $event_categories[0]->name : 'Event';
                        $category_class = !empty($event_categories) ? sanitize_html_class($event_categories[0]->slug) : 'event';
                        
                        $time_display = '';
                        if ($all_day) {
                            $time_display = 'All Day';
                        } elseif (!empty($start_datetime)) {
                            $start_time = ibew_local_53_format_event_time($start_datetime);
                            if (!empty($end_datetime)) {
                                $end_time = ibew_local_53_format_event_time($end_datetime);
                                $time_display = $start_time . ' - ' . $end_time;
                            } else {
                                $time_display = $start_time;
                            }
                        }
                    ?>
                        <article class="event-list-item">
                            <div class="event-date-badge <?php echo esc_attr($category_class); ?>">
                                <span class="date-month"><?php echo esc_html($date_badge_month); ?></span>
                                <span class="date-day"><?php echo esc_html($date_badge_day); ?></span>
                                <span class="date-weekday"><?php echo esc_html($date_badge_weekday); ?></span>
                            </div>
                            <div class="event-list-body">
                                <div class="event-list-top">
                                    <span class="event-category-pill <?php echo esc_attr($category_class); ?>">
                                        <span class="category-dot <?php echo esc_attr($category_class); ?>"></span>
                                        <?php echo esc_html($category_name); ?>
                                    </span>
                                    <?php if ($time_display) : ?>
                                        <span class="event-time"><span class="material-icons">schedule</span><?php echo esc_html($time_display); ?></span>
                                    <?php endif; ?>
                                </div>
                                <h3 class="event-list-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p class="event-list-description"><?php echo wp_trim_words(get_the_excerpt(), 25); ?></p>
                                <div class="event-list-footer">
                                    <?php if ($location) : ?>
                                        <span class="event-location"><span class="material-icons">location_on</span><?php echo esc_html($location); ?></span>
                                    <?php endif; ?>
                                    <?php if ($cta_label && $cta_url) : ?>
                                        <a href="<?php echo esc_url($cta_url); ?>" class="event-cta-link"><?php echo esc_html($cta_label); ?> <span class="material-icons">arrow_forward</span></a>
                                    <?php else : ?>
                                        <a href="<?php the_permalink(); ?>" class="event-cta-link">Event Details <span class="material-icons">arrow_forward</span></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php
                endforeach;
                wp_reset_postdata();
            else :
            ?>
                <p class="no-posts">No upcoming events found.</p>
            <?php endif; ?>
            
            <!-- Pagination -->
            <?php if ($events_query->max_num_pages > 1) : ?>
                <div class="pagination">
                    <div class="pagination-nav">
                        <?php
                        echo paginate_links(array(
                            'total' => $events_query->max_num_pages,
                            'prev_next' => true,
                            'prev_text' => 'Previous',
                            'next_text' => 'Next',
                            'type' => 'list',
                            'before_page_number' => '<span class="page-number">',
                            'after_page_number' => '</span>',
                        ));
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
get_footer();

