<?php
/**
 * Template for displaying individual host pages
 */

get_header();

$term = get_queried_object();
$image_id = get_term_meta($term->term_id, 'host-image-id', true);
?>

<div class="host-page-container">
    <div class="host-header">
        <?php if ($image_id) : ?>
            <div class="host-profile-image">
                <?php echo wp_get_attachment_image($image_id, 'medium_large'); ?>
            </div>
        <?php endif; ?>
        
        <div class="host-header-content">
            <h1 class="host-name"><?php echo esc_html($term->name); ?></h1>
            
            <?php if ($term->description) : ?>
                <div class="host-bio">
                    <?php echo wpautop(esc_html($term->description)); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="host-shows-section">
        <h2 class="host-shows-title">Shows</h2>
        
        <?php
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
            'meta_key' => 'start_time',
            'orderby' => 'meta_value',
            'order' => 'ASC',
        );
        
        $shows = new WP_Query($args);
        
        if ($shows->have_posts()) : ?>
            <div class="host-shows-grid">
                <?php while ($shows->have_posts()) : $shows->the_post(); ?>
                    <article class="host-show-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="host-show-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="host-show-details">
                            <h3 class="show-card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            
                            <?php
                            $days = get_field('show_days');
                            $start = get_field('start_time');
                            $end = get_field('end_time');
                            ?>
                            
                            <?php if ($days && is_array($days)) : ?>
                                <p class="show-card-days">
                                    <strong>Airs:</strong> <?php echo implode(', ', array_map('ucfirst', $days)); ?>
                                </p>
                            <?php endif; ?>
                            
                            <?php if ($start && $end) : ?>
                                <p class="show-card-time">
                                    <strong>Time:</strong> 
                                    <?php echo date('g:i a', strtotime($start)); ?> - <?php echo date('g:i a', strtotime($end)); ?>
                                </p>
                            <?php endif; ?>
                            
                            <?php if (has_excerpt()) : ?>
                                <div class="show-card-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                            <?php endif; ?>
                            
                            <a href="<?php the_permalink(); ?>" class="show-card-link">
                                View Show Details →
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <p>No shows found for this host.</p>
        <?php endif; 
        
        wp_reset_postdata();
        ?>
    </div>
</div>

<?php get_footer(); ?>