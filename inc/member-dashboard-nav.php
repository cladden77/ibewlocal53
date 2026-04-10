<?php
/**
 * Shared member quick nav (Resources, Forms, Events, My Account) + block/shortcode.
 *
 * @package IBEW_Local_53
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Echo the member quick nav (and optional rule). Links target the Member Dashboard page + anchors when available.
 *
 * @param array $args {
 *     @type bool $show_rule Whether to output the horizontal rule after the nav. Default true.
 * }
 */
function ibew_local_53_render_member_dashboard_nav($args = array()) {
    $args = wp_parse_args(
        $args,
        array(
            'show_rule' => true,
        )
    );

    $dashboard = function_exists('ibew_local_53_get_member_dashboard_permalink')
        ? ibew_local_53_get_member_dashboard_permalink()
        : '';

    $resources_href = $dashboard ? $dashboard . '#member-dashboard-resources' : '#member-dashboard-resources';
    $forms_href     = $dashboard ? $dashboard . '#member-dashboard-forms' : '#member-dashboard-forms';
    $events_href    = $dashboard ? $dashboard . '#member-dashboard-events' : '#member-dashboard-events';

    $account_url = (function_exists('pmpro_url')) ? pmpro_url('account') : home_url('/membership-account/');
    $logout_url  = wp_logout_url(home_url('/'));
    ?>
    <div class="ibew-member-nav-block">
        <nav class="member-dashboard-nav reveal-fade-up" aria-label="<?php esc_attr_e('Member dashboard sections', 'ibew-local-53'); ?>">
            <a class="member-dashboard-nav-link" href="<?php echo esc_url($resources_href); ?>"><?php esc_html_e('Resources', 'ibew-local-53'); ?></a>
            <a class="member-dashboard-nav-link" href="<?php echo esc_url($forms_href); ?>"><?php esc_html_e('Forms', 'ibew-local-53'); ?></a>
            <a class="member-dashboard-nav-link" href="<?php echo esc_url($events_href); ?>"><?php esc_html_e('Events', 'ibew-local-53'); ?></a>
            <a class="member-dashboard-nav-link" href="<?php echo esc_url($account_url); ?>"><?php esc_html_e('My Account', 'ibew-local-53'); ?></a>
            <a class="member-dashboard-nav-link" href="<?php echo esc_url($logout_url); ?>"><?php esc_html_e('Logout', 'ibew-local-53'); ?></a>
        </nav>
        <?php if ($args['show_rule']) : ?>
            <hr class="member-dashboard-rule" />
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Block render callback for ibew-local-53/member-nav.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Inner blocks (unused).
 * @param WP_Block $block      Block instance.
 * @return string
 */
function ibew_local_53_render_member_nav_block($attributes, $content, $block) {
    $show_rule = !isset($attributes['showRule']) || $attributes['showRule'];
    ob_start();
    ibew_local_53_render_member_dashboard_nav(array('show_rule' => $show_rule));
    return ob_get_clean();
}

/**
 * Register dynamic block + shortcode (for pages that only use shortcodes, e.g. PMPro account).
 */
function ibew_local_53_register_member_nav_block() {
    $block_json = get_theme_file_path('blocks/member-nav/block.json');
    if (file_exists($block_json)) {
        register_block_type_from_metadata(
            dirname($block_json),
            array(
                'render_callback' => 'ibew_local_53_render_member_nav_block',
            )
        );
    }

    add_shortcode(
        'ibew_member_nav',
        static function ($atts) {
            $atts = shortcode_atts(
                array(
                    'show_rule' => '1',
                ),
                $atts,
                'ibew_member_nav'
            );
            ob_start();
            ibew_local_53_render_member_dashboard_nav(
                array(
                    'show_rule' => $atts['show_rule'] === '1' || $atts['show_rule'] === 'true',
                )
            );
            return ob_get_clean();
        }
    );
}
add_action('init', 'ibew_local_53_register_member_nav_block', 9);
