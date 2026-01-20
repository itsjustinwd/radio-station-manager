<?php get_header(); ?>

<div class="show-single">
    <?php while (have_posts()) : the_post(); ?>
        
        <h1><?php the_title(); ?></h1>
        
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('large'); ?>
        <?php endif; ?>
        
        <div class="show-content">
            <?php the_content(); ?>
        </div>
        
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>