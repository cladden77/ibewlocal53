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
