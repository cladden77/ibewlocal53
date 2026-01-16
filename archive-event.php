<?php
/**
 * Events Archive Template
 *
 * @package IBEW_Local_53
 */

get_header();

/**
 * Map event category to CSS class for consistent styling
 * First 4 categories get specific colors, additional categories get assigned from a color palette
 */
function ibew_get_event_category_class($category) {
    $name_lower = strtolower($category->name);
    $slug = $category->slug;
    
    // Map by name first (most reliable) - Primary 4 categories
    if (strpos($name_lower, 'union') !== false && strpos($name_lower, 'meeting') !== false) {
        return 'union-meetings';
    }
    if (strpos($name_lower, 'social') !== false && strpos($name_lower, 'event') !== false) {
        return 'social-events';
    }
    if (strpos($name_lower, 'training') !== false || strpos($name_lower, 'safety') !== false) {
        return 'training-safety';
    }
    if (strpos($name_lower, 'holiday') !== false) {
        return 'holiday';
    }
    
    // Fallback to slug-based mapping for primary 4
    if (strpos($slug, 'union') !== false) {
        return 'union-meetings';
    }
    if (strpos($slug, 'social') !== false) {
        return 'social-events';
    }
    if (strpos($slug, 'training') !== false || strpos($slug, 'safety') !== false) {
        return 'training-safety';
    }
    if (strpos($slug, 'holiday') !== false) {
        return 'holiday';
    }
    
    // For additional categories (5th and beyond), assign colors from a palette
    // Use a hash of the slug to consistently assign the same category to the same color
    $additional_colors = array('category-5', 'category-6', 'category-7', 'category-8', 'category-9', 'category-10');
    $hash = crc32($slug);
    $color_index = abs($hash) % count($additional_colors);
    
    return $additional_colors[$color_index];
}
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
            <?php
            // Get all event dates for calendar highlighting with category information
            $all_events_query = new WP_Query(array(
                'post_type' => 'event',
                'posts_per_page' => -1,
                'meta_key' => 'event_start_datetime',
                'orderby' => 'meta_value',
                'order' => 'ASC',
            ));
            
            $event_dates = array(); // Array of dates
            $event_dates_with_categories = array(); // Associative array: date => category_class
            if ($all_events_query->have_posts()) {
                while ($all_events_query->have_posts()) {
                    $all_events_query->the_post();
                    $start_datetime = get_post_meta(get_the_ID(), 'event_start_datetime', true);
                    if (!empty($start_datetime)) {
                        // Convert datetime-local format to YYYY-MM-DD
                        $date_only = substr($start_datetime, 0, 10);
                        if (!in_array($date_only, $event_dates)) {
                            $event_dates[] = $date_only;
                            
                            // Get category for this event
                            $event_categories = get_the_terms(get_the_ID(), 'event_category');
                            $category_class = 'event'; // default
                            if (!empty($event_categories)) {
                                $category_class = ibew_get_event_category_class($event_categories[0]);
                            }
                            $event_dates_with_categories[$date_only] = $category_class;
                        }
                    }
                }
                wp_reset_postdata();
            }
            ?>
            <div class="sidebar-card calendar-card" data-event-dates="<?php echo esc_attr(json_encode($event_dates)); ?>" data-event-categories="<?php echo esc_attr(json_encode($event_dates_with_categories)); ?>">
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
                        $color_class = ibew_get_event_category_class($category);
                    ?>
                        <li>
                            <a href="<?php echo get_term_link($category); ?>" class="category-item">
                                <span class="category-dot <?php echo esc_attr($color_class); ?>">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/checkmark-icon.svg" alt="" class="checkmark-icon" />
                                </span>
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
                        $event_date_only = substr($start_datetime, 0, 10); // YYYY-MM-DD format
                        
                        $event_categories = get_the_terms($event_id, 'event_category');
                        $category_name = !empty($event_categories) ? $event_categories[0]->name : 'Event';
                        $category_class = !empty($event_categories) ? ibew_get_event_category_class($event_categories[0]) : 'event';
                        
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
                        <article class="event-list-item" data-event-date="<?php echo esc_attr($event_date_only); ?>">
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
            
            <!-- No Events Scheduled Message (hidden by default) -->
            <div class="no-events-scheduled" id="no-events-scheduled" style="display: none;">
                <p>No Events Scheduled</p>
            </div>
            
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

