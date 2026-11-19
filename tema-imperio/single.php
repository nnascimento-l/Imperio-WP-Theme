<?php get_header(); while(have_posts()): the_post();
 $cats = get_the_category(); $label = $cats ? $cats[0]->name : 'Notícia'; ?>
<main>
 <div class="phead"><div class="wrap">
  <span class="hero__tag"><?php echo esc_html($label); ?></span>
  <h1><?php the_title(); ?></h1>
  <p><?php echo esc_html(get_the_date('d \d\e F \d\e Y')); ?></p>
 </div></div>
 <article class="section"><div class="wrap entry">
  <?php if(has_post_thumbnail()) the_post_thumbnail('large'); ?>
  <?php the_content(); ?>
 </div></article>
</main>
<?php endwhile; get_footer();