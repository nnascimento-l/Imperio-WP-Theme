<?php get_header(); ?>
<main>
 <div class="phead"><div class="wrap">
  <span class="hero__tag"><?php echo esc_html(imp_mod('hero_tag')); ?></span>
  <h1><?php
   if(is_search()) echo 'Resultados para “'.esc_html(get_search_query()).'”';
   elseif(is_archive()) echo wp_kses_post(get_the_archive_title());
   else echo esc_html(imp_mod('news_title')); ?></h1>
 </div></div>
 <section class="section"><div class="wrap">
  <?php if(have_posts()): ?>
   <div class="track"><?php while(have_posts()){ the_post(); get_template_part('card'); } ?></div>
   <div class="pager"><?php the_posts_pagination(['prev_text'=>'Anterior','next_text'=>'Próxima']); ?></div>
  <?php else: ?><p>Nada encontrado.</p><?php endif; ?>
 </div></section>
</main>
<?php get_footer();