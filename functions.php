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
    
    // Block editor support: wide alignment, block styles, editor styles
    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');
    add_theme_support('editor-styles');
    add_editor_style(array('style.css', 'assets/css/main.css'));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'ibew-local-53'),
        'member_primary' => __('Member Primary Menu', 'ibew-local-53'),
    ));
    
    // Register custom 16:12 image size for news and events
    add_image_size('featured-16-12', 1280, 960, true);
}
add_action('after_setup_theme', 'ibew_local_53_setup');

/**
 * Resolve which header menu location to render.
 *
 * Logged-in users get `member_primary` when assigned; everyone else falls back
 * to the default `primary` location.
 *
 * @return string
 */
function ibew_local_53_get_header_menu_location() {
    if (is_user_logged_in() && has_nav_menu('member_primary')) {
        return 'member_primary';
    }
    return 'primary';
}

// Register block pattern category and patterns for page layouts
function ibew_local_53_register_block_patterns() {
    register_block_pattern_category('ibew-layouts', array(
        'label'       => __('IBEW Layouts', 'ibew-local-53'),
        'description' => __('Two-column and layout blocks for building pages.', 'ibew-local-53'),
    ));
    
    $patterns = array(
        'two-column-text-left-image-right' => array(
            'title'       => __('Two Column: Text Left, Image Right', 'ibew-local-53'),
            'description' => __('Heading and body text on the left, image on the right.', 'ibew-local-53'),
        ),
        'two-column-image-left-text-right' => array(
            'title'       => __('Two Column: Image Left, Text Right', 'ibew-local-53'),
            'description' => __('Image on the left, heading and body text on the right.', 'ibew-local-53'),
        ),
        'two-column-equal' => array(
            'title'       => __('Two Column: Equal Columns', 'ibew-local-53'),
            'description' => __('Two equal columns for text or mixed content.', 'ibew-local-53'),
        ),
    );
    
    foreach ($patterns as $slug => $props) {
        $path = get_theme_file_path('patterns/' . $slug . '.php');
        if (file_exists($path)) {
            ob_start();
            include $path;
            $content = ob_get_clean();
            if ($content) {
                register_block_pattern('ibew-local-53/' . $slug, array_merge($props, array(
                    'content'    => $content,
                    'categories' => array('ibew-layouts'),
                )));
            }
        }
    }
}
add_action('init', 'ibew_local_53_register_block_patterns');

// ============================================
// PAGE HERO OPTIONS (default page template)
// ============================================

function ibew_local_53_register_page_hero_meta() {
    register_post_meta('page', 'ibew_hero_type', array(
        'type'         => 'string',
        'default'      => 'none',
        'single'       => true,
        'show_in_rest' => true,
        'sanitize_callback' => function ($val) {
            return in_array($val, array('none', 'resources', 'events'), true) ? $val : 'none';
        },
    ));
    register_post_meta('page', 'ibew_hero_title', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
    ));
    register_post_meta('page', 'ibew_hero_subtext', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    register_post_meta('page', 'ibew_hero_pill', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
    ));
}
add_action('init', 'ibew_local_53_register_page_hero_meta');

function ibew_local_53_add_page_hero_meta_box() {
    add_meta_box(
        'ibew_page_hero',
        __('Page Hero', 'ibew-local-53'),
        'ibew_local_53_page_hero_meta_box_callback',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'ibew_local_53_add_page_hero_meta_box');

function ibew_local_53_page_hero_meta_box_callback($post) {
    wp_nonce_field('ibew_page_hero_nonce', 'ibew_page_hero_nonce');
    $hero_type   = get_post_meta($post->ID, 'ibew_hero_type', true) ?: 'none';
    $hero_title  = get_post_meta($post->ID, 'ibew_hero_title', true);
    $hero_subtext = get_post_meta($post->ID, 'ibew_hero_subtext', true);
    $hero_pill   = get_post_meta($post->ID, 'ibew_hero_pill', true);
    $page_title  = $post->post_title;
    ?>
    <p class="description" style="margin-bottom: 14px;"><?php esc_html_e('Choose a hero style for this page and optionally override the title and subtext. Leave hero title blank to use the page title.', 'ibew-local-53'); ?></p>
    <table class="form-table">
        <tr>
            <th scope="row"><label for="ibew_hero_type"><?php esc_html_e('Hero type', 'ibew-local-53'); ?></label></th>
            <td>
                <select name="ibew_hero_type" id="ibew_hero_type">
                    <option value="none" <?php selected($hero_type, 'none'); ?>><?php esc_html_e('None (default page title only)', 'ibew-local-53'); ?></option>
                    <option value="resources" <?php selected($hero_type, 'resources'); ?>><?php esc_html_e('Resources style (full-width gradient, left-aligned)', 'ibew-local-53'); ?></option>
                    <option value="events" <?php selected($hero_type, 'events'); ?>><?php esc_html_e('Events / News style (centered card with optional pill)', 'ibew-local-53'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="ibew_hero_title"><?php esc_html_e('Hero title', 'ibew-local-53'); ?></label></th>
            <td>
                <input type="text" name="ibew_hero_title" id="ibew_hero_title" value="<?php echo esc_attr($hero_title); ?>" class="large-text" placeholder="<?php echo esc_attr($page_title); ?>" />
                <p class="description"><?php esc_html_e('Leave blank to use the page title.', 'ibew-local-53'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="ibew_hero_subtext"><?php esc_html_e('Hero subtext', 'ibew-local-53'); ?></label></th>
            <td>
                <textarea name="ibew_hero_subtext" id="ibew_hero_subtext" rows="3" class="large-text"><?php echo esc_textarea($hero_subtext); ?></textarea>
            </td>
        </tr>
        <tr id="ibew_hero_pill_row" style="<?php echo $hero_type !== 'events' ? 'display:none;' : ''; ?>">
            <th scope="row"><label for="ibew_hero_pill"><?php esc_html_e('Pill text', 'ibew-local-53'); ?></label></th>
            <td>
                <input type="text" name="ibew_hero_pill" id="ibew_hero_pill" value="<?php echo esc_attr($hero_pill); ?>" class="regular-text" />
                <p class="description"><?php esc_html_e('Optional. Shown above the title (Events/News style only).', 'ibew-local-53'); ?></p>
            </td>
        </tr>
    </table>
    <script>
    jQuery(function($) {
        $('#ibew_hero_type').on('change', function() {
            var v = $(this).val();
            $('#ibew_hero_pill_row').toggle(v === 'events');
        });
    });
    </script>
    <?php
}

function ibew_local_53_save_page_hero_meta($post_id) {
    if (!isset($_POST['ibew_page_hero_nonce']) || !wp_verify_nonce($_POST['ibew_page_hero_nonce'], 'ibew_page_hero_nonce')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id) || get_post_type($post_id) !== 'page') {
        return;
    }
    if (isset($_POST['ibew_hero_type'])) {
        $val = sanitize_text_field($_POST['ibew_hero_type']);
        update_post_meta($post_id, 'ibew_hero_type', in_array($val, array('none', 'resources', 'events'), true) ? $val : 'none');
    }
    if (isset($_POST['ibew_hero_title'])) {
        update_post_meta($post_id, 'ibew_hero_title', sanitize_text_field($_POST['ibew_hero_title']));
    }
    if (isset($_POST['ibew_hero_subtext'])) {
        update_post_meta($post_id, 'ibew_hero_subtext', sanitize_textarea_field($_POST['ibew_hero_subtext']));
    }
    if (isset($_POST['ibew_hero_pill'])) {
        update_post_meta($post_id, 'ibew_hero_pill', sanitize_text_field($_POST['ibew_hero_pill']));
    }
}
add_action('save_post_page', 'ibew_local_53_save_page_hero_meta');

// Enqueue styles and scripts
function ibew_local_53_scripts() {
    // Enqueue Inter font
    wp_enqueue_style('inter-font', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap', array(), null);
    // Enqueue Material Icons
    wp_enqueue_style('material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons', array(), null);
    wp_enqueue_style('ibew-local-53-style', get_stylesheet_uri(), array(), '1.0.0');
    wp_enqueue_style('ibew-local-53-main', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.2');
    wp_enqueue_script('ibew-local-53-main', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.2', true);
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

/**
 * Map event category term to a CSS class for list/card styling.
 *
 * @param WP_Term $category Event category term.
 * @return string
 */
function ibew_get_event_category_class( $category ) {
    $name_lower = strtolower( $category->name );
    $slug        = $category->slug;

    if ( strpos( $name_lower, 'union' ) !== false && strpos( $name_lower, 'meeting' ) !== false ) {
        return 'union-meetings';
    }
    if ( strpos( $name_lower, 'social' ) !== false && strpos( $name_lower, 'event' ) !== false ) {
        return 'social-events';
    }
    if ( strpos( $name_lower, 'training' ) !== false || strpos( $name_lower, 'safety' ) !== false ) {
        return 'training-safety';
    }
    if ( strpos( $name_lower, 'holiday' ) !== false ) {
        return 'holiday';
    }

    if ( strpos( $slug, 'union' ) !== false ) {
        return 'union-meetings';
    }
    if ( strpos( $slug, 'social' ) !== false ) {
        return 'social-events';
    }
    if ( strpos( $slug, 'training' ) !== false || strpos( $slug, 'safety' ) !== false ) {
        return 'training-safety';
    }
    if ( strpos( $slug, 'holiday' ) !== false ) {
        return 'holiday';
    }

    $additional_colors = array( 'category-5', 'category-6', 'category-7', 'category-8', 'category-9', 'category-10' );
    $hash              = crc32( $slug );
    $color_index       = abs( $hash ) % count( $additional_colors );

    return $additional_colors[ $color_index ];
}

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

/**
 * Member Register page: create user from First Name, Last Name, Email (no levels redirect).
 */
function ibew_local_53_handle_member_register_form() {
    if (!isset($_POST['ibew_member_register_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ibew_member_register_nonce'])), 'ibew_member_register')) {
        wp_die(esc_html__('Security check failed.', 'ibew-local-53'), '', array('response' => 403));
    }

    if (!empty($_POST['website'])) {
        wp_die(esc_html__('Spam detected.', 'ibew-local-53'), '', array('response' => 400));
    }

    if (isset($_POST['form_time']) && time() - intval($_POST['form_time']) < 3) {
        wp_die(esc_html__('Please wait a moment before submitting.', 'ibew-local-53'), '', array('response' => 429));
    }

    $page_id = isset($_POST['ibew_register_page_id']) ? absint($_POST['ibew_register_page_id']) : 0;
    $redirect_base = home_url('/');
    if ($page_id && get_post_meta($page_id, '_wp_page_template', true) === 'page-member-register.php') {
        $redirect_base = get_permalink($page_id);
    }

    $fail = function ($code) use ($redirect_base) {
        wp_safe_redirect(add_query_arg('ibew_reg', $code, $redirect_base));
        exit;
    };

    if (!get_option('users_can_register')) {
        $fail('closed');
    }

    if (is_user_logged_in()) {
        $fail('logged_in');
    }

    $first_name = isset($_POST['ibew_reg_first_name']) ? sanitize_text_field(wp_unslash($_POST['ibew_reg_first_name'])) : '';
    $last_name = isset($_POST['ibew_reg_last_name']) ? sanitize_text_field(wp_unslash($_POST['ibew_reg_last_name'])) : '';
    $email = isset($_POST['ibew_reg_email']) ? sanitize_email(wp_unslash($_POST['ibew_reg_email'])) : '';

    if ($first_name === '' || $last_name === '' || $email === '' || !is_email($email)) {
        $fail('invalid');
    }

    if (email_exists($email)) {
        $fail('duplicate');
    }

    $base = sanitize_user(strtolower($first_name . $last_name), true);
    if ($base === '') {
        $email_local = explode('@', $email);
        $local_part = isset($email_local[0]) ? $email_local[0] : 'user';
        $base = sanitize_user($local_part, true);
    }
    if ($base === '') {
        $base = 'member';
    }

    $user_login = $base;
    $n = 1;
    while (username_exists($user_login)) {
        $user_login = $base . $n;
        $n++;
    }

    $password = wp_generate_password(24);
    $user_id = wp_insert_user(array(
        'user_login'   => $user_login,
        'user_email'   => $email,
        'user_pass'    => $password,
        'first_name'   => $first_name,
        'last_name'    => $last_name,
        'display_name' => trim($first_name . ' ' . $last_name),
        'role'         => get_option('default_role'),
    ));

    if (is_wp_error($user_id)) {
        $fail('error');
    }

    if (apply_filters('ibew_local_53_hide_member_portal_users_from_users_list', true)) {
        update_user_meta($user_id, 'ibew_local_53_hide_wp_users_list', '1');
    }

    if (function_exists('wp_send_new_user_notifications')) {
        wp_send_new_user_notifications($user_id, 'user');
    } elseif (function_exists('wp_new_user_notification')) {
        wp_new_user_notification($user_id, null, 'user');
    }

    wp_safe_redirect(add_query_arg('ibew_reg', 'success', $redirect_base));
    exit;
}
add_action('admin_post_ibew_member_register', 'ibew_local_53_handle_member_register_form');
add_action('admin_post_nopriv_ibew_member_register', 'ibew_local_53_handle_member_register_form');

/**
 * User meta key: hide this user on Users → All Users (they still exist; PMPro Members list unchanged).
 */
function ibew_local_53_get_hide_wp_users_list_meta_key() {
    return 'ibew_local_53_hide_wp_users_list';
}

/**
 * Remove "hide from Users list" when the user can create/edit content (promoted beyond typical member).
 *
 * @param int $user_id User ID.
 */
function ibew_local_53_unhide_user_if_can_edit_posts($user_id) {
    $user_id = (int) $user_id;
    if ($user_id < 1) {
        return;
    }
    if (!user_can($user_id, 'edit_posts')) {
        return;
    }
    $key = ibew_local_53_get_hide_wp_users_list_meta_key();
    if (get_user_meta($user_id, $key, true)) {
        delete_user_meta($user_id, $key);
    }
}
add_action('profile_update', 'ibew_local_53_unhide_user_if_can_edit_posts', 10, 1);
add_action('set_user_role', 'ibew_local_53_unhide_user_if_can_edit_posts', 10, 1);

/**
 * Exclude portal-registered members from the Users admin screen only (not from PMPro SQL).
 *
 * @param WP_User_Query $user_query User query instance.
 */
function ibew_local_53_exclude_hidden_users_from_users_admin($user_query) {
    if (!is_admin()) {
        return;
    }
    global $pagenow;
    if ($pagenow !== 'users.php') {
        return;
    }
    if (!apply_filters('ibew_local_53_hide_member_portal_users_from_users_list', true)) {
        return;
    }
    if (isset($_GET['show_all_users']) && $_GET['show_all_users'] === '1' && current_user_can('list_users')) {
        return;
    }
    global $wpdb;
    $key = ibew_local_53_get_hide_wp_users_list_meta_key();
    $user_query->query_where .= $wpdb->prepare(
        " AND {$wpdb->users}.ID NOT IN (
            SELECT user_id FROM {$wpdb->usermeta}
            WHERE meta_key = %s AND meta_value = '1'
        )",
        $key
    );
}
add_action('pre_user_query', 'ibew_local_53_exclude_hidden_users_from_users_admin', 5);

/**
 * Explain hidden portal members on Users screen.
 */
function ibew_local_53_users_admin_hidden_members_notice() {
    global $pagenow;
    if ($pagenow !== 'users.php' || !current_user_can('list_users')) {
        return;
    }
    if (!apply_filters('ibew_local_53_hide_member_portal_users_from_users_list', true)) {
        return;
    }
    if (isset($_GET['show_all_users']) && $_GET['show_all_users'] === '1') {
        return;
    }
    echo '<div class="notice notice-info is-dismissible"><p>';
    esc_html_e('Members who registered through the Member Register form are omitted from this list but still appear in Paid Memberships Pro under Memberships → Members.', 'ibew-local-53');
    echo ' ';
    printf(
        '<a href="%s">%s</a>',
        esc_url(add_query_arg('show_all_users', '1')),
        esc_html__('Show every WordPress user', 'ibew-local-53')
    );
    echo '</p></div>';
}
add_action('admin_notices', 'ibew_local_53_users_admin_hidden_members_notice');

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
        // Keep resources queryable for WP_Query, but prevent archive URL collision with the Resources page.
        'has_archive' => false,
        'rewrite' => array('slug' => 'resource-item'),
        'supports' => array('title'),
        'menu_icon' => 'dashicons-portfolio',
        'show_in_rest' => true,
    ));
}
add_action('init', 'ibew_local_53_register_resource_post_type');

// Register Resource Categories Taxonomy
function ibew_local_53_register_resource_taxonomy() {
    register_taxonomy('resource_category', 'resource', array(
        'labels' => array(
            'name' => __('Document Categories', 'ibew-local-53'),
            'singular_name' => __('Document Category', 'ibew-local-53'),
            'search_items' => __('Search Categories', 'ibew-local-53'),
            'all_items' => __('All Categories', 'ibew-local-53'),
            'parent_item' => __('Parent Category', 'ibew-local-53'),
            'parent_item_colon' => __('Parent Category:', 'ibew-local-53'),
            'edit_item' => __('Edit Category', 'ibew-local-53'),
            'update_item' => __('Update Category', 'ibew-local-53'),
            'add_new_item' => __('Add New Category', 'ibew-local-53'),
            'new_item_name' => __('New Category Name', 'ibew-local-53'),
            'menu_name' => __('Document Categories', 'ibew-local-53'),
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
        __('Resource Details', 'ibew-local-53'),
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
    
    $resource_type = get_post_meta($post->ID, 'resource_type', true) ?: 'document';
    $file_id = get_post_meta($post->ID, 'resource_file_id', true);
    $link_url = get_post_meta($post->ID, 'resource_link_url', true);
    $display_order = get_post_meta($post->ID, 'resource_display_order', true);
    
    // Get file metadata if exists
    $file_name = '';
    $file_size = '';
    $file_type = '';
    if ($file_id) {
        $file_path = get_attached_file($file_id);
        $file_name = basename($file_path);
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
    <style>
        .resource-type-selector {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        .resource-type-option {
            flex: 1;
            position: relative;
        }
        .resource-type-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 2;
        }
        .resource-type-option label {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #fff;
        }
        .resource-type-option input[type="radio"]:checked + label {
            border-color: #2271b1;
            background: #f0f7fc;
        }
        .resource-type-option input[type="radio"]:focus + label {
            box-shadow: 0 0 0 2px #2271b1;
        }
        .resource-type-option .dashicons {
            font-size: 32px;
            width: 32px;
            height: 32px;
            margin-bottom: 8px;
            color: #646970;
        }
        .resource-type-option input[type="radio"]:checked + label .dashicons {
            color: #2271b1;
        }
        .resource-type-option .type-title {
            font-weight: 600;
            color: #1d2327;
            margin-bottom: 4px;
        }
        .resource-type-option .type-description {
            font-size: 12px;
            color: #646970;
            text-align: center;
        }
        .resource-fields-section {
            display: none;
            padding: 16px;
            background: #f9f9f9;
            border-radius: 8px;
            margin-bottom: 16px;
        }
        .resource-fields-section.active {
            display: block;
        }
        .resource-fields-section h4 {
            margin: 0 0 16px 0;
            padding-bottom: 8px;
            border-bottom: 1px solid #ddd;
        }
    </style>
    
    <!-- Resource Type Selector -->
    <div class="resource-type-selector">
        <div class="resource-type-option">
            <input type="radio" id="resource_type_document" name="resource_type" value="document" <?php checked($resource_type, 'document'); ?> />
            <label for="resource_type_document">
                <span class="dashicons dashicons-media-document"></span>
                <span class="type-title"><?php _e('Document', 'ibew-local-53'); ?></span>
                <span class="type-description"><?php _e('Upload a PDF, Word doc, or spreadsheet', 'ibew-local-53'); ?></span>
            </label>
        </div>
        <div class="resource-type-option">
            <input type="radio" id="resource_type_link" name="resource_type" value="external_link" <?php checked($resource_type, 'external_link'); ?> />
            <label for="resource_type_link">
                <span class="dashicons dashicons-admin-links"></span>
                <span class="type-title"><?php _e('External Link', 'ibew-local-53'); ?></span>
                <span class="type-description"><?php _e('Link to an external website', 'ibew-local-53'); ?></span>
            </label>
        </div>
    </div>
    
    <!-- Document Fields -->
    <div id="document-fields" class="resource-fields-section <?php echo $resource_type === 'document' ? 'active' : ''; ?>">
        <h4><span class="dashicons dashicons-media-document" style="margin-right: 8px;"></span><?php _e('Document Upload', 'ibew-local-53'); ?></h4>
        
        <input type="hidden" id="resource_file_id" name="resource_file_id" value="<?php echo esc_attr($file_id); ?>" />
        
        <div id="resource-file-preview" style="margin-bottom: 15px; <?php echo $file_id ? '' : 'display:none;'; ?>">
            <div style="background: #fff; padding: 15px; border-radius: 4px; border: 1px solid #ddd; display: flex; align-items: center; gap: 15px;">
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
            <?php _e('Upload a PDF, DOC, DOCX, XLS, XLSX, or other document file. Documents will appear in the "Official Documents" section.', 'ibew-local-53'); ?>
        </p>
    </div>
    
    <!-- External Link Fields -->
    <div id="link-fields" class="resource-fields-section <?php echo $resource_type === 'external_link' ? 'active' : ''; ?>">
        <h4><span class="dashicons dashicons-admin-links" style="margin-right: 8px;"></span><?php _e('External Link', 'ibew-local-53'); ?></h4>
        
        <table class="form-table" style="margin: 0;">
            <tr>
                <th style="padding-left: 0;"><label for="resource_link_url"><?php _e('Link URL', 'ibew-local-53'); ?></label></th>
                <td>
                    <input type="url" id="resource_link_url" name="resource_link_url" value="<?php echo esc_attr($link_url); ?>" class="regular-text" placeholder="https://example.com" style="width: 100%;" />
                    <p class="description"><?php _e('Enter the full URL including https://. Links will appear in the "External Resources" section.', 'ibew-local-53'); ?></p>
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Display Order (for external links) -->
    <div id="order-field" style="margin-top: 16px; <?php echo $resource_type === 'external_link' ? '' : 'display:none;'; ?>">
        <label for="resource_display_order"><strong><?php _e('Display Order', 'ibew-local-53'); ?></strong></label>
        <input type="number" id="resource_display_order" name="resource_display_order" value="<?php echo esc_attr($display_order ?: 0); ?>" class="small-text" min="0" style="margin-left: 10px;" />
        <p class="description"><?php _e('Lower numbers appear first in the External Resources section.', 'ibew-local-53'); ?></p>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Toggle fields based on resource type
        $('input[name="resource_type"]').on('change', function() {
            var type = $(this).val();
            
            if (type === 'document') {
                $('#document-fields').addClass('active');
                $('#link-fields').removeClass('active');
                $('#order-field').hide();
            } else {
                $('#document-fields').removeClass('active');
                $('#link-fields').addClass('active');
                $('#order-field').show();
            }
        });
        
        // Media uploader for documents
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

    // Save resource type
    if (isset($_POST['resource_type'])) {
        $type = sanitize_text_field($_POST['resource_type']);
        if (in_array($type, array('document', 'external_link'))) {
            update_post_meta($post_id, 'resource_type', $type);
        }
    }

    // Save document file
    if (isset($_POST['resource_file_id'])) {
        $file_id = absint($_POST['resource_file_id']);
        if ($file_id) {
            update_post_meta($post_id, 'resource_file_id', $file_id);
        } else {
            delete_post_meta($post_id, 'resource_file_id');
        }
    }

    // Save external link URL
    if (isset($_POST['resource_link_url'])) {
        $url = esc_url_raw($_POST['resource_link_url']);
        if ($url) {
            update_post_meta($post_id, 'resource_link_url', $url);
        } else {
            delete_post_meta($post_id, 'resource_link_url');
        }
    }

    // Save display order
    if (isset($_POST['resource_display_order'])) {
        update_post_meta($post_id, 'resource_display_order', absint($_POST['resource_display_order']));
    }
}
add_action('save_post', 'ibew_local_53_save_resource_meta');

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

/**
 * Insert a column immediately before the Date column (or append if Date is missing).
 *
 * @param array  $columns Existing columns.
 * @param string $slug    New column key.
 * @param string $label   Column heading.
 * @return array
 */
function ibew_local_53_admin_columns_insert_before_date($columns, $slug, $label) {
    if (!is_array($columns)) {
        return $columns;
    }
    $new_columns = array();
    $inserted = false;
    foreach ($columns as $key => $value) {
        if ($key === 'date') {
            $new_columns[$slug] = $label;
            $inserted = true;
        }
        $new_columns[$key] = $value;
    }
    if (!$inserted) {
        $new_columns[$slug] = $label;
    }
    return $new_columns;
}

/**
 * Membership level IDs required to view this post (PMPro "Require Membership" on the post).
 *
 * @param int $post_id Post ID.
 * @return int[]
 */
function ibew_local_53_pmpro_get_post_restriction_level_ids($post_id) {
    global $wpdb;
    $post_id = (int) $post_id;
    if ($post_id < 1 || empty($wpdb->pmpro_memberships_pages)) {
        return array();
    }
    $col = $wpdb->get_col(
        $wpdb->prepare(
            "SELECT membership_id FROM {$wpdb->pmpro_memberships_pages} WHERE page_id = %d",
            $post_id
        )
    );
    return array_map('intval', (array) $col);
}

/**
 * Post IDs in this list that have at least one PMPro "Require Membership" level (single query).
 *
 * @param int[] $post_ids Post IDs.
 * @return int[]
 */
function ibew_local_53_pmpro_get_restricted_post_ids(array $post_ids) {
    global $wpdb;
    $post_ids = array_values(array_unique(array_filter(array_map('intval', $post_ids))));
    if (empty($post_ids)) {
        return array();
    }
    sort($post_ids, SORT_NUMERIC);
    static $cache = array();
    $cache_key = implode(',', $post_ids);
    if (isset($cache[$cache_key])) {
        return $cache[$cache_key];
    }
    if (empty($wpdb->pmpro_memberships_pages)) {
        $cache[$cache_key] = array();
        return array();
    }
    $ids_sql = implode(',', $post_ids);
    $sql = "SELECT DISTINCT page_id FROM {$wpdb->pmpro_memberships_pages} WHERE page_id IN ($ids_sql)";
    $rows = $wpdb->get_col($sql);
    $cache[$cache_key] = array_map('intval', (array) $rows);
    return $cache[$cache_key];
}

/**
 * Whether a resource post is restricted via PMPro (Require Membership on the post).
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function ibew_local_53_resource_requires_pmpro_membership($post_id) {
    return !empty(ibew_local_53_pmpro_get_post_restriction_level_ids((int) $post_id));
}

/**
 * Sort resource posts: members-only first, then public; each group by title (case-insensitive).
 *
 * @param WP_Post[] $posts Posts from a resource query.
 * @return WP_Post[]
 */
function ibew_local_53_order_resource_posts_members_first(array $posts) {
    $restricted = array();
    $public = array();
    $valid = array();
    foreach ($posts as $post) {
        if ($post instanceof WP_Post) {
            $valid[] = $post;
        }
    }
    if (empty($valid)) {
        return array();
    }
    $restricted_ids = array_flip(ibew_local_53_pmpro_get_restricted_post_ids(wp_list_pluck($valid, 'ID')));
    foreach ($valid as $post) {
        if (isset($restricted_ids[$post->ID])) {
            $restricted[] = $post;
        } else {
            $public[] = $post;
        }
    }
    $cmp = static function ($a, $b) {
        return strcasecmp($a->post_title, $b->post_title);
    };
    usort($restricted, $cmp);
    usort($public, $cmp);
    return array_merge($restricted, $public);
}

/**
 * Echo admin list cell: Public vs Members only (with level names in title tooltip).
 *
 * @param int $post_id Post ID.
 */
function ibew_local_53_pmpro_render_admin_visibility_cell($post_id) {
    if (!function_exists('pmpro_getAllLevels')) {
        echo '<span aria-hidden="true">—</span>';
        return;
    }
    $level_ids = ibew_local_53_pmpro_get_post_restriction_level_ids($post_id);
    if (empty($level_ids)) {
        echo '<span class="ibew-visibility-public">' . esc_html__('Public', 'ibew-local-53') . '</span>';
        return;
    }
    $levels = pmpro_getAllLevels(true, true);
    $names = array();
    foreach ($levels as $level) {
        if (is_object($level) && in_array((int) $level->id, $level_ids, true)) {
            $names[] = $level->name;
        }
    }
    $title = !empty($names) ? implode(', ', $names) : '';
    echo '<span class="ibew-visibility-members"' . ($title !== '' ? ' title="' . esc_attr($title) . '"' : '') . '>';
    echo esc_html__('Members only', 'ibew-local-53');
    echo '</span>';
}

// Add custom columns to Resource admin list
function ibew_local_53_resource_admin_columns($columns) {
    $new_columns = array();
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'title') {
            $new_columns['resource_type'] = __('Type', 'ibew-local-53');
            $new_columns['resource_details'] = __('Details', 'ibew-local-53');
        }
    }
    return ibew_local_53_admin_columns_insert_before_date(
        $new_columns,
        'ibew_pmpro_visibility',
        __('Visibility', 'ibew-local-53')
    );
}
add_filter('manage_resource_posts_columns', 'ibew_local_53_resource_admin_columns');

// Populate custom columns for Resource
function ibew_local_53_resource_admin_column_content($column, $post_id) {
    $resource_type = get_post_meta($post_id, 'resource_type', true) ?: 'document';
    
    if ($column === 'resource_type') {
        if ($resource_type === 'document') {
            echo '<span class="dashicons dashicons-media-document" style="color: #c82e39;" title="Document"></span> ';
            echo '<span style="color: #666;">Document</span>';
        } else {
            echo '<span class="dashicons dashicons-admin-links" style="color: #2271b1;" title="External Link"></span> ';
            echo '<span style="color: #666;">External Link</span>';
        }
    }
    
    if ($column === 'resource_details') {
        if ($resource_type === 'document') {
            $file_info = ibew_local_53_get_resource_file_info($post_id);
            if ($file_info) {
                echo '<span style="color: #666;">' . esc_html($file_info['type']) . ' • ' . esc_html($file_info['size']) . '</span>';
            } else {
                echo '<span style="color: #d63638;">No file uploaded</span>';
            }
        } else {
            $url = get_post_meta($post_id, 'resource_link_url', true);
            if ($url) {
                $display_url = strlen($url) > 50 ? substr($url, 0, 50) . '...' : $url;
                echo '<a href="' . esc_url($url) . '" target="_blank" style="color: #2271b1;">' . esc_html($display_url) . '</a>';
            } else {
                echo '<span style="color: #d63638;">No URL set</span>';
            }
        }
    }

    if ($column === 'ibew_pmpro_visibility') {
        ibew_local_53_pmpro_render_admin_visibility_cell($post_id);
    }
}
add_action('manage_resource_posts_custom_column', 'ibew_local_53_resource_admin_column_content', 10, 2);

/**
 * Visibility column on News and Events admin lists.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function ibew_local_53_news_event_pmpro_visibility_column($column, $post_id) {
    if ($column !== 'ibew_pmpro_visibility') {
        return;
    }
    ibew_local_53_pmpro_render_admin_visibility_cell($post_id);
}
add_action('manage_news_posts_custom_column', 'ibew_local_53_news_event_pmpro_visibility_column', 10, 2);
add_action('manage_event_posts_custom_column', 'ibew_local_53_news_event_pmpro_visibility_column', 10, 2);

/**
 * @param array $columns Default columns.
 * @return array
 */
function ibew_local_53_news_admin_columns($columns) {
    return ibew_local_53_admin_columns_insert_before_date(
        $columns,
        'ibew_pmpro_visibility',
        __('Visibility', 'ibew-local-53')
    );
}
add_filter('manage_news_posts_columns', 'ibew_local_53_news_admin_columns');

/**
 * @param array $columns Default columns.
 * @return array
 */
function ibew_local_53_event_admin_columns($columns) {
    return ibew_local_53_admin_columns_insert_before_date(
        $columns,
        'ibew_pmpro_visibility',
        __('Visibility', 'ibew-local-53')
    );
}
add_filter('manage_event_posts_columns', 'ibew_local_53_event_admin_columns');

// Add filter dropdown for resource type in admin
function ibew_local_53_resource_admin_filter() {
    global $typenow;
    
    if ($typenow !== 'resource') {
        return;
    }
    
    $current_type = isset($_GET['resource_type_filter']) ? $_GET['resource_type_filter'] : '';
    ?>
    <select name="resource_type_filter">
        <option value=""><?php _e('All Types', 'ibew-local-53'); ?></option>
        <option value="document" <?php selected($current_type, 'document'); ?>><?php _e('Documents', 'ibew-local-53'); ?></option>
        <option value="external_link" <?php selected($current_type, 'external_link'); ?>><?php _e('External Links', 'ibew-local-53'); ?></option>
    </select>
    <?php
}
add_action('restrict_manage_posts', 'ibew_local_53_resource_admin_filter');

// Handle resource type filter in admin
function ibew_local_53_resource_admin_filter_query($query) {
    global $pagenow, $typenow;
    
    if ($pagenow !== 'edit.php' || $typenow !== 'resource' || !is_admin() || !$query->is_main_query()) {
        return;
    }
    
    if (isset($_GET['resource_type_filter']) && !empty($_GET['resource_type_filter'])) {
        $query->set('meta_query', array(
            array(
                'key' => 'resource_type',
                'value' => sanitize_text_field($_GET['resource_type_filter']),
                'compare' => '=',
            ),
        ));
    }
}
add_action('pre_get_posts', 'ibew_local_53_resource_admin_filter_query');

// ============================================
// MEMBER DASHBOARD PAGE (bootstrap)
// ============================================

/**
 * Ensure a published Member Dashboard page exists and uses the correct template.
 * Runs in admin for users who can manage_options; creates the page once if missing.
 */
function ibew_local_53_ensure_member_dashboard_page() {
    if (!is_admin() || !current_user_can('manage_options')) {
        return;
    }

    $slug = 'member-dashboard';
    $page = get_page_by_path($slug, OBJECT, 'page');

    if ($page instanceof WP_Post && $page->post_status === 'publish') {
        $tpl = get_post_meta($page->ID, '_wp_page_template', true);
        if ($tpl !== 'page-member-dashboard.php') {
            update_post_meta($page->ID, '_wp_page_template', 'page-member-dashboard.php');
        }
        return;
    }

    $post_id = wp_insert_post(
        array(
            'post_title'   => __('Member Dashboard', 'ibew-local-53'),
            'post_name'    => $slug,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => '',
        ),
        true
    );

    if (is_wp_error($post_id) || !$post_id) {
        return;
    }

    update_post_meta($post_id, '_wp_page_template', 'page-member-dashboard.php');
    set_transient('ibew_member_dashboard_created_notice', 1, 60);
}
add_action('admin_init', 'ibew_local_53_ensure_member_dashboard_page', 20);

/**
 * After auto-creating the Member Dashboard page, prompt to set PMPro restrictions.
 */
function ibew_local_53_member_dashboard_admin_notice() {
    if (!get_transient('ibew_member_dashboard_created_notice') || !current_user_can('manage_options')) {
        return;
    }
    delete_transient('ibew_member_dashboard_created_notice');
    echo '<div class="notice notice-success is-dismissible"><p>';
    esc_html_e('IBEW Local 53: The Member Dashboard page was created. Edit the page and use Paid Memberships Pro to require the appropriate membership level (Require Membership / content settings).', 'ibew-local-53');
    echo '</p></div>';
}
add_action('admin_notices', 'ibew_local_53_member_dashboard_admin_notice');

// ============================================
// PMPRO: MEMBERS-ONLY RESOURCES, NEWS, AND EVENTS
// ============================================
// Exposes PMPro "Require Membership" (editor sidebar + classic meta box) on these post types
// and hides restricted items from theme listing queries. Single news/event views still use
// PMPro's content filter for the login / levels message.

/**
 * Post types that can be restricted in the editor via Paid Memberships Pro.
 *
 * @return string[]
 */
function ibew_local_53_pmpro_member_content_post_types() {
    return array('resource', 'news', 'event');
}

/**
 * @param string[] $types Default PMPro restrictable types.
 * @return string[]
 */
function ibew_local_53_pmpro_restrictable_post_types($types) {
    if (!function_exists('pmpro_getAllLevels')) {
        return $types;
    }
    return array_values(array_unique(array_merge((array) $types, ibew_local_53_pmpro_member_content_post_types())));
}
add_filter('pmpro_restrictable_post_types', 'ibew_local_53_pmpro_restrictable_post_types');

/**
 * Include custom post types when PMPro's "filter searches and archives" runs.
 *
 * @param string[] $types Default types.
 * @return string[]
 */
function ibew_local_53_pmpro_search_filter_post_types($types) {
    if (!function_exists('pmpro_getAllLevels')) {
        return $types;
    }
    return array_values(array_unique(array_merge((array) $types, ibew_local_53_pmpro_member_content_post_types())));
}
add_filter('pmpro_search_filter_post_types', 'ibew_local_53_pmpro_search_filter_post_types');

/**
 * Find a published resource that uses this attachment as its document file.
 *
 * @param int $attachment_id Attachment post ID.
 * @return int Resource post ID or 0.
 */
function ibew_local_53_get_resource_id_for_attachment($attachment_id) {
    $attachment_id = (int) $attachment_id;
    if ($attachment_id < 1) {
        return 0;
    }
    static $cache = array();
    if (array_key_exists($attachment_id, $cache)) {
        return $cache[$attachment_id];
    }
    $q = new WP_Query(array(
        'post_type'              => 'resource',
        'post_status'            => 'publish',
        'posts_per_page'         => 1,
        'fields'                 => 'ids',
        'no_found_rows'          => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
        'meta_query'             => array(
            array(
                'key'   => 'resource_file_id',
                'value' => $attachment_id,
            ),
        ),
    ));
    $cache[$attachment_id] = !empty($q->posts) ? (int) $q->posts[0] : 0;
    return $cache[$attachment_id];
}

/**
 * Block direct downloads of files tied to a members-only resource.
 */
function ibew_local_53_pmpro_protect_resource_file_attachments() {
    if (!function_exists('pmpro_has_membership_access') || !function_exists('pmpro_url')) {
        return;
    }
    if (!is_attachment()) {
        return;
    }
    $att_id = get_queried_object_id();
    if ($att_id < 1) {
        return;
    }
    $resource_id = ibew_local_53_get_resource_id_for_attachment($att_id);
    if ($resource_id < 1) {
        return;
    }
    if (pmpro_has_membership_access($resource_id)) {
        return;
    }
    wp_safe_redirect(pmpro_url('levels'));
    exit;
}
add_action('template_redirect', 'ibew_local_53_pmpro_protect_resource_file_attachments', 5);

/**
 * Remove member-restricted resources, news, and events from listing queries (not singular main).
 *
 * @param WP_Post[] $posts Posts for the current query.
 * @param WP_Query  $query Query instance.
 * @return WP_Post[]
 */
function ibew_local_53_pmpro_filter_member_content_in_lists($posts, $query) {
    if (!function_exists('pmpro_has_membership_access')) {
        return $posts;
    }
    if (is_admin()) {
        return $posts;
    }
    if (defined('REST_REQUEST') && REST_REQUEST) {
        return $posts;
    }
    if ($query->is_singular() && $query->is_main_query()) {
        return $posts;
    }
    $member_types = ibew_local_53_pmpro_member_content_post_types();
    $q_type = $query->get('post_type');
    if ($q_type === '') {
        return $posts;
    }
    if ($q_type !== 'any') {
        $q_types = is_array($q_type) ? $q_type : array($q_type);
        if (!array_intersect($member_types, $q_types)) {
            return $posts;
        }
    }
    $out = array();
    foreach ($posts as $post) {
        if (!$post instanceof WP_Post) {
            $out[] = $post;
            continue;
        }
        if (in_array($post->post_type, $member_types, true) && !pmpro_has_membership_access($post->ID)) {
            continue;
        }
        $out[] = $post;
    }
    return $out;
}
add_filter('the_posts', 'ibew_local_53_pmpro_filter_member_content_in_lists', 10, 2);

// ============================================
// PMPRO: FREE TIER VIA WORDPRESS REGISTRATION (optional)
// ============================================
// If IBEW_PMPRO_DEFAULT_FREE_LEVEL_ID is set to a free level ID, native WP registration
// assigns that level (user_register) and PMPro no longer redirects register to levels.
// @link https://www.paidmembershipspro.com/assign-default-membership-level-wordpress-user-registration/

/**
 * Membership level ID to assign on WordPress user_register (0 = disabled).
 *
 * @return int
 */
function ibew_local_53_pmpro_default_free_level_id() {
    $id = 0;
    if (defined('IBEW_PMPRO_DEFAULT_FREE_LEVEL_ID')) {
        $id = (int) IBEW_PMPRO_DEFAULT_FREE_LEVEL_ID;
    }
    return (int) apply_filters('ibew_local_53_pmpro_default_free_level_id', $id);
}

/**
 * Whether the site should allow the native WP register screen alongside PMPro.
 */
function ibew_local_53_pmpro_native_free_registration_enabled() {
    return ibew_local_53_pmpro_default_free_level_id() > 0 && get_option('users_can_register');
}

/**
 * Stop PMPro from redirecting wp-login.php?action=register to the levels page when
 * we use the free-level-on-user_register flow.
 *
 * @param bool $allow_redirect Default true.
 * @return bool
 */
function ibew_local_53_pmpro_maybe_disable_register_redirect($allow_redirect) {
    if (ibew_local_53_pmpro_native_free_registration_enabled()) {
        return false;
    }
    return $allow_redirect;
}
add_filter('pmpro_login_redirect', 'ibew_local_53_pmpro_maybe_disable_register_redirect');

/**
 * Assign the configured free PMPro level to new users (WordPress registration only).
 *
 * @param int $user_id New user ID.
 */
function ibew_local_53_pmpro_assign_default_free_level_on_register($user_id) {
    if (!function_exists('pmpro_changeMembershipLevel') || !function_exists('pmpro_getLevel') || !function_exists('pmpro_isLevelFree')) {
        return;
    }
    $level_id = ibew_local_53_pmpro_default_free_level_id();
    if ($level_id < 1) {
        return;
    }
    // Checkout and other flows may assign a level in the same request; do not override.
    if (function_exists('pmpro_hasMembershipLevel') && pmpro_hasMembershipLevel(false, $user_id)) {
        return;
    }
    $level = pmpro_getLevel($level_id);
    if (empty($level)) {
        return;
    }
    if (!pmpro_isLevelFree($level)) {
        return;
    }
    pmpro_changeMembershipLevel($level_id, $user_id);
}
add_action('user_register', 'ibew_local_53_pmpro_assign_default_free_level_on_register', 20, 1);

/**
 * Permalink for the Member Dashboard page (used after login). Empty if missing/unpublished.
 *
 * @return string
 */
function ibew_local_53_get_member_dashboard_permalink() {
    static $cached = false;
    static $url = '';
    if ($cached) {
        return $url;
    }
    $cached = true;
    $page = get_page_by_path('member-dashboard', OBJECT, 'page');
    if (!$page instanceof WP_Post || $page->post_status !== 'publish') {
        return $url;
    }
    $url = apply_filters('ibew_local_53_member_dashboard_login_url', get_permalink($page));
    return $url;
}

/**
 * When PMPro would send an active member to the Account page, send them to Member Dashboard instead.
 * Respects other redirect_to targets (admin, checkout, etc.).
 *
 * @param string         $redirect_to URL.
 * @param string         $request     Requested redirect (unused).
 * @param WP_User|mixed $user        Logged-in user.
 * @return string
 */
function ibew_local_53_pmpro_login_redirect_member_dashboard($redirect_to, $request, $user) {
    if (!($user instanceof WP_User) || !$user->exists()) {
        return $redirect_to;
    }
    $dashboard = ibew_local_53_get_member_dashboard_permalink();
    if ($dashboard === '') {
        return $redirect_to;
    }
    if (!function_exists('pmpro_url')) {
        return $redirect_to;
    }
    $account = pmpro_url('account');
    if ($account === '') {
        return $redirect_to;
    }
    if (untrailingslashit((string) $redirect_to) !== untrailingslashit((string) $account)) {
        return $redirect_to;
    }
    return $dashboard;
}
add_filter('pmpro_login_redirect_url', 'ibew_local_53_pmpro_login_redirect_member_dashboard', 15, 3);

/**
 * Fallback if another plugin alters login_redirect after PMPro (same account → dashboard swap).
 *
 * @param string           $redirect_to Redirect URL.
 * @param string           $request     Requested redirect.
 * @param WP_User|WP_Error $user        User or error.
 * @return string
 */
function ibew_local_53_login_redirect_member_dashboard_fallback($redirect_to, $request, $user) {
    if (!($user instanceof WP_User) || !$user->exists()) {
        return $redirect_to;
    }
    return ibew_local_53_pmpro_login_redirect_member_dashboard($redirect_to, $request, $user);
}
add_filter('login_redirect', 'ibew_local_53_login_redirect_member_dashboard_fallback', 100, 3);

require_once get_template_directory() . '/inc/member-dashboard-nav.php';

/**
 * Body classes so PMPro frontend styles apply on the Member Register template.
 */
function ibew_local_53_member_register_body_class($classes) {
    if (is_page_template('page-member-register.php')) {
        $classes[] = 'pmpro';
        $classes[] = 'ibew-member-register';
    }
    return $classes;
}
add_filter('body_class', 'ibew_local_53_member_register_body_class');

/**
 * Ensure a published Member Register page exists (PMPro levels / checkout entry).
 */
function ibew_local_53_ensure_member_register_page() {
    if (!is_admin() || !current_user_can('manage_options')) {
        return;
    }

    $slug = 'member-register';
    $page = get_page_by_path($slug, OBJECT, 'page');

    if ($page instanceof WP_Post && $page->post_status === 'publish') {
        $tpl = get_post_meta($page->ID, '_wp_page_template', true);
        if ($tpl !== 'page-member-register.php') {
            update_post_meta($page->ID, '_wp_page_template', 'page-member-register.php');
        }
        return;
    }

    $post_id = wp_insert_post(
        array(
            'post_title'   => __('Member Register', 'ibew-local-53'),
            'post_name'    => $slug,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => '',
        ),
        true
    );

    if (is_wp_error($post_id) || !$post_id) {
        return;
    }

    update_post_meta($post_id, '_wp_page_template', 'page-member-register.php');
    set_transient('ibew_member_register_created_notice', 1, 60);
}
add_action('admin_init', 'ibew_local_53_ensure_member_register_page', 21);

/**
 * Admin notice after Member Register page is auto-created.
 */
function ibew_local_53_member_register_admin_notice() {
    if (!get_transient('ibew_member_register_created_notice') || !current_user_can('manage_options')) {
        return;
    }
    delete_transient('ibew_member_register_created_notice');
    echo '<div class="notice notice-success is-dismissible"><p>';
    esc_html_e('IBEW Local 53: The Member Register page was created. Add it to your menu and ensure Paid Memberships Pro has membership levels configured.', 'ibew-local-53');
    echo '</p></div>';
}
add_action('admin_notices', 'ibew_local_53_member_register_admin_notice');
