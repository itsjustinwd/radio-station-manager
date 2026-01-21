<?php
/**
 * Ad Management Functions for Radio Station Manager
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display Header Ad (728x90) - Shortcode
 */
function rsm_header_ad_shortcode($atts) {
    $enable = get_field('header_ad', 'option')['enable_header_ad'] ?? false;
    
    if (!$enable) {
        return '';
    }
    
    $ad_code = get_field('header_ad', 'option')['header_ad_code'] ?? '';
    
    if (empty($ad_code)) {
        return '';
    }
    
    $output = '<div class="rsm-header-ad rsm-ad-unit">';
    $output .= $ad_code;
    $output .= '</div>';
    
    return $output;
}
add_shortcode('rsm_header_ad', 'rsm_header_ad_shortcode');

/**
 * Display Mid-Content Ad (728x90) - Shortcode
 */
function rsm_mid_content_ad_shortcode($atts) {
    $enable = get_field('mid_content_ad', 'option')['enable_mid_content_ad'] ?? false;
    
    if (!$enable) {
        return '';
    }
    
    $ad_code = get_field('mid_content_ad', 'option')['mid_content_ad_code'] ?? '';
    
    if (empty($ad_code)) {
        return '';
    }
    
    $output = '<div class="rsm-mid-content-ad rsm-ad-unit">';
    $output .= $ad_code;
    $output .= '</div>';
    
    return $output;
}
add_shortcode('rsm_mid_content_ad', 'rsm_mid_content_ad_shortcode');

/**
 * Display Sidebar Top Ad (300x250) - Shortcode
 */
function rsm_sidebar_top_ad_shortcode($atts) {
    $enable = get_field('sidebar_top_ad', 'option')['enable_sidebar_top_ad'] ?? false;
    
    if (!$enable) {
        return '';
    }
    
    $ad_code = get_field('sidebar_top_ad', 'option')['sidebar_top_ad_code'] ?? '';
    
    if (empty($ad_code)) {
        return '';
    }
    
    $output = '<div class="rsm-sidebar-top-ad rsm-ad-unit">';
    $output .= $ad_code;
    $output .= '</div>';
    
    return $output;
}
add_shortcode('rsm_sidebar_top_ad', 'rsm_sidebar_top_ad_shortcode');

/**
 * Display Sidebar Bottom Ad (300x250) - Shortcode
 */
function rsm_sidebar_bottom_ad_shortcode($atts) {
    $enable = get_field('sidebar_bottom_ad', 'option')['enable_sidebar_bottom_ad'] ?? false;
    
    if (!$enable) {
        return '';
    }
    
    $ad_code = get_field('sidebar_bottom_ad', 'option')['sidebar_bottom_ad_code'] ?? '';
    
    if (empty($ad_code)) {
        return '';
    }
    
    $output = '<div class="rsm-sidebar-bottom-ad rsm-ad-unit">';
    $output .= $ad_code;
    $output .= '</div>';
    
    return $output;
}
add_shortcode('rsm_sidebar_bottom_ad', 'rsm_sidebar_bottom_ad_shortcode');