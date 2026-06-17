<?php
/**
 * Template Name: Resources Page
 * 
 * @package IBEW_Local_53
 */

get_header();

$show_member_documents = is_user_logged_in();
$training_category_slug = 'training';
$training_term = get_term_by('slug', $training_category_slug, 'resource_category');
$training_term_id = ($training_term && !is_wp_error($training_term)) ? (int) $training_term->term_id : 0;

// Get official documents data only for logged-in members.
$resource_categories = array();
$documents_query = null;
$training_resources_query = null;
if ($show_member_documents) {
    $category_args = array(
        'taxonomy' => 'resource_category',
        'hide_empty' => true,
    );
    if ($training_term_id) {
        $category_args['exclude'] = array($training_term_id);
    }
    $resource_categories = get_terms($category_args);

    $document_query_args = array(
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
    );

    $documents_query = new WP_Query(array_merge($document_query_args, array(
        'tax_query' => $training_term_id ? array(
            array(
                'taxonomy' => 'resource_category',
                'field' => 'term_id',
                'terms' => $training_term_id,
                'operator' => 'NOT IN',
            ),
        ) : array(),
    )));

    $training_resources_query = $training_term_id ? new WP_Query(array_merge($document_query_args, array(
        'tax_query' => array(
            array(
                'taxonomy' => 'resource_category',
                'field' => 'term_id',
                'terms' => $training_term_id,
            ),
        ),
    ))) : null;
}

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
    <div class="archive-hero-container reveal-fade-up">
        <h1 class="hero-title">Resources Hub</h1>
        <p class="hero-subtext">Download official documents and access essential external union tools and websites.</p>
    </div>
</section>

<!-- Main Content -->
<div class="resources-page-container">
    
    <?php if ($show_member_documents) : ?>
        <!-- Official Documents Section -->
        <section class="resources-documents-section" id="official-documents-section">
            <div class="section-header-row reveal-fade-up">
                <div class="section-header-left">
                    <h2 class="section-title">Official Documents</h2>
                    
                    <!-- Category Filter Chips -->
                    <div class="resource-category-filters reveal-stagger">
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
                <div class="resource-search-container reveal-fade-up reveal-delay-1">
                    <div class="resource-search-input">
                        <span class="material-icons search-icon">search</span>
                        <input type="text" id="resource-search" placeholder="Search documents..." />
                    </div>
                </div>
            </div>
            
            <!-- Documents Grid (two rows per page + pagination) -->
            <div class="resources-documents-grid-wrap">
            <div class="resources-grid reveal-stagger" id="resources-grid">
                <?php if ($documents_query && $documents_query->have_posts()) : ?>
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
                                case 'training':
                                    $icon_bg_color = '#fef3c7';
                                    $icon_color = '#d97706';
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
                    <div class="no-resources-message reveal-fade-up">
                        <p>No documents available at this time. Check back soon!</p>
                    </div>
                <?php endif; ?>
            </div>
            <nav class="resources-pagination pagination" id="resources-pagination" aria-label="Document list pages" hidden>
                <div class="pagination-nav" id="resources-pagination-nav"></div>
            </nav>
            </div>
            
            <!-- No results message (hidden by default) -->
            <div class="no-results-message reveal-fade-up" id="no-results-message" style="display: none;">
                <span class="material-icons">search_off</span>
                <p>No documents match your search criteria.</p>
            </div>
        </section>
    <?php endif; ?>
    
    <!-- External Resources Section -->
    <section class="external-resources-section">
        <div class="external-resources-header reveal-fade-up">
            <div class="header-accent"></div>
            <div class="header-content">
                <h2 class="section-title">External Resources</h2>
                <p class="section-subtitle">Important links for benefits and partner organizations.</p>
            </div>
        </div>
        
        <div class="external-links-grid reveal-stagger">
            <?php if ($external_links_query->have_posts()) : ?>
                <?php while ($external_links_query->have_posts()) : $external_links_query->the_post();
                    $link_url = get_post_meta(get_the_ID(), 'resource_link_url', true);
                    $is_pdf_link = !empty($link_url) && preg_match('/\.pdf(\?.*)?$/i', $link_url);
                ?>
                    <a href="<?php echo esc_url($link_url); ?>" class="external-link-item" target="_blank" rel="noopener noreferrer">
                        <span class="material-icons external-link-type-icon <?php echo $is_pdf_link ? 'is-pdf' : 'is-url'; ?>" aria-hidden="true">
                            <?php echo $is_pdf_link ? 'picture_as_pdf' : 'link'; ?>
                        </span>
                        <span class="link-text"><?php the_title(); ?></span>
                    </a>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p class="no-links-message reveal-fade-up">No external links available at this time.</p>
            <?php endif; ?>
        </div>
    </section>

    <?php if ($show_member_documents) : ?>
        <!-- Training Resources Section -->
        <section class="resources-documents-section training-resources-section" id="training-resources-section">
            <div class="section-header-row reveal-fade-up">
                <div class="section-header-left">
                    <h2 class="section-title">Training Resources</h2>
                </div>

                <div class="resource-search-container reveal-fade-up reveal-delay-1">
                    <div class="resource-search-input">
                        <span class="material-icons search-icon">search</span>
                        <input type="text" id="training-resource-search" placeholder="Search documents..." />
                    </div>
                </div>
            </div>

            <div class="resources-documents-grid-wrap">
            <div class="resources-grid reveal-stagger" id="training-resources-grid">
                <?php if ($training_resources_query && $training_resources_query->have_posts()) : ?>
                    <?php while ($training_resources_query->have_posts()) : $training_resources_query->the_post();
                        $file_info = ibew_local_53_get_resource_file_info(get_the_ID());
                        $icon_bg_color = '#fef3c7';
                        $icon_color = '#d97706';
                    ?>
                        <article class="resource-card" data-title="<?php echo esc_attr(strtolower(get_the_title())); ?>">
                            <div class="resource-card-content">
                                <div class="resource-icon" style="background-color: <?php echo esc_attr($icon_bg_color); ?>;">
                                    <svg width="30" height="36" viewBox="0 0 30 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M18.75 0H3.75C1.6875 0 0 1.6875 0 3.75V32.25C0 34.3125 1.6875 36 3.75 36H26.25C28.3125 36 30 34.3125 30 32.25V11.25L18.75 0ZM22.5 28.5H7.5V24.75H22.5V28.5ZM22.5 21H7.5V17.25H22.5V21ZM16.875 13.125V2.8125L27.1875 13.125H16.875Z" fill="<?php echo esc_attr($icon_color); ?>"/>
                                    </svg>
                                </div>
                                <div class="resource-info">
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
                    <div class="no-resources-message reveal-fade-up">
                        <p>No training resources available at this time. Check back soon!</p>
                    </div>
                <?php endif; ?>
            </div>
            <nav class="resources-pagination pagination" id="training-resources-pagination" aria-label="Training document list pages" hidden>
                <div class="pagination-nav" id="training-resources-pagination-nav"></div>
            </nav>
            </div>

            <div class="no-results-message reveal-fade-up" id="training-no-results-message" style="display: none;">
                <span class="material-icons">search_off</span>
                <p>No documents match your search criteria.</p>
            </div>
        </section>
    <?php endif; ?>

    <!-- Help CTA Section -->
    <section class="resources-cta-section">
        <div class="cta-content reveal-fade-up">
            <h3 class="cta-title">Still can't find what you need?</h3>
            <p class="cta-text">Our staff is available at the Main Hall to assist with specific jurisdiction documentation and member requests.</p>
        </div>
        <div class="cta-actions reveal-fade-up reveal-delay-1">
            <a href="mailto:localrep@ibewlocal53.org" class="btn btn-cta-gold">
                Email Us
                <span class="material-icons">arrow_forward</span>
            </a>
            <a href="tel:+18164315434" class="btn btn-cta-outline">
                Call (816) 431-5434
            </a>
        </div>
    </section>
    
</div>

<?php get_footer(); ?>
