<?php
/**
 * Front Page Template
 *
 * @package IBEW_Local_53
 */

get_header();
?>

<!-- Home Hero Section -->
<section class="home-hero">
    <div class="hero-card">
        <div class="hero-background-overlay"></div>
        <div class="hero-content">
            <div class="hero-pill">Est. 1910 • Kansas City, MO</div>
            <h1 class="hero-title">
                POWERING THE FUTURE
                <span>WITH <span class="gold-text">SKILLED LABOR</span></span>
            </h1>
            <p class="hero-subtext">IBEW Local 53 is dedicated to providing the highest quality electrical workers<br>for our communities while securing fair wages, benefits, and safety for our<br>members.</p>
            <div class="hero-buttons">
                <a href="https://ibewyes.org" class="btn btn-primary" target="_blank" rel="noopener noreferrer">Join the Brotherhood <span class="btn-icon">arrow_forward</span></a>
                <a href="<?php echo home_url('/events'); ?>" class="btn btn-secondary">View Upcoming Events</a>
            </div>
        </div>
    </div>
</section>

<!-- Feature Chips (Overlapping Hero) -->
<section class="feature-chips">
    <div class="chips-container">
        <div class="feature-chip chip-priority">
            <div class="chip-icon-wrapper">
                <span class="material-icons">security</span>
            </div>
            <div class="chip-content">
                <div class="chip-label">Priority</div>
                <div class="chip-title">Safety First</div>
            </div>
        </div>
        <div class="feature-chip chip-community">
            <div class="chip-icon-wrapper">
                <span class="material-icons">handshake</span>
            </div>
            <div class="chip-content">
                <div class="chip-label">Community</div>
                <div class="chip-title">Brotherhood</div>
            </div>
        </div>
        <div class="feature-chip chip-growth">
            <div class="chip-icon-wrapper">
                <span class="material-icons">school</span>
            </div>
            <div class="chip-content">
                <div class="chip-label">Growth</div>
                <div class="chip-title">Apprenticeship</div>
            </div>
        </div>
        <div class="feature-chip chip-benefits">
            <div class="chip-icon-wrapper">
                <span class="material-icons">payments</span>
            </div>
            <div class="chip-content">
                <div class="chip-label">Benefits</div>
                <div class="chip-title">Fair Wages</div>
            </div>
        </div>
    </div>
</section>

<!-- Latest News Section -->
<section class="latest-news-section">
    <div class="section-container">
        <div class="section-header reveal-fade-up">
            <div class="section-header-left">
                <h2 class="section-title">Latest News</h2>
                <p class="section-subtitle">Updates from the Local 53 leadership and community.</p>
            </div>
            <div class="section-header-right">
                <a href="<?php echo home_url('/news'); ?>" class="view-all-link">View All News →</a>
            </div>
        </div>
        
        <div class="news-grid reveal-stagger">
            <?php
            $news_query = new WP_Query(array(
                'post_type' => 'news',
                'posts_per_page' => 4,
                'orderby' => 'date',
                'order' => 'DESC',
            ));
            
            if ($news_query->have_posts()) :
                $is_first = true;
                while ($news_query->have_posts()) : $news_query->the_post();
                    $categories = get_the_terms(get_the_ID(), 'news_category');
                    $category_name = !empty($categories) ? $categories[0]->name : '';
            ?>
                <article class="news-card">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="news-card-image">
                            <?php the_post_thumbnail('medium_large'); ?>
                            <?php if ($is_first) : ?>
                                <span class="news-badge badge-new">NEW</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="news-card-content">
                        <p class="news-date">
                            <?php echo get_the_date('M j, Y'); ?>
                            <?php if ($category_name) : ?>
                                <span class="news-date-separator">•</span>
                                <span class="news-category"><?php echo esc_html($category_name); ?></span>
                            <?php endif; ?>
                        </p>
                        <h3 class="news-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <a href="<?php the_permalink(); ?>" class="news-link">Read Update <span class="material-icons">play_circle</span></a>
                    </div>
                </article>
            <?php
                    $is_first = false;
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
        <div class="news-view-all-mobile">
            <a href="<?php echo home_url('/news'); ?>" class="view-all-link">View All News <span class="material-icons">arrow_forward</span></a>
        </div>
    </div>
</section>

<!-- Who We Are Section -->
<section class="who-we-are-section">
    <div class="section-container">
        <div class="who-we-are-grid">
            <div class="who-we-are-content reveal-fade-right">
                <span class="who-we-are-label">Who We Are</span>
                <h2 class="section-title-large">
                Dedicated to Excellence, Committed to <span class="blue-text">Our Members</span>.
                </h2>
                <p class="who-we-are-text">
                    For over a century, IBEW Local 53 has been the cornerstone of electrical excellence in Kansas City. 
                    We represent skilled electrical workers who power our communities with dedication, safety, and expertise. 
                    Our commitment extends beyond the job site—we support our members, their families, and our community 
                    through training, advocacy, and solidarity.
                </p>
                <div class="who-we-are-actions">
                    <a href="<?php echo home_url('/about'); ?>" class="btn btn-primary">More About Us</a>
                    <div class="members-indicator">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/members-sample.svg" alt="Members" class="members-icon" />
                        <div class="indicator-text">
                            <strong>3,200+</strong> Members
                        </div>
                    </div>
                </div>
            </div>
            <div class="who-we-are-image reveal-fade-left reveal-delay-2">
                <div class="illustration-card">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/lineman-working.jpg" alt="Lineman Working" class="illustration-image" />
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Upcoming Events Section -->
<section class="upcoming-events-section">
    <div class="section-container">
        <div class="section-header reveal-fade-up">
            <h2 class="section-title">Upcoming Events</h2>
            <div class="event-nav-controls">
                <button class="nav-arrow prev-arrow" aria-label="Previous events">‹</button>
                <button class="nav-arrow next-arrow" aria-label="Next events">›</button>
            </div>
        </div>
        
        <div class="events-grid reveal-fade-up reveal-delay-1">
            <?php
            // Get current date/time in datetime-local format for comparison
            $current_datetime = date('Y-m-d\TH:i');
            
            $events_query = new WP_Query(array(
                'post_type' => 'event',
                'posts_per_page' => 12,
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
                while ($events_query->have_posts()) : $events_query->the_post();
                    $start_datetime = get_post_meta(get_the_ID(), 'event_start_datetime', true);
                    // Convert datetime-local format for date functions
                    $datetime_for_format = str_replace('T', ' ', $start_datetime);
                    if (strlen($datetime_for_format) === 16) {
                        $datetime_for_format .= ':00';
                    }
                    $event_month = !empty($start_datetime) ? date('M', strtotime($datetime_for_format)) : '';
                    $event_day = !empty($start_datetime) ? date('j', strtotime($datetime_for_format)) : '';
                    $event_categories = get_the_terms(get_the_ID(), 'event_category');
                    $category_name = !empty($event_categories) ? $event_categories[0]->name : 'Event';
            ?>
                <article class="event-card">
                    <div class="event-card-image">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium_large'); ?>
                        <?php else : ?>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/IBEW53.avif" alt="<?php echo esc_attr(get_the_title()); ?>" />
                        <?php endif; ?>
                        <?php if (!empty($event_month) && !empty($event_day)) : ?>
                            <div class="event-date-badge">
                                <span class="date-month"><?php echo esc_html($event_month); ?></span>
                                <span class="date-day"><?php echo esc_html($event_day); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="event-card-content">
                        <h3 class="event-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <p class="event-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                    </div>
                </article>
            <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</section>

<!-- Bottom CTA Banner -->
<section class="cta-banner reveal-scale">
    <div class="cta-container">
        <div class="cta-content">
            <h2 class="cta-title">Looking to join a Union?</h2>
            <p class="cta-text">Discover the benefits of union membership and start your journey with IBEW Local 53.</p>
            <a href="#" class="btn btn-white">Join Now</a>
        </div>
        <div class="cta-decoration">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/join-now.png" alt="Join Now" class="cta-image" />
        </div>
    </div>
</section>

<?php
get_footer();



