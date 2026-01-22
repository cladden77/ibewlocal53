<?php
/**
 * IBEW Local 53 Theme Functions
 *
 * @package IBEW_Local_53
 */

if (!defined('ABSPATH')) {
    exit;
}

// Theme setup
function ibew_local_53_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'ibew-local-53'),
    ));
    
    // Register custom 16:12 image size for news and events
    add_image_size('featured-16-12', 1280, 960, true);
}
add_action('after_setup_theme', 'ibew_local_53_setup');

// Enqueue styles and scripts
function ibew_local_53_scripts() {
    // Enqueue Inter font
    wp_enqueue_style('inter-font', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap', array(), null);
    // Enqueue Material Icons
    wp_enqueue_style('material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons', array(), null);
    wp_enqueue_style('ibew-local-53-style', get_stylesheet_uri(), array(), '1.0.0');
    wp_enqueue_style('ibew-local-53-main', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0');
    wp_enqueue_script('ibew-local-53-main', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'ibew_local_53_scripts');

// Register Custom Post Types
function ibew_local_53_register_post_types() {
    // News Post Type
    register_post_type('news', array(
        'labels' => array(
            'name' => __('News', 'ibew-local-53'),
            'singular_name' => __('News Item', 'ibew-local-53'),
            'add_new' => __('Add New', 'ibew-local-53'),
            'add_new_item' => __('Add New News Item', 'ibew-local-53'),
            'edit_item' => __('Edit News Item', 'ibew-local-53'),
            'new_item' => __('New News Item', 'ibew-local-53'),
            'view_item' => __('View News Item', 'ibew-local-53'),
            'search_items' => __('Search News', 'ibew-local-53'),
            'not_found' => __('No news items found', 'ibew-local-53'),
            'not_found_in_trash' => __('No news items found in Trash', 'ibew-local-53'),
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'news'),
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
        'menu_icon' => 'dashicons-megaphone',
        'show_in_rest' => true,
    ));

    // Event Post Type
    register_post_type('event', array(
        'labels' => array(
            'name' => __('Events', 'ibew-local-53'),
            'singular_name' => __('Event', 'ibew-local-53'),
            'add_new' => __('Add New', 'ibew-local-53'),
            'add_new_item' => __('Add New Event', 'ibew-local-53'),
            'edit_item' => __('Edit Event', 'ibew-local-53'),
            'new_item' => __('New Event', 'ibew-local-53'),
            'view_item' => __('View Event', 'ibew-local-53'),
            'search_items' => __('Search Events', 'ibew-local-53'),
            'not_found' => __('No events found', 'ibew-local-53'),
            'not_found_in_trash' => __('No events found in Trash', 'ibew-local-53'),
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'events'),
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
        'menu_icon' => 'dashicons-calendar-alt',
        'show_in_rest' => true,
    ));
}
add_action('init', 'ibew_local_53_register_post_types');

// Register Taxonomies
function ibew_local_53_register_taxonomies() {
    // News Categories
    register_taxonomy('news_category', 'news', array(
        'labels' => array(
            'name' => __('News Categories', 'ibew-local-53'),
            'singular_name' => __('News Category', 'ibew-local-53'),
        ),
        'hierarchical' => true,
        'public' => true,
        'rewrite' => array('slug' => 'news-category'),
        'show_in_rest' => true,
    ));

    // Event Categories
    register_taxonomy('event_category', 'event', array(
        'labels' => array(
            'name' => __('Event Categories', 'ibew-local-53'),
            'singular_name' => __('Event Category', 'ibew-local-53'),
        ),
        'hierarchical' => true,
        'public' => true,
        'rewrite' => array('slug' => 'event-category'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'ibew_local_53_register_taxonomies');

// Register Event Meta Fields
function ibew_local_53_register_event_meta() {
    register_post_meta('event', 'event_start_datetime', array(
        'type' => 'string',
        'description' => 'Event start date and time',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
    ));

    register_post_meta('event', 'event_end_datetime', array(
        'type' => 'string',
        'description' => 'Event end date and time',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
    ));

    register_post_meta('event', 'event_all_day', array(
        'type' => 'boolean',
        'description' => 'Whether the event is all day',
        'single' => true,
        'show_in_rest' => true,
        'default' => false,
    ));

    register_post_meta('event', 'event_location', array(
        'type' => 'string',
        'description' => 'Event location',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
    ));

    register_post_meta('event', 'event_cta_label', array(
        'type' => 'string',
        'description' => 'Event CTA button label',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
    ));

    register_post_meta('event', 'event_cta_url', array(
        'type' => 'string',
        'description' => 'Event CTA button URL',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'esc_url_raw',
    ));
}
add_action('init', 'ibew_local_53_register_event_meta');

// Add Event Meta Box
function ibew_local_53_add_event_meta_box() {
    add_meta_box(
        'ibew_event_meta',
        __('Event Details', 'ibew-local-53'),
        'ibew_local_53_event_meta_box_callback',
        'event',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'ibew_local_53_add_event_meta_box');

// Event Meta Box Callback
function ibew_local_53_event_meta_box_callback($post) {
    wp_nonce_field('ibew_event_meta_box', 'ibew_event_meta_box_nonce');
    
    $start_datetime = get_post_meta($post->ID, 'event_start_datetime', true);
    $end_datetime = get_post_meta($post->ID, 'event_end_datetime', true);
    $all_day = get_post_meta($post->ID, 'event_all_day', true);
    $location = get_post_meta($post->ID, 'event_location', true);
    $cta_label = get_post_meta($post->ID, 'event_cta_label', true);
    $cta_url = get_post_meta($post->ID, 'event_cta_url', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="event_start_datetime"><?php _e('Start Date & Time', 'ibew-local-53'); ?> <span style="color:red;">*</span></label></th>
            <td>
                <input type="datetime-local" id="event_start_datetime" name="event_start_datetime" value="<?php echo esc_attr($start_datetime); ?>" required />
                <p class="description"><?php _e('Required. Format: YYYY-MM-DDTHH:MM', 'ibew-local-53'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="event_end_datetime"><?php _e('End Date & Time', 'ibew-local-53'); ?></label></th>
            <td>
                <input type="datetime-local" id="event_end_datetime" name="event_end_datetime" value="<?php echo esc_attr($end_datetime); ?>" />
                <p class="description"><?php _e('Optional. Format: YYYY-MM-DDTHH:MM', 'ibew-local-53'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="event_all_day"><?php _e('All Day Event', 'ibew-local-53'); ?></label></th>
            <td>
                <input type="checkbox" id="event_all_day" name="event_all_day" value="1" <?php checked($all_day, 1); ?> />
                <label for="event_all_day"><?php _e('This event lasts all day', 'ibew-local-53'); ?></label>
            </td>
        </tr>
        <tr>
            <th><label for="event_location"><?php _e('Location', 'ibew-local-53'); ?></label></th>
            <td>
                <input type="text" id="event_location" name="event_location" value="<?php echo esc_attr($location); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th><label for="event_cta_label"><?php _e('CTA Button Label', 'ibew-local-53'); ?></label></th>
            <td>
                <input type="text" id="event_cta_label" name="event_cta_label" value="<?php echo esc_attr($cta_label); ?>" class="regular-text" placeholder="e.g., Event Details, RSVP Now" />
            </td>
        </tr>
        <tr>
            <th><label for="event_cta_url"><?php _e('CTA Button URL', 'ibew-local-53'); ?></label></th>
            <td>
                <input type="url" id="event_cta_url" name="event_cta_url" value="<?php echo esc_attr($cta_url); ?>" class="regular-text" />
            </td>
        </tr>
    </table>
    <?php
}

// Save Event Meta
function ibew_local_53_save_event_meta($post_id) {
    // Only process for event post type
    if (get_post_type($post_id) !== 'event') {
        return;
    }

    if (!isset($_POST['ibew_event_meta_box_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['ibew_event_meta_box_nonce'], 'ibew_event_meta_box')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Validate and save start datetime (required)
    if (isset($_POST['event_start_datetime']) && !empty($_POST['event_start_datetime'])) {
        $start_datetime = sanitize_text_field($_POST['event_start_datetime']);
        // Convert datetime-local format (YYYY-MM-DDTHH:MM) to a format that can be compared
        update_post_meta($post_id, 'event_start_datetime', $start_datetime);
    } else {
        // If start datetime is empty, set an error
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>Event Start Date & Time is required. Please set a date for this event.</p></div>';
        });
    }

    if (isset($_POST['event_end_datetime']) && !empty($_POST['event_end_datetime'])) {
        update_post_meta($post_id, 'event_end_datetime', sanitize_text_field($_POST['event_end_datetime']));
    } else {
        delete_post_meta($post_id, 'event_end_datetime');
    }

    if (isset($_POST['event_all_day'])) {
        update_post_meta($post_id, 'event_all_day', 1);
    } else {
        update_post_meta($post_id, 'event_all_day', 0);
    }

    if (isset($_POST['event_location'])) {
        update_post_meta($post_id, 'event_location', sanitize_text_field($_POST['event_location']));
    } else {
        delete_post_meta($post_id, 'event_location');
    }

    if (isset($_POST['event_cta_label'])) {
        update_post_meta($post_id, 'event_cta_label', sanitize_text_field($_POST['event_cta_label']));
    } else {
        delete_post_meta($post_id, 'event_cta_label');
    }

    if (isset($_POST['event_cta_url'])) {
        update_post_meta($post_id, 'event_cta_url', esc_url_raw($_POST['event_cta_url']));
    } else {
        delete_post_meta($post_id, 'event_cta_url');
    }
}
add_action('save_post', 'ibew_local_53_save_event_meta');

// Contact Form Handler
function ibew_local_53_handle_contact_form() {
    if (!isset($_POST['ibew_contact_nonce']) || !wp_verify_nonce($_POST['ibew_contact_nonce'], 'ibew_contact_form')) {
        wp_die('Security check failed');
    }

    // Honeypot check
    if (!empty($_POST['website'])) {
        wp_die('Spam detected');
    }

    // Time-based spam check (form must be submitted at least 3 seconds after page load)
    if (isset($_POST['form_time']) && time() - intval($_POST['form_time']) < 3) {
        wp_die('Please wait a moment before submitting');
    }

    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $topic = sanitize_text_field($_POST['topic']);
    $message = sanitize_textarea_field($_POST['message']);

    if (empty($first_name) || empty($last_name) || empty($email) || empty($message)) {
        wp_redirect(add_query_arg('contact', 'error', wp_get_referer()));
        exit;
    }

    $to = get_option('admin_email');
    $subject = 'Contact Form Submission: ' . $topic;
    $body = "Name: $first_name $last_name\n";
    $body .= "Email: $email\n";
    $body .= "Phone: $phone\n";
    $body .= "Topic: $topic\n\n";
    $body .= "Message:\n$message";

    $headers = array('Content-Type: text/plain; charset=UTF-8', 'From: ' . $email);

    if (wp_mail($to, $subject, $body, $headers)) {
        wp_redirect(add_query_arg('contact', 'success', wp_get_referer()));
    } else {
        wp_redirect(add_query_arg('contact', 'error', wp_get_referer()));
    }
    exit;
}
add_action('admin_post_ibew_contact_form', 'ibew_local_53_handle_contact_form');
add_action('admin_post_nopriv_ibew_contact_form', 'ibew_local_53_handle_contact_form');

// Helper function to format event date
function ibew_local_53_format_event_date($datetime, $format = 'F j, Y') {
    if (empty($datetime)) {
        return '';
    }
    $timestamp = strtotime($datetime);
    return date($format, $timestamp);
}

// Helper function to format event time
function ibew_local_53_format_event_time($datetime) {
    if (empty($datetime)) {
        return '';
    }
    return date('g:i A', strtotime($datetime));
}

// Helper function to convert datetime-local format to timestamp for comparison
function ibew_local_53_get_event_timestamp($datetime) {
    if (empty($datetime)) {
        return 0;
    }
    // Handle datetime-local format (YYYY-MM-DDTHH:MM)
    $datetime = str_replace('T', ' ', $datetime);
    // Add seconds if not present
    if (strlen($datetime) === 16) {
        $datetime .= ':00';
    }
    return strtotime($datetime);
}

// Enqueue admin scripts for event date picker
function ibew_local_53_event_admin_scripts($hook) {
    global $post_type;
    if ($post_type === 'event') {
        wp_enqueue_script('jquery');
    }
}
add_action('admin_enqueue_scripts', 'ibew_local_53_event_admin_scripts');

// Modify the main query for event archives to support pagination
function ibew_local_53_modify_event_archive_query($query) {
    // Only modify the main query on the frontend for event archives
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('event')) {
        $query->set('posts_per_page', 4);
        $query->set('meta_key', 'event_start_datetime');
        $query->set('orderby', 'meta_value');
        $query->set('order', 'ASC');
        
        // Handle category filter
        if (isset($_GET['event_category']) && !empty($_GET['event_category'])) {
            $query->set('tax_query', array(
                array(
                    'taxonomy' => 'event_category',
                    'field' => 'slug',
                    'terms' => sanitize_text_field($_GET['event_category']),
                ),
            ));
        }
    }
    
    // Modify the main query for news archives
    // Note: The archive-news.php template uses a custom query with offset for pagination
    // Page 1: Featured story + 6 articles, Page 2+: 9 articles
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('news')) {
        $paged = isset($_GET['pg']) ? max(1, intval($_GET['pg'])) : 1;
        
        if ($paged === 1) {
            $query->set('posts_per_page', 6);
        } else {
            $query->set('posts_per_page', 9);
        }
        
        $query->set('orderby', 'date');
        $query->set('order', 'DESC');
        
        // Handle category filter
        if (isset($_GET['news_category']) && !empty($_GET['news_category'])) {
            $query->set('tax_query', array(
                array(
                    'taxonomy' => 'news_category',
                    'field' => 'slug',
                    'terms' => sanitize_text_field($_GET['news_category']),
                ),
            ));
        }
    }
}
add_action('pre_get_posts', 'ibew_local_53_modify_event_archive_query');

// Pass AJAX URL to frontend
function ibew_local_53_enqueue_pagination_scripts() {
    wp_localize_script('ibew-local-53-main', 'ibewAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ibew_pagination_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'ibew_local_53_enqueue_pagination_scripts');

// ============================================
// RESOURCES POST TYPE & FUNCTIONALITY
// ============================================

// Register Resource Post Type
function ibew_local_53_register_resource_post_type() {
    register_post_type('resource', array(
        'labels' => array(
            'name' => __('Resources', 'ibew-local-53'),
            'singular_name' => __('Resource', 'ibew-local-53'),
            'add_new' => __('Add New', 'ibew-local-53'),
            'add_new_item' => __('Add New Resource', 'ibew-local-53'),
            'edit_item' => __('Edit Resource', 'ibew-local-53'),
            'new_item' => __('New Resource', 'ibew-local-53'),
            'view_item' => __('View Resource', 'ibew-local-53'),
            'search_items' => __('Search Resources', 'ibew-local-53'),
            'not_found' => __('No resources found', 'ibew-local-53'),
            'not_found_in_trash' => __('No resources found in Trash', 'ibew-local-53'),
            'menu_name' => __('Resources', 'ibew-local-53'),
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'resources'),
        'supports' => array('title'),
        'menu_icon' => 'dashicons-media-document',
        'show_in_rest' => true,
    ));

    // Register External Link Post Type
    register_post_type('external_link', array(
        'labels' => array(
            'name' => __('External Links', 'ibew-local-53'),
            'singular_name' => __('External Link', 'ibew-local-53'),
            'add_new' => __('Add New', 'ibew-local-53'),
            'add_new_item' => __('Add New External Link', 'ibew-local-53'),
            'edit_item' => __('Edit External Link', 'ibew-local-53'),
            'new_item' => __('New External Link', 'ibew-local-53'),
            'view_item' => __('View External Link', 'ibew-local-53'),
            'search_items' => __('Search External Links', 'ibew-local-53'),
            'not_found' => __('No external links found', 'ibew-local-53'),
            'not_found_in_trash' => __('No external links found in Trash', 'ibew-local-53'),
            'menu_name' => __('External Links', 'ibew-local-53'),
        ),
        'public' => true,
        'has_archive' => false,
        'rewrite' => array('slug' => 'external-links'),
        'supports' => array('title'),
        'menu_icon' => 'dashicons-admin-links',
        'show_in_rest' => true,
    ));
}
add_action('init', 'ibew_local_53_register_resource_post_type');

// Register Resource Categories Taxonomy
function ibew_local_53_register_resource_taxonomy() {
    register_taxonomy('resource_category', 'resource', array(
        'labels' => array(
            'name' => __('Resource Categories', 'ibew-local-53'),
            'singular_name' => __('Resource Category', 'ibew-local-53'),
            'search_items' => __('Search Categories', 'ibew-local-53'),
            'all_items' => __('All Categories', 'ibew-local-53'),
            'parent_item' => __('Parent Category', 'ibew-local-53'),
            'parent_item_colon' => __('Parent Category:', 'ibew-local-53'),
            'edit_item' => __('Edit Category', 'ibew-local-53'),
            'update_item' => __('Update Category', 'ibew-local-53'),
            'add_new_item' => __('Add New Category', 'ibew-local-53'),
            'new_item_name' => __('New Category Name', 'ibew-local-53'),
            'menu_name' => __('Categories', 'ibew-local-53'),
        ),
        'hierarchical' => true,
        'public' => true,
        'rewrite' => array('slug' => 'resource-category'),
        'show_in_rest' => true,
        'show_admin_column' => true,
    ));
}
add_action('init', 'ibew_local_53_register_resource_taxonomy');

// Add Resource Meta Box
function ibew_local_53_add_resource_meta_box() {
    add_meta_box(
        'ibew_resource_meta',
        __('Resource File', 'ibew-local-53'),
        'ibew_local_53_resource_meta_box_callback',
        'resource',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'ibew_local_53_add_resource_meta_box');

// Resource Meta Box Callback
function ibew_local_53_resource_meta_box_callback($post) {
    wp_nonce_field('ibew_resource_meta_box', 'ibew_resource_meta_box_nonce');
    
    $file_id = get_post_meta($post->ID, 'resource_file_id', true);
    $file_url = $file_id ? wp_get_attachment_url($file_id) : '';
    $file_name = $file_id ? basename(get_attached_file($file_id)) : '';
    
    // Get file metadata
    $file_size = '';
    $file_type = '';
    if ($file_id) {
        $file_path = get_attached_file($file_id);
        if (file_exists($file_path)) {
            $size_bytes = filesize($file_path);
            if ($size_bytes >= 1048576) {
                $file_size = number_format($size_bytes / 1048576, 1) . ' MB';
            } else {
                $file_size = number_format($size_bytes / 1024, 0) . ' KB';
            }
        }
        $file_type = strtoupper(pathinfo($file_path, PATHINFO_EXTENSION));
    }
    ?>
    <div class="resource-file-upload">
        <input type="hidden" id="resource_file_id" name="resource_file_id" value="<?php echo esc_attr($file_id); ?>" />
        
        <div id="resource-file-preview" style="margin-bottom: 15px; <?php echo $file_id ? '' : 'display:none;'; ?>">
            <div style="background: #f0f0f1; padding: 15px; border-radius: 4px; display: flex; align-items: center; gap: 15px;">
                <span class="dashicons dashicons-pdf" style="font-size: 36px; width: 36px; height: 36px; color: #c82e39;"></span>
                <div>
                    <strong id="resource-file-name"><?php echo esc_html($file_name); ?></strong>
                    <br>
                    <span id="resource-file-info" style="color: #666; font-size: 12px;">
                        <?php echo esc_html($file_type . ($file_size ? ' • ' . $file_size : '')); ?>
                    </span>
                </div>
            </div>
        </div>
        
        <button type="button" class="button button-primary" id="upload-resource-btn">
            <?php echo $file_id ? __('Change File', 'ibew-local-53') : __('Upload File', 'ibew-local-53'); ?>
        </button>
        <button type="button" class="button" id="remove-resource-btn" style="<?php echo $file_id ? '' : 'display:none;'; ?>">
            <?php _e('Remove File', 'ibew-local-53'); ?>
        </button>
        
        <p class="description" style="margin-top: 10px;">
            <?php _e('Upload a PDF, DOC, DOCX, XLS, XLSX, or other document file.', 'ibew-local-53'); ?>
        </p>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        var mediaUploader;
        
        $('#upload-resource-btn').on('click', function(e) {
            e.preventDefault();
            
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            
            mediaUploader = wp.media({
                title: '<?php _e('Select or Upload a Document', 'ibew-local-53'); ?>',
                button: {
                    text: '<?php _e('Use this file', 'ibew-local-53'); ?>'
                },
                multiple: false,
                library: {
                    type: ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
                }
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#resource_file_id').val(attachment.id);
                $('#resource-file-name').text(attachment.filename);
                
                var fileSize = '';
                if (attachment.filesizeInBytes >= 1048576) {
                    fileSize = (attachment.filesizeInBytes / 1048576).toFixed(1) + ' MB';
                } else {
                    fileSize = Math.round(attachment.filesizeInBytes / 1024) + ' KB';
                }
                var fileExt = attachment.filename.split('.').pop().toUpperCase();
                $('#resource-file-info').text(fileExt + (fileSize ? ' • ' + fileSize : ''));
                
                $('#resource-file-preview').show();
                $('#remove-resource-btn').show();
                $('#upload-resource-btn').text('<?php _e('Change File', 'ibew-local-53'); ?>');
            });
            
            mediaUploader.open();
        });
        
        $('#remove-resource-btn').on('click', function(e) {
            e.preventDefault();
            $('#resource_file_id').val('');
            $('#resource-file-preview').hide();
            $(this).hide();
            $('#upload-resource-btn').text('<?php _e('Upload File', 'ibew-local-53'); ?>');
        });
    });
    </script>
    <?php
}

// Save Resource Meta
function ibew_local_53_save_resource_meta($post_id) {
    if (get_post_type($post_id) !== 'resource') {
        return;
    }

    if (!isset($_POST['ibew_resource_meta_box_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['ibew_resource_meta_box_nonce'], 'ibew_resource_meta_box')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['resource_file_id'])) {
        $file_id = absint($_POST['resource_file_id']);
        if ($file_id) {
            update_post_meta($post_id, 'resource_file_id', $file_id);
        } else {
            delete_post_meta($post_id, 'resource_file_id');
        }
    }
}
add_action('save_post', 'ibew_local_53_save_resource_meta');

// Add External Link Meta Box
function ibew_local_53_add_external_link_meta_box() {
    add_meta_box(
        'ibew_external_link_meta',
        __('Link Details', 'ibew-local-53'),
        'ibew_local_53_external_link_meta_box_callback',
        'external_link',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'ibew_local_53_add_external_link_meta_box');

// External Link Meta Box Callback
function ibew_local_53_external_link_meta_box_callback($post) {
    wp_nonce_field('ibew_external_link_meta_box', 'ibew_external_link_meta_box_nonce');
    
    $link_url = get_post_meta($post->ID, 'external_link_url', true);
    $link_order = get_post_meta($post->ID, 'external_link_order', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="external_link_url"><?php _e('Link URL', 'ibew-local-53'); ?> <span style="color:red;">*</span></label></th>
            <td>
                <input type="url" id="external_link_url" name="external_link_url" value="<?php echo esc_attr($link_url); ?>" class="regular-text" required placeholder="https://example.com" />
                <p class="description"><?php _e('Enter the full URL including https://', 'ibew-local-53'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="external_link_order"><?php _e('Display Order', 'ibew-local-53'); ?></label></th>
            <td>
                <input type="number" id="external_link_order" name="external_link_order" value="<?php echo esc_attr($link_order ?: 0); ?>" class="small-text" min="0" />
                <p class="description"><?php _e('Lower numbers appear first. Links with same order are sorted alphabetically.', 'ibew-local-53'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

// Save External Link Meta
function ibew_local_53_save_external_link_meta($post_id) {
    if (get_post_type($post_id) !== 'external_link') {
        return;
    }

    if (!isset($_POST['ibew_external_link_meta_box_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['ibew_external_link_meta_box_nonce'], 'ibew_external_link_meta_box')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['external_link_url'])) {
        update_post_meta($post_id, 'external_link_url', esc_url_raw($_POST['external_link_url']));
    }

    if (isset($_POST['external_link_order'])) {
        update_post_meta($post_id, 'external_link_order', absint($_POST['external_link_order']));
    }
}
add_action('save_post', 'ibew_local_53_save_external_link_meta');

// Enqueue media uploader for resources
function ibew_local_53_enqueue_resource_admin_scripts($hook) {
    global $post_type;
    if ($post_type === 'resource' && in_array($hook, array('post.php', 'post-new.php'))) {
        wp_enqueue_media();
    }
}
add_action('admin_enqueue_scripts', 'ibew_local_53_enqueue_resource_admin_scripts');

// Helper function to get resource file info
function ibew_local_53_get_resource_file_info($post_id) {
    $file_id = get_post_meta($post_id, 'resource_file_id', true);
    if (!$file_id) {
        return null;
    }
    
    $file_path = get_attached_file($file_id);
    $file_url = wp_get_attachment_url($file_id);
    
    if (!$file_path || !file_exists($file_path)) {
        return null;
    }
    
    $size_bytes = filesize($file_path);
    if ($size_bytes >= 1048576) {
        $file_size = number_format($size_bytes / 1048576, 1) . ' MB';
    } else {
        $file_size = number_format($size_bytes / 1024, 0) . ' KB';
    }
    
    $file_type = strtoupper(pathinfo($file_path, PATHINFO_EXTENSION));
    $file_name = basename($file_path);
    
    // Get the post modified date for "Updated" date
    $post = get_post($post_id);
    $updated_date = date('M j, Y', strtotime($post->post_modified));
    
    return array(
        'id' => $file_id,
        'url' => $file_url,
        'path' => $file_path,
        'name' => $file_name,
        'size' => $file_size,
        'type' => $file_type,
        'updated' => $updated_date,
    );
}

// Add custom columns to Resource admin list
function ibew_local_53_resource_admin_columns($columns) {
    $new_columns = array();
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'title') {
            $new_columns['resource_file'] = __('File', 'ibew-local-53');
        }
    }
    return $new_columns;
}
add_filter('manage_resource_posts_columns', 'ibew_local_53_resource_admin_columns');

// Populate custom columns for Resource
function ibew_local_53_resource_admin_column_content($column, $post_id) {
    if ($column === 'resource_file') {
        $file_info = ibew_local_53_get_resource_file_info($post_id);
        if ($file_info) {
            echo '<span style="color: #666;">' . esc_html($file_info['type']) . ' • ' . esc_html($file_info['size']) . '</span>';
        } else {
            echo '<span style="color: #999;">—</span>';
        }
    }
}
add_action('manage_resource_posts_custom_column', 'ibew_local_53_resource_admin_column_content', 10, 2);

// Add custom columns to External Link admin list
function ibew_local_53_external_link_admin_columns($columns) {
    $new_columns = array();
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'title') {
            $new_columns['link_url'] = __('URL', 'ibew-local-53');
            $new_columns['link_order'] = __('Order', 'ibew-local-53');
        }
    }
    return $new_columns;
}
add_filter('manage_external_link_posts_columns', 'ibew_local_53_external_link_admin_columns');

// Populate custom columns for External Link
function ibew_local_53_external_link_admin_column_content($column, $post_id) {
    if ($column === 'link_url') {
        $url = get_post_meta($post_id, 'external_link_url', true);
        if ($url) {
            echo '<a href="' . esc_url($url) . '" target="_blank">' . esc_html($url) . '</a>';
        } else {
            echo '<span style="color: #999;">—</span>';
        }
    }
    if ($column === 'link_order') {
        $order = get_post_meta($post_id, 'external_link_order', true);
        echo esc_html($order ?: 0);
    }
}
add_action('manage_external_link_posts_custom_column', 'ibew_local_53_external_link_admin_column_content', 10, 2);

// Make External Link order column sortable
function ibew_local_53_external_link_sortable_columns($columns) {
    $columns['link_order'] = 'link_order';
    return $columns;
}
add_filter('manage_edit-external_link_sortable_columns', 'ibew_local_53_external_link_sortable_columns');

// Handle sorting by link order
function ibew_local_53_external_link_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }
    
    if ($query->get('orderby') === 'link_order') {
        $query->set('meta_key', 'external_link_order');
        $query->set('orderby', 'meta_value_num');
    }
}
add_action('pre_get_posts', 'ibew_local_53_external_link_orderby');
