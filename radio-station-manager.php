<?php
/**
 * Plugin Name: Radio Station Manager
 * Description: Complete radio station management system with scheduling, hosts, hero sliders, and concert calendars
 * Version: 2.1.0
 * Author: JustinWd @ WVRC Digital
 * Text Domain: radio-station-manager
 */

// Prevent direct access
if (!defined('ABSPATH')) exit;

// Define plugin constants
define('RSM_VERSION', '2.0.0');
define('RSM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('RSM_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once RSM_PLUGIN_DIR . 'includes/post-types.php';
require_once RSM_PLUGIN_DIR . 'includes/taxonomies.php';
require_once RSM_PLUGIN_DIR . 'includes/shortcodes-schedule.php';
require_once RSM_PLUGIN_DIR . 'includes/shortcodes-hero.php';
require_once RSM_PLUGIN_DIR . 'includes/shortcodes-concert.php';
require_once RSM_PLUGIN_DIR . 'includes/template-functions.php';

// Enqueue styles and scripts
function rsm_enqueue_assets() {
    // Main CSS
    wp_enqueue_style(
        'rsm-styles',
        RSM_PLUGIN_URL . 'assets/css/radio-station-manager.css',
        array(),
        RSM_VERSION
    );
    
    // Splide for hero slider
    wp_enqueue_style(
        'splide-css',
        'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css',
        array(),
        '4.1.4'
    );
    
    wp_enqueue_script(
        'splide-js',
        'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js',
        array(),
        '4.1.4',
        true
    );
    
    // Main JS
    wp_enqueue_script(
        'rsm-scripts',
        RSM_PLUGIN_URL . 'assets/js/radio-station-manager.js',
        array('jquery', 'splide-js'),
        RSM_VERSION,
        true
    );
}
add_action('wp_enqueue_scripts', 'rsm_enqueue_assets');

// Activation hook
function rsm_activate() {
    // Register post types and taxonomies
    rsm_register_show_post_type();
    rsm_register_host_taxonomy();
    rsm_register_hero_slider_post_type();
    rsm_register_concert_post_type();
    
    // Flush rewrite rules
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'rsm_activate');

// Deactivation hook
function rsm_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'rsm_deactivate');

// Add settings link on plugins page
function rsm_add_settings_link($links) {
    $settings_link = '<a href="edit.php?post_type=radio_show">Shows</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'rsm_add_settings_link');
