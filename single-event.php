<?php
/**
 * Single Event Template
 *
 * @package IBEW_Local_53
 */

get_header();
?>

<?php while (have_posts()) : the_post(); ?>
    <?php
    $start_datetime = get_post_meta(get_the_ID(), 'event_start_datetime', true);
    $end_datetime = get_post_meta(get_the_ID(), 'event_end_datetime', true);
    $all_day = get_post_meta(get_the_ID(), 'event_all_day', true);
    $location = get_post_meta(get_the_ID(), 'event_location', true);
    $cta_label = get_post_meta(get_the_ID(), 'event_cta_label', true);
    $cta_url = get_post_meta(get_the_ID(), 'event_cta_url', true);
    
    $event_categories = get_the_terms(get_the_ID(), 'event_category');
    $category_name = !empty($event_categories) ? $event_categories[0]->name : '';
    
    $date_display = ibew_local_53_format_event_date($start_datetime, 'F j, Y');
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
    
    <article class="single-event">
        <div class="single-container">
            <div class="single-header">
                <?php if ($category_name) : ?>
                    <span class="event-category-pill"><?php echo esc_html($category_name); ?></span>
                <?php endif; ?>
                <h1 class="single-title"><?php the_title(); ?></h1>
            </div>
            
            <div class="event-details">
                <div class="event-detail-item">
                    <strong>Date:</strong> <?php echo esc_html($date_display); ?>
                </div>
                <?php if ($time_display) : ?>
                    <div class="event-detail-item">
                        <strong>Time:</strong> <?php echo esc_html($time_display); ?>
                    </div>
                <?php endif; ?>
                <?php if ($location) : ?>
                    <div class="event-detail-item">
                        <strong>Location:</strong> <?php echo esc_html($location); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (has_post_thumbnail()) : ?>
                <div class="single-featured-image">
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php endif; ?>
            
            <div class="single-content">
                <?php the_content(); ?>
            </div>
            
            <?php if ($cta_label && $cta_url) : ?>
                <div class="event-cta">
                    <a href="<?php echo esc_url($cta_url); ?>" class="btn btn-primary"><?php echo esc_html($cta_label); ?> →</a>
                </div>
            <?php endif; ?>
            
            <div class="single-footer">
                <a href="<?php echo home_url('/events'); ?>" class="btn btn-secondary">← Back to Events</a>
            </div>
        </div>
    </article>
<?php endwhile; ?>

<?php
get_footer();

