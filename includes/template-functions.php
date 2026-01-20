<?php
/**
 * Template loading and content filters
 */

// Load custom templates from plugin
function rsm_load_plugin_templates($template) {
    if (is_singular('radio_show')) {
        $plugin_template = RSM_PLUGIN_DIR . 'templates/single-radio_show.php';
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }
    
    if (is_tax('show_host_tax')) {
        $plugin_template = RSM_PLUGIN_DIR . 'templates/taxonomy-show_host_tax.php';
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }
    
    return $template;
}
add_filter('template_include', 'rsm_load_plugin_templates', 99);

// Filter the content for single show pages (adds details box automatically)
function rsm_single_show_content($content) {
    if (is_singular('radio_show') && in_the_loop() && is_main_query()) {
        $days = get_field('show_days');
        $start = get_field('start_time');
        $end = get_field('end_time');
        $hosts = get_the_terms(get_the_ID(), 'show_host_tax');
        
        $show_details = '<div class="show-details-box">';
        
        if ($hosts && !is_wp_error($hosts)) {
            $show_details .= '<div class="show-detail">';
            $show_details .= '<span class="detail-label">Hosted by:</span>';
            $show_details .= '<span class="detail-value">';
            $host_links = array();
            foreach ($hosts as $host) {
                $host_links[] = '<a href="' . get_term_link($host) . '">' . esc_html($host->name) . '</a>';
            }
            $show_details .= implode(' & ', $host_links);
            $show_details .= '</span>';
            $show_details .= '</div>';
        }
        
        if ($days && is_array($days)) {
            $show_details .= '<div class="show-detail">';
            $show_details .= '<span class="detail-label">Airs:</span>';
            $show_details .= '<span class="detail-value">' . implode(', ', array_map('ucfirst', $days)) . '</span>';
            $show_details .= '</div>';
        }
        
        if ($start && $end) {
            $show_details .= '<div class="show-detail">';
            $show_details .= '<span class="detail-label">Time:</span>';
            $show_details .= '<span class="detail-value">';
            $show_details .= date('g:i a', strtotime($start)) . ' - ' . date('g:i a', strtotime($end));
            $show_details .= '</span>';
            $show_details .= '</div>';
        }
        
        $show_details .= '</div>';
        
        return $show_details . $content;
    }
    
    return $content;
}
add_filter('the_content', 'rsm_single_show_content', 20);

// Display host shows on taxonomy archive pages
function rsm_host_archive_content($content) {
    if (is_tax('show_host_tax') && is_main_query()) {
        $term = get_queried_object();
        
        $args = array(
            'post_type' => 'radio_show',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'show_host_tax',
                    'field' => 'term_id',
                    'terms' => $term->term_id,
                ),
            ),
        );
        
        $shows = get_posts($args);
        
        $output = '<div class="host-shows-archive">';
        $output .= '<h2>Shows Hosted by ' . esc_html($term->name) . '</h2>';
        
        if ($term->description) {
            $output .= '<div class="host-description">' . wpautop($term->description) . '</div>';
        }
        
        if ($shows) {
            $output .= '<div class="host-shows-grid">';
            foreach ($shows as $show) {
                $start = get_field('start_time', $show->ID);
                $end = get_field('end_time', $show->ID);
                $days = get_field('show_days', $show->ID);
                
                $output .= '<div class="host-show-card">';
                
                if (has_post_thumbnail($show->ID)) {
                    $output .= '<div class="host-show-image">';
                    $output .= '<a href="' . get_permalink($show->ID) . '">';
                    $output .= get_the_post_thumbnail($show->ID, 'full');
                    $output .= '</a>';
                    $output .= '</div>';
                }
                
                $output .= '<div class="host-show-info">';
                $output .= '<h3><a href="' . get_permalink($show->ID) . '">' . esc_html($show->post_title) . '</a></h3>';
                
                if ($days && is_array($days)) {
                    $output .= '<p class="show-days">' . implode(', ', array_map('ucfirst', $days)) . '</p>';
                }
                
                if ($start && $end) {
                    $start_formatted = date('g:i a', strtotime($start));
                    $end_formatted = date('g:i a', strtotime($end));
                    $output .= '<p class="show-times">' . esc_html($start_formatted . ' - ' . $end_formatted) . '</p>';
                }
                
                if ($show->post_excerpt) {
                    $output .= '<p class="show-excerpt">' . esc_html($show->post_excerpt) . '</p>';
                }
                
                $output .= '</div>';
                $output .= '</div>';
            }
            $output .= '</div>';
        } else {
            $output .= '<p>No shows found for this host.</p>';
        }
        
        $output .= '</div>';
        
        return $output;
    }
    
    return $content;
}
add_filter('the_content', 'rsm_host_archive_content', 10);