<?php get_header();
$img = imp_mod('hero_image');
$e = isset($_GET['enviado']) ? $_GET['enviado'] : '';
?>
<main id="inicio">

 <section class="hero"<?php if($img) echo ' style="--hero-img:url('.esc_url($img).')"'; ?>>
  <div class="wrap">
   <span class="hero__tag"><?php echo esc_html(imp_mod('hero_tag')); ?></span>
   <h1><?php echo esc_html(imp_mod('hero_title')); ?></h1>
   <p><?php echo esc_html(imp_mod('hero_sub')); ?></p>
   <a class="btn" href="<?php echo esc_url(imp_url(imp_mod('hero_btn1_url'))); ?>"><?php echo esc_html(imp_mod('hero_btn1_text')); ?></a>
   <a class="btn btn--ghost" href="<?php echo esc_url(imp_url(imp_mod('hero_btn2_url'))); ?>"><?php echo esc_html(imp_mod('hero_btn2_text')); ?></a>
  </div>
 </section>

 <div class="imp-content"><?php while(have_posts()){ the_post(); the_content(); } ?></div>

 <section class="section news" id="noticias">
  <div class="wrap">
   <div class="news__head">
    <div><h2><?php echo esc_html(imp_mod('news_title')); ?></h2><p><?php echo esc_html(imp_mod('news_sub')); ?></p></div>
    <div class="news__tools">
     <a class="btn btn--ghost" href="<?php echo esc_url(imp_news_url()); ?>">Ver todos</a>
     <button class="car-btn" id="car-prev" type="button" aria-label="Anterior">&#8249;</button>
     <button class="car-btn" id="car-next" type="button" aria-label="Próximo">&#8250;</button>
    </div>
   </div>
   <?php
   $q = new WP_Query(['post_type'=>'post','posts_per_page'=>max(1,(int)imp_mod('news_count')),'ignore_sticky_posts'=>true]);
   if($q->have_posts()): $i=0; ?>
    <div class="carousel" id="carousel"><?php while($q->have_posts()){ $q->the_post(); get_template_part('card',null,['latest'=>$i++===0]); } ?></div>
   <?php else: ?><p>Nenhuma publicação ainda.</p><?php endif; wp_reset_postdata(); ?>
  </div>
 </section>

 <section class="section" id="contato">
  <div class="wrap split">
   <div>
    <h2><?php echo esc_html(imp_mod('contact_title')); ?></h2>
    <p><?php echo esc_html(imp_mod('contact_text')); ?></p>
    <p>WhatsApp: <?php echo esc_html(imp_mod('contact_wa')); ?><br>E-mail: <?php echo esc_html(imp_mod('contact_mail')); ?></p>
   </div>
   <form class="form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
    <input type="hidden" name="action" value="imp_form">
    <?php wp_nonce_field('imp_form','_n'); ?>
    <input type="text" name="site" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
    <label>Nome<input name="nome" required autocomplete="given-name"></label>
    <label>Sobrenome<input name="sobrenome" autocomplete="family-name"></label>
    <label class="full">E-mail<input name="email" type="email" required autocomplete="email"></label>
    <label class="full">Fale sobre você e seu propósito<textarea name="msg" rows="4"></textarea></label>
    <button class="btn full" type="submit">Enviar solicitação</button>
    <p class="msg full" role="status"><?php
     if($e==='ok') echo 'Solicitação enviada! Responderemos pelo e-mail informado.';
     elseif($e==='erro') echo 'Não foi possível enviar. Confira os campos e tente novamente.';
    ?></p>
   </form>
  </div>
 </section>

</main>

<script>
(function(){
 var c=document.getElementById('carousel'); if(!c) return;
 var p=document.getElementById('car-prev'), n=document.getElementById('car-next');
 if(c.scrollWidth<=c.clientWidth+4){ p.hidden=true; n.hidden=true; return; }
 function step(){ var k=c.querySelector('.card'); return k?k.getBoundingClientRect().width+22:300; }
 function go(d){
  var max=c.scrollWidth-c.clientWidth;
  if(d>0 && c.scrollLeft>=max-4) c.scrollTo({left:0});
  else if(d<0 && c.scrollLeft<=4) c.scrollTo({left:max});
  else c.scrollBy({left:d*step()});
 }
 p.onclick=function(){go(-1)}; n.onclick=function(){go(1)};
 var hold=false;
 c.addEventListener('mouseenter',function(){hold=true});
 c.addEventListener('mouseleave',function(){hold=false});
 c.addEventListener('touchstart',function(){hold=true},{passive:true});
 if(!window.matchMedia('(prefers-reduced-motion:reduce)').matches){
  setInterval(function(){ if(!hold) go(1); },5000);
 }
})();
</script>
<?php get_footer();