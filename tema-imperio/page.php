<?php get_header(); while(have_posts()): the_post(); ?>
<main>
 <div class="phead"><div class="wrap">
  <span class="hero__tag"><?php echo esc_html(imp_mod('hero_tag')); ?></span>
  <h1><?php the_title(); ?></h1>
  <?php if(has_excerpt()) echo '<p>'.esc_html(get_the_excerpt()).'</p>'; ?>
 </div></div>
 <div class="imp-content"><?php the_content(); ?></div>
</main>
<?php endwhile; get_footer();