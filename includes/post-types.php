<?php
/**
 * Register all custom post types
 */

// Register Radio Shows Post Type
function rsm_register_show_post_type() {
    $labels = array(
        'name' => 'Shows',
        'singular_name' => 'Show',
        'add_new' => 'Add New Show',
        'add_new_item' => 'Add New Show',
        'edit_item' => 'Edit Show',
        'view_item' => 'View Show',
        'all_items' => 'All Shows',
        'search_items' => 'Search Shows',
    );
    
    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-microphone',
        'rewrite' => array('slug' => 'shows'),
        'show_in_rest' => true,
        'menu_position' => 20,
    );
    
    register_post_type('radio_show', $args);
}
add_action('init', 'rsm_register_show_post_type');

// Register Hero Slider Post Type
function rsm_register_hero_slider_post_type() {
    $labels = array(
        'name' => 'Hero Slides',
        'singular_name' => 'Hero Slide',
        'add_new' => 'Add New Slide',
        'add_new_item' => 'Add New Hero Slide',
        'edit_item' => 'Edit Slide',
        'view_item' => 'View Slide',
        'all_items' => 'Hero Slider',
    );
    
    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => false,
        'supports' => array('title', 'thumbnail', 'page-attributes'),
        'menu_icon' => 'dashicons-images-alt2',
        'rewrite' => array('slug' => 'hero-slides'),
        'show_in_rest' => true,
        'menu_position' => 21,
    );
    
    register_post_type('hero-slide', $args);
}
add_action('init', 'rsm_register_hero_slider_post_type');

// Register Concert Post Type
function rsm_register_concert_post_type() {
    $labels = array(
        'name' => 'Concerts',
        'singular_name' => 'Concert',
        'add_new' => 'Add New Concert',
        'add_new_item' => 'Add New Concert',
        'edit_item' => 'Edit Concert',
        'view_item' => 'View Concert',
        'all_items' => 'All Concerts',
    );
    
    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-tickets-alt',
        'rewrite' => array('slug' => 'concerts'),
        'show_in_rest' => true,
        'menu_position' => 22,
    );
    
    register_post_type('concert', $args);
}
add_action('init', 'rsm_register_concert_post_type');

// Add host column to shows admin list
function rsm_add_host_column($columns) {
    $new_columns = array();
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'title') {
            $new_columns['hosts'] = 'Hosts';
        }
    }
    return $new_columns;
}
add_filter('manage_radio_show_posts_columns', 'rsm_add_host_column');

// Display hosts in the column
function rsm_display_host_column($column, $post_id) {
    if ($column === 'hosts') {
        $hosts = get_the_terms($post_id, 'show_host_tax');
        if ($hosts && !is_wp_error($hosts)) {
            $host_names = array();
            foreach ($hosts as $host) {
                $host_names[] = '<a href="' . admin_url('term.php?taxonomy=show_host_tax&tag_ID=' . $host->term_id . '&post_type=radio_show') . '">' . $host->name . '</a>';
            }
            echo implode(', ', $host_names);
        } else {
            echo '—';
        }
    }
}
add_action('manage_radio_show_posts_custom_column', 'rsm_display_host_column', 10, 2);

// Make host column sortable
function rsm_sortable_host_column($columns) {
    $columns['hosts'] = 'hosts';
    return $columns;
}
add_filter('manage_edit-radio_show_sortable_columns', 'rsm_sortable_host_column');