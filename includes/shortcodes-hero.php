<?php
/**
 * Hero slider shortcode
 */

function rsm_display_hero_slider() {
    $slides = new WP_Query(array(
        'post_type' => 'hero-slide',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'post_status' => 'publish'
    ));
    
    if (!$slides->have_posts()) return '';
    
    ob_start();
    ?>
    <div class="splide hero-slider" id="hero-slider">
        <div class="splide__track">
            <ul class="splide__list">
                <?php 
                $slide_index = 0;
                while ($slides->have_posts()) : $slides->the_post(); 
                    $bg_image = get_field('background_image');
                    $heading = get_field('heading');
                    $subheading = get_field('subheading');
                    $description = get_field('description');
                    $button_text = get_field('button_text');
                    $button_link = get_field('button_link');
                    $overlay_photo = get_field('overlay_photo');
                    $overlay_opacity = get_field('overlay_opacity') ?: 0;
                    $overlay_color = get_field('overlay_color') ?: '#000000';
                    
                    // Get the slide link
                    $slide_link_url = get_field('slide_link_url');
                    
                    $heading_color = get_field('heading_color') ?: '#ffffff';
                    $subheading_color = get_field('subheading_color') ?: '#ffffff';
                    $description_color = get_field('description_color') ?: '#ffffff';
                    $button_bg_color = get_field('button_bg_color') ?: '#0066cc';
                    $button_text_color = get_field('button_text_color') ?: '#ffffff';
                    
                    $layout = get_field('layout') ?: 'text-left';
                    
                    $text_bg_overlay = get_field('text_bg_overlay');
                    $text_bg_color = get_field('text_bg_color') ?: '#000000';
                    $text_bg_opacity = get_field('text_bg_opacity') ?: 70;
                    
                    // Check if text content exists
                    $has_text_content = $heading || $subheading || $description || ($button_text && $button_link);
                    
                    $slide_class = 'hero-slide-' . $slide_index;
                    
                    // Check if slide should be clickable and get target
                    $has_slide_link = !empty($slide_link_url);
                    $new_tab = get_field('slide_link_new_tab');
                    $target = ($has_slide_link && $new_tab) ? ' target="_blank" rel="noopener noreferrer"' : '';
                ?>
                <li class="splide__slide <?php echo $slide_class; ?> <?php echo $has_slide_link ? 'has-slide-link' : ''; ?>">
                    <?php if ($has_slide_link): ?>
                    <a href="<?php echo esc_url($slide_link_url); ?>" class="hero-slide-link"<?php echo $target; ?>>
                    <?php endif; ?>
                    
                        <div class="hero-slide-wrapper" style="background-image: url('<?php echo esc_url($bg_image['url']); ?>');">
                            <?php if ($overlay_opacity > 0): ?>
                                <div class="hero-overlay" style="opacity: <?php echo $overlay_opacity / 100; ?>; background-color: <?php echo esc_attr($overlay_color); ?>;"></div>
                            <?php endif; ?>
                            <div class="hero-content hero-layout-<?php echo esc_attr($layout); ?>">
                                <?php if ($has_text_content): ?>
                                    <div class="hero-text-content <?php echo $text_bg_overlay ? 'has-text-bg' : ''; ?>">
                                        <?php if ($heading): ?>
                                            <h1 style="color: <?php echo esc_attr($heading_color); ?>;"><?php echo esc_html($heading); ?></h1>
                                        <?php endif; ?>
                                        <?php if ($subheading): ?>
                                            <h2 style="color: <?php echo esc_attr($subheading_color); ?>;"><?php echo esc_html($subheading); ?></h2>
                                        <?php endif; ?>
                                        <?php if ($description): ?>
                                            <p style="color: <?php echo esc_attr($description_color); ?>;"><?php echo esc_html($description); ?></p>
                                        <?php endif; ?>
                                        <?php if ($button_text && $button_link): ?>
                                            <?php if ($has_slide_link): ?>
                                                <span class="hero-button" style="background-color: <?php echo esc_attr($button_bg_color); ?>; color: <?php echo esc_attr($button_text_color); ?>;"><?php echo esc_html($button_text); ?></span>
                                            <?php else: ?>
                                                <a href="<?php echo esc_url($button_link); ?>" class="hero-button" style="background-color: <?php echo esc_attr($button_bg_color); ?>; color: <?php echo esc_attr($button_text_color); ?>;"><?php echo esc_html($button_text); ?></a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($text_bg_overlay): ?>
                                    <style>
                                        .<?php echo $slide_class; ?> .hero-text-content.has-text-bg::before {
                                            background-color: <?php echo esc_attr($text_bg_color); ?>;
                                            opacity: <?php echo $text_bg_opacity / 100; ?>;
                                        }
                                    </style>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if ($overlay_photo): ?>
                                    <div class="hero-image-content">
                                        <img src="<?php echo esc_url($overlay_photo['url']); ?>" alt="" class="hero-overlay-image">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="hero-bottom-spacer"></div>
                        </div>
                    
                    <?php if ($has_slide_link): ?>
                    </a>
                    <?php endif; ?>
                </li>
                <?php 
                $slide_index++;
                endwhile; 
                wp_reset_postdata(); 
                ?>
            </ul>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('hero_slider', 'rsm_display_hero_slider');