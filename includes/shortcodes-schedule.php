<?php
/**
 * Schedule-related shortcodes
 */

// Get currently playing show
function rsm_get_current_show() {
    $current_day = strtolower(date('l'));
    $current_time = current_time('H:i:s');
    
    $args = array(
        'post_type' => 'radio_show',
        'posts_per_page' => -1,
    );
    
    $shows = get_posts($args);
    
    foreach ($shows as $show) {
        $days = get_field('show_days', $show->ID);
        $start_time = get_field('start_time', $show->ID);
        $end_time = get_field('end_time', $show->ID);
        
        if ($days && is_array($days) && in_array($current_day, $days)) {
            if ($start_time && $end_time) {
                $current_timestamp = strtotime($current_time);
                $start_timestamp = strtotime($start_time);
                $end_timestamp = strtotime($end_time);
                
                // Handle shows that go past midnight
                if ($end_timestamp < $start_timestamp) {
                    // Show crosses midnight (e.g., 7pm - 6am)
                    if ($current_timestamp >= $start_timestamp || $current_timestamp < $end_timestamp) {
                        return $show;
                    }
                } else {
                    // Normal show (e.g., 6am - 10am)
                    if ($current_timestamp >= $start_timestamp && $current_timestamp < $end_timestamp) {
                        return $show;
                    }
                }
            }
        }
    }
    
    return null;
}

// Shortcode to display current show
function rsm_now_playing_shortcode($atts) {
    $atts = shortcode_atts(array(
        'show_image' => 'no',
        'show_time' => 'yes',
        'image_only' => 'no',
        'image_size' => 'full',
        'max_width' => '',
        'layout' => 'horizontal',
        'debug' => 'no'
    ), $atts);
    
    $show = rsm_get_current_show();
    
    // DEBUG MODE - Remove this after testing
    if ($atts['debug'] === 'yes') {
        $current_day = strtolower(date('l'));
        $current_time = current_time('H:i:s');
        $debug_output = '<div style="background: #f0f0f0; padding: 15px; margin: 10px 0; border: 2px solid #333;">';
        $debug_output .= '<strong>DEBUG INFO:</strong><br>';
        $debug_output .= 'Current Day: ' . $current_day . '<br>';
        $debug_output .= 'Current Time: ' . $current_time . '<br>';
        $debug_output .= 'WordPress Timezone: ' . wp_timezone_string() . '<br>';
        $debug_output .= 'Show Found: ' . ($show ? 'YES - ' . $show->post_title : 'NO') . '<br>';
        
        // List all shows with times
        $all_shows = get_posts(array('post_type' => 'radio_show', 'posts_per_page' => -1));
        $debug_output .= '<br><strong>All Shows:</strong><br>';
        foreach ($all_shows as $s) {
            $days = get_field('show_days', $s->ID);
            $start = get_field('start_time', $s->ID);
            $end = get_field('end_time', $s->ID);
            $debug_output .= '- ' . $s->post_title . ': ';
            if ($days) $debug_output .= implode(', ', $days) . ' ';
            if ($start && $end) $debug_output .= $start . ' - ' . $end;
            $debug_output .= '<br>';
        }
        
        $debug_output .= '</div>';
        
        if ($show) {
            return $debug_output . '<div class="now-playing"><p>Show detected: ' . $show->post_title . '</p></div>';
        } else {
            return $debug_output . '<div class="now-playing"><p>No show currently airing</p></div>';
        }
    }
    
    if ($show) {
        $hosts = get_the_terms($show->ID, 'show_host_tax');
        $start_time = get_field('start_time', $show->ID);
        $end_time = get_field('end_time', $show->ID);
        $has_thumbnail = has_post_thumbnail($show->ID);
        
        if ($atts['image_only'] === 'yes' && $has_thumbnail) {
            $max_width_class = '';
            if (!empty($atts['max_width'])) {
                $max_width_class = ' max-width-' . esc_attr($atts['max_width']);
            }
            
            $output = '<div class="now-playing-image-only' . $max_width_class . '">';
            $output .= '<a href="' . get_permalink($show->ID) . '">';
            $output .= get_the_post_thumbnail($show->ID, $atts['image_size']);
            $output .= '</a>';
            $output .= '</div>';
            return $output;
        }
        
        $layout_class = ($atts['layout'] === 'vertical') ? 'now-playing-vertical' : '';
        $output = '<div class="now-playing ' . $layout_class . '">';
        
        if ($atts['show_image'] === 'yes') {
            if ($has_thumbnail) {
                $output .= '<div class="now-playing-image">';
                $output .= get_the_post_thumbnail($show->ID, $atts['image_size']);
                $output .= '</div>';
            }
        }
        
        $output .= '<div class="now-playing-content">';
        $output .= '<h3>Now Playing</h3>';
        $output .= '<h4><a href="' . get_permalink($show->ID) . '">' . esc_html($show->post_title) . '</a></h4>';
        
        if ($hosts && !is_wp_error($hosts)) {
            $host_names = array();
            foreach ($hosts as $host) {
                $host_names[] = '<a href="' . get_term_link($host) . '">' . esc_html($host->name) . '</a>';
            }
            $output .= '<p class="now-playing-host">with ' . implode(' & ', $host_names) . '</p>';
        }
        
        if ($atts['show_time'] === 'yes' && $start_time && $end_time) {
            $start_formatted = date('g:i a', strtotime($start_time));
            $end_formatted = date('g:i a', strtotime($end_time));
            $output .= '<p class="now-playing-time">' . esc_html($start_formatted . ' - ' . $end_formatted) . '</p>';
        }
        
        $output .= '</div>';
        $output .= '</div>';
        return $output;
    }
    
    return '<div class="now-playing"><div class="now-playing-content"><p>No show currently airing</p></div></div>';
}
add_shortcode('now_playing', 'rsm_now_playing_shortcode');

// Enhanced weekly schedule with options
function rsm_weekly_schedule_shortcode($atts) {
    $atts = shortcode_atts(array(
        'days' => 'all',
        'show_empty' => 'yes',
        'show_images' => 'no'
    ), $atts);
    
    $days_array = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
    $day_labels = array(
        'monday' => 'Monday',
        'tuesday' => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday' => 'Thursday',
        'friday' => 'Friday',
        'saturday' => 'Saturday',
        'sunday' => 'Sunday'
    );
    
    if ($atts['days'] == 'today') {
        $days_array = array(strtolower(date('l')));
    } elseif ($atts['days'] != 'all' && isset($day_labels[strtolower($atts['days'])])) {
        $days_array = array(strtolower($atts['days']));
    }
    
    $output = '<div class="radio-schedule">';
    
    foreach ($days_array as $day) {
        $output .= '<div class="schedule-day">';
        $output .= '<h3>' . esc_html($day_labels[$day]) . '</h3>';
        
        $args = array(
            'post_type' => 'radio_show',
            'posts_per_page' => -1,
            'meta_key' => 'start_time',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'show_days',
                    'value' => $day,
                    'compare' => 'LIKE',
                ),
            ),
        );
        
        $shows = get_posts($args);
        
        if ($shows) {
            $output .= '<ul class="show-list">';
            foreach ($shows as $show) {
                $start = get_field('start_time', $show->ID);
                $end = get_field('end_time', $show->ID);
                $hosts = get_the_terms($show->ID, 'show_host_tax');
                
                if ($start && $end) {
                    $start_formatted = date('g:i a', strtotime($start));
                    $end_formatted = date('g:i a', strtotime($end));
                    
                    $output .= '<li class="show-item">';
                    
                    if ($atts['show_images'] === 'yes' && has_post_thumbnail($show->ID)) {
                        $output .= '<div class="show-item-image">';
                        $output .= get_the_post_thumbnail($show->ID, 'full');
                        $output .= '</div>';
                    }
                    
                    $output .= '<div class="show-item-content">';
                    $output .= '<span class="show-time">' . esc_html($start_formatted . ' - ' . $end_formatted) . '</span>';
                    $output .= '<a href="' . get_permalink($show->ID) . '" class="show-title">' . esc_html($show->post_title) . '</a>';
                    if ($hosts && !is_wp_error($hosts)) {
                        $host_names = array();
                        foreach ($hosts as $host) {
                            $host_names[] = $host->name;
                        }
                        $output .= '<span class="show-host">with ' . esc_html(implode(' & ', $host_names)) . '</span>';
                    }
                    $output .= '</div>';
                    $output .= '</li>';
                }
            }
            $output .= '</ul>';
        } else {
            if ($atts['show_empty'] == 'yes') {
                $output .= '<p>No shows scheduled</p>';
            }
        }
        
        $output .= '</div>';
    }
    
    $output .= '</div>';
    
    return $output;
}
add_shortcode('weekly_schedule', 'rsm_weekly_schedule_shortcode');

// Shortcode to display all shows as a list
function rsm_all_shows_shortcode($atts) {
    $atts = shortcode_atts(array(
        'show_images' => 'no'
    ), $atts);
    
    $args = array(
        'post_type' => 'radio_show',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    );
    
    $shows = get_posts($args);
    
    if ($shows) {
        $output = '<div class="all-shows' . ($atts['show_images'] === 'yes' ? ' with-images' : '') . '">';
        $output .= '<ul class="shows-list">';
        
        foreach ($shows as $show) {
            $hosts = get_the_terms($show->ID, 'show_host_tax');
            $output .= '<li>';
            
            if ($atts['show_images'] === 'yes' && has_post_thumbnail($show->ID)) {
                $output .= '<div class="show-list-image">';
                $output .= get_the_post_thumbnail($show->ID, 'full');
                $output .= '</div>';
            }
            
            $output .= '<div class="show-list-content">';
            $output .= '<a href="' . get_permalink($show->ID) . '">' . esc_html($show->post_title) . '</a>';
            if ($hosts && !is_wp_error($hosts)) {
                $host_names = array();
                foreach ($hosts as $host) {
                    $host_names[] = $host->name;
                }
                $output .= ' <span class="show-host-inline">with ' . esc_html(implode(' & ', $host_names)) . '</span>';
            }
            $output .= '</div>';
            $output .= '</li>';
        }
        
        $output .= '</ul>';
        $output .= '</div>';
        return $output;
    }
    
    return '<p>No shows available</p>';
}
add_shortcode('all_shows', 'rsm_all_shows_shortcode');

// Shortcode to display all hosts
function rsm_all_hosts_shortcode($atts) {
    $atts = shortcode_atts(array(
        'show_images' => 'yes',
        'show_count' => 'yes',
        'show_times' => 'no',
        'show_description' => 'yes',
        'columns' => '3',
        'hide_empty' => 'yes',
        'include' => '',
        'exclude' => ''
    ), $atts);
    
    $term_args = array(
        'taxonomy' => 'show_host_tax',
        'hide_empty' => ($atts['hide_empty'] === 'yes'),
    );
    
    // Add include filter if specified
    if (!empty($atts['include'])) {
        $include_slugs = array_map('trim', explode(',', $atts['include']));
        $term_args['slug'] = $include_slugs;
    }
    
    // Add exclude filter if specified
    if (!empty($atts['exclude'])) {
        $exclude_slugs = array_map('trim', explode(',', $atts['exclude']));
        $term_args['exclude'] = $exclude_slugs;
    }
    
    $hosts = get_terms($term_args);
    
    if (!empty($hosts) && !is_wp_error($hosts)) {
        $col_class = 'host-grid-cols-' . $atts['columns'];
        $output = '<div class="all-hosts-grid ' . $col_class . '">';
        
        foreach ($hosts as $host) {
            $show_count = $host->count;
            $image_id = get_term_meta($host->term_id, 'host-image-id', true);
            
            $output .= '<div class="host-card">';
            
            if ($atts['show_images'] === 'yes' && $image_id) {
                $output .= '<div class="host-card-image">';
                $output .= '<a href="' . get_term_link($host) . '">';
                $output .= wp_get_attachment_image($image_id, 'full');
                $output .= '</a>';
                $output .= '</div>';
            }
            
            $output .= '<div class="host-card-content">';
            $output .= '<h3><a href="' . get_term_link($host) . '">' . esc_html($host->name) . '</a></h3>';
            
            if ($atts['show_count'] === 'yes') {
                $show_text = $show_count === 1 ? 'show' : 'shows';
                $output .= '<p class="host-show-count">' . $show_count . ' ' . $show_text . '</p>';
            }
            
            // Show times if enabled
            if ($atts['show_times'] === 'yes') {
                // Get all shows for this host
                $host_shows = get_posts(array(
                    'post_type' => 'radio_show',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'show_host_tax',
                            'field' => 'term_id',
                            'terms' => $host->term_id,
                        ),
                    ),
                ));
                
                if ($host_shows) {
                    $output .= '<div class="host-show-times">';
                    foreach ($host_shows as $show) {
                        $days = get_field('show_days', $show->ID);
                        $start_time = get_field('start_time', $show->ID);
                        $end_time = get_field('end_time', $show->ID);
                        
                        if ($days && is_array($days) && $start_time && $end_time) {
                            $days_formatted = implode(', ', array_map('ucfirst', $days));
                            $start_formatted = date('g:i a', strtotime($start_time));
                            $end_formatted = date('g:i a', strtotime($end_time));
                            
                            $output .= '<p class="host-time-item">';
                            $output .= '<strong>' . esc_html($show->post_title) . ':</strong> ';
                            $output .= '<span class="show-days-inline">' . esc_html($days_formatted) . '</span> ';
                            $output .= '<span class="show-time-inline">' . esc_html($start_formatted . ' - ' . $end_formatted) . '</span>';
                            $output .= '</p>';
                        }
                    }
                    $output .= '</div>';
                }
            }
            
            if ($atts['show_description'] === 'yes' && $host->description) {
                $output .= '<p class="host-card-description">' . esc_html(wp_trim_words($host->description, 20)) . '</p>';
            }
            
            $output .= '<a href="' . get_term_link($host) . '" class="host-view-link">View Shows →</a>';
            $output .= '</div>';
            $output .= '</div>';
        }
        
        $output .= '</div>';
        return $output;
    }
    
    return '<p>No hosts found.</p>';
}
add_shortcode('all_hosts', 'rsm_all_hosts_shortcode');

// Shortcode to display upcoming shows
function rsm_upcoming_shows_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => '5',
        'show_images' => 'yes',
        'show_day' => 'yes',
        'layout' => 'vertical',
        'columns' => '3'
    ), $atts);
    
    $current_day = strtolower(date('l'));
    $current_time = current_time('H:i:s');
    $current_timestamp = strtotime($current_time);
    
    $days_order = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
    $current_day_index = array_search($current_day, $days_order);
    
    $args = array(
        'post_type' => 'radio_show',
        'posts_per_page' => -1,
    );
    
    $all_shows = get_posts($args);
    $upcoming_shows = array();
    
    foreach ($all_shows as $show) {
        $days = get_field('show_days', $show->ID);
        $start_time = get_field('start_time', $show->ID);
        $end_time = get_field('end_time', $show->ID);
        
        if ($days && is_array($days) && $start_time) {
            $start_timestamp = strtotime($start_time);
            
            foreach ($days as $show_day) {
                $show_day_index = array_search($show_day, $days_order);
                
                if ($show_day_index >= $current_day_index) {
                    $days_until = $show_day_index - $current_day_index;
                } else {
                    $days_until = (7 - $current_day_index) + $show_day_index;
                }
                
                if ($days_until == 0 && $start_timestamp <= $current_timestamp) {
                    continue;
                }
                
                $sort_order = ($days_until * 86400) + $start_timestamp;
                
                $upcoming_shows[] = array(
                    'show' => $show,
                    'day' => $show_day,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'days_until' => $days_until,
                    'sort_order' => $sort_order
                );
            }
        }
    }
    
    usort($upcoming_shows, function($a, $b) {
        return $a['sort_order'] - $b['sort_order'];
    });
    
    $upcoming_shows = array_slice($upcoming_shows, 0, intval($atts['limit']));
    
    if (empty($upcoming_shows)) {
        return '<p>No upcoming shows found.</p>';
    }
    
    $layout_class = ($atts['layout'] === 'horizontal') ? 'upcoming-shows-grid' : 'upcoming-shows-list';
    $columns_class = ($atts['layout'] === 'horizontal') ? ' upcoming-cols-' . $atts['columns'] : '';
    
    $output = '<div class="upcoming-shows">';
    $output .= '<ul class="' . $layout_class . $columns_class . '">';
    
    foreach ($upcoming_shows as $item) {
        $show = $item['show'];
        $hosts = get_the_terms($show->ID, 'show_host_tax');
        
        $start_formatted = date('g:i a', strtotime($item['start_time']));
        $end_formatted = date('g:i a', strtotime($item['end_time']));
        
        if ($item['days_until'] == 0) {
            $when_text = 'Today';
        } elseif ($item['days_until'] == 1) {
            $when_text = 'Tomorrow';
        } else {
            $when_text = ucfirst($item['day']);
        }
        
        $output .= '<li class="upcoming-show-item">';
        
        if ($atts['show_images'] === 'yes' && has_post_thumbnail($show->ID)) {
            $output .= '<div class="upcoming-show-image">';
            $output .= '<a href="' . get_permalink($show->ID) . '">';
            $output .= get_the_post_thumbnail($show->ID, 'full');
            $output .= '</a>';
            $output .= '</div>';
        }
        
        $output .= '<div class="upcoming-show-content">';
        
        if ($atts['show_day'] === 'yes') {
            $output .= '<span class="upcoming-show-when">' . esc_html($when_text) . '</span>';
        }
        
        $output .= '<h4 class="upcoming-show-title">';
        $output .= '<a href="' . get_permalink($show->ID) . '">' . esc_html($show->post_title) . '</a>';
        $output .= '</h4>';
        
        if ($hosts && !is_wp_error($hosts)) {
            $host_names = array();
            foreach ($hosts as $host) {
                $host_names[] = $host->name;
            }
            $output .= '<p class="upcoming-show-host">with ' . esc_html(implode(' & ', $host_names)) . '</p>';
        }
        
        $output .= '<p class="upcoming-show-time">' . esc_html($start_formatted . ' - ' . $end_formatted) . '</p>';
        
        $output .= '</div>';
        $output .= '</li>';
    }
    
    $output .= '</ul>';
    $output .= '</div>';
    
    return $output;
}
add_shortcode('upcoming_shows', 'rsm_upcoming_shows_shortcode');