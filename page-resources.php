<?php
/**
 * Template Name: Resources Page
 * 
 * @package IBEW_Local_53
 */

get_header();

// Get all resource categories (only for documents)
$resource_categories = get_terms(array(
    'taxonomy' => 'resource_category',
    'hide_empty' => true,
));

// Get all document resources
$documents_query = new WP_Query(array(
    'post_type' => 'resource',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
    'meta_query' => array(
        'relation' => 'OR',
        array(
            'key' => 'resource_type',
            'value' => 'document',
            'compare' => '=',
        ),
        array(
            'key' => 'resource_type',
            'compare' => 'NOT EXISTS',
        ),
    ),
));

// Get all external link resources
$external_links_query = new WP_Query(array(
    'post_type' => 'resource',
    'posts_per_page' => -1,
    'meta_query' => array(
        array(
            'key' => 'resource_type',
            'value' => 'external_link',
            'compare' => '=',
        ),
    ),
    'meta_key' => 'resource_display_order',
    'orderby' => array(
        'meta_value_num' => 'ASC',
        'title' => 'ASC',
    ),
));
?>

<!-- Resources Hero Section -->
<section class="archive-hero resources-hero">
    <div class="archive-hero-container">
        <h1 class="hero-title">Resources Hub</h1>
        <p class="hero-subtext">Download official documents and access essential external union tools and websites.</p>
    </div>
</section>

<!-- Main Content -->
<div class="resources-page-container">
    
    <!-- Official Documents Section -->
    <section class="resources-documents-section">
        <div class="section-header-row">
            <div class="section-header-left">
                <h2 class="section-title">Official Documents</h2>
                
                <!-- Category Filter Chips -->
                <div class="resource-category-filters">
                    <button class="filter-chip active" data-category="all">All Files</button>
                    <?php if (!empty($resource_categories) && !is_wp_error($resource_categories)) : ?>
                        <?php foreach ($resource_categories as $category) : ?>
                            <button class="filter-chip" data-category="<?php echo esc_attr($category->slug); ?>">
                                <?php echo esc_html($category->name); ?>
                            </button>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Search Input -->
            <div class="resource-search-container">
                <div class="resource-search-input">
                    <span class="material-icons search-icon">search</span>
                    <input type="text" id="resource-search" placeholder="Search documents..." />
                </div>
            </div>
        </div>
        
        <!-- Documents Grid -->
        <div class="resources-grid" id="resources-grid">
            <?php if ($documents_query->have_posts()) : ?>
                <?php while ($documents_query->have_posts()) : $documents_query->the_post(); 
                    $file_info = ibew_local_53_get_resource_file_info(get_the_ID());
                    $categories = get_the_terms(get_the_ID(), 'resource_category');
                    $category_name = !empty($categories) ? $categories[0]->name : '';
                    $category_slugs = !empty($categories) ? implode(' ', wp_list_pluck($categories, 'slug')) : '';
                    
                    // Determine file icon color based on category
                    $icon_bg_color = '#fef2f2'; // Default pink/red
                    $icon_color = '#dc2626';
                    if (!empty($categories)) {
                        $cat_slug = $categories[0]->slug;
                        switch ($cat_slug) {
                            case 'contracts':
                                $icon_bg_color = '#fef2f2';
                                $icon_color = '#dc2626';
                                break;
                            case 'safety':
                                $icon_bg_color = '#fef3c7';
                                $icon_color = '#d97706';
                                break;
                            case 'benefits':
                                $icon_bg_color = '#dbeafe';
                                $icon_color = '#2563eb';
                                break;
                            case 'wage-scales':
                                $icon_bg_color = '#dcfce7';
                                $icon_color = '#16a34a';
                                break;
                            default:
                                $icon_bg_color = '#f3f4f6';
                                $icon_color = '#6b7280';
                        }
                    }
                ?>
                    <article class="resource-card" data-categories="<?php echo esc_attr($category_slugs); ?>" data-title="<?php echo esc_attr(strtolower(get_the_title())); ?>">
                        <div class="resource-card-content">
                            <div class="resource-icon" style="background-color: <?php echo esc_attr($icon_bg_color); ?>;">
                                <svg width="30" height="36" viewBox="0 0 30 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.75 0H3.75C1.6875 0 0 1.6875 0 3.75V32.25C0 34.3125 1.6875 36 3.75 36H26.25C28.3125 36 30 34.3125 30 32.25V11.25L18.75 0ZM22.5 28.5H7.5V24.75H22.5V28.5ZM22.5 21H7.5V17.25H22.5V21ZM16.875 13.125V2.8125L27.1875 13.125H16.875Z" fill="<?php echo esc_attr($icon_color); ?>"/>
                                </svg>
                            </div>
                            <div class="resource-info">
                                <?php if ($category_name) : ?>
                                    <span class="resource-category"><?php echo esc_html($category_name); ?></span>
                                <?php endif; ?>
                                <h3 class="resource-title"><?php the_title(); ?></h3>
                                <?php if ($file_info) : ?>
                                    <span class="resource-meta"><?php echo esc_html($file_info['type']); ?> • <?php echo esc_html($file_info['size']); ?> • Updated <?php echo esc_html($file_info['updated']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ($file_info) : ?>
                            <div class="resource-actions">
                                <a href="<?php echo esc_url($file_info['url']); ?>" class="btn btn-download" download>
                                    <svg width="18" height="22" viewBox="0 0 18 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M18 7.75H12.75V0.25H5.25V7.75H0L9 16.75L18 7.75ZM0 19.25V21.75H18V19.25H0Z" fill="currentColor"/>
                                    </svg>
                                    <span>Download</span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </article>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <div class="no-resources-message">
                    <p>No documents available at this time. Check back soon!</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- No results message (hidden by default) -->
        <div class="no-results-message" id="no-results-message" style="display: none;">
            <span class="material-icons">search_off</span>
            <p>No documents match your search criteria.</p>
        </div>
    </section>
    
    <!-- External Resources Section -->
    <section class="external-resources-section">
        <div class="external-resources-header">
            <div class="header-accent"></div>
            <div class="header-content">
                <h2 class="section-title">External Resources</h2>
                <p class="section-subtitle">Important links for benefits, training, and partner organizations.</p>
            </div>
        </div>
        
        <div class="external-links-grid">
            <?php if ($external_links_query->have_posts()) : ?>
                <?php while ($external_links_query->have_posts()) : $external_links_query->the_post();
                    $link_url = get_post_meta(get_the_ID(), 'resource_link_url', true);
                ?>
                    <a href="<?php echo esc_url($link_url); ?>" class="external-link-item" target="_blank" rel="noopener noreferrer">
                        <span class="link-text"><?php the_title(); ?></span>
                        <svg class="external-link-icon" width="14" height="17" viewBox="0 0 14 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.4444 12.1H1.55556V4.7H7V3.5H1.55556C0.855556 3.5 0.311111 4.04 0.311111 4.7L0.308333 12.1C0.308333 12.76 0.855556 13.3 1.55556 13.3H12.4444C13.1444 13.3 13.6889 12.76 13.6889 12.1V6.5H12.4444V12.1ZM8.55556 3.5V4.7H11.3633L3.91222 11.7505L4.78667 12.595L12.4444 5.3395V8.3H13.6889V3.5H8.55556Z" fill="currentColor"/>
                        </svg>
                    </a>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p class="no-links-message">No external links available at this time.</p>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Help CTA Section -->
    <section class="resources-cta-section">
        <div class="cta-content">
            <h3 class="cta-title">Still can't find what you need?</h3>
            <p class="cta-text">Our staff is available at the Main Hall to assist with specific jurisdiction documentation and member requests.</p>
        </div>
        <div class="cta-actions">
            <a href="mailto:info@ibewlocal53.org" class="btn btn-outline">
                Email Us
                <span class="material-icons">arrow_forward</span>
            </a>
            <a href="tel:+18164315434" class="btn btn-outline">
                Call (816) 431-5434
            </a>
        </div>
    </section>
    
</div>

<?php get_footer(); ?>
