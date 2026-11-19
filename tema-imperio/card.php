<?php
$latest = !empty($args['latest']);
$cats = get_the_category(); $c = $cats ? $cats[0] : null;
$label = $c ? $c->name : (get_post_type()==='page' ? 'Página' : 'Notícia');
$blue = !$c || $c->slug !== 'decretos';
?>
<article class="card<?php echo $latest ? ' is-latest' : ''; ?>">
 <a class="thumb" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1"><?php if(has_post_thumbnail()) the_post_thumbnail('medium_large'); ?></a>
 <div class="meta">
  <span class="chip<?php echo $blue ? ' chip--n' : ''; ?>"><?php echo esc_html($label); ?></span>
  <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('d \d\e M \d\e Y')); ?></time>
 </div>
 <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
 <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?></p>
 <a class="more" href="<?php the_permalink(); ?>">Ler mais</a>
</article>