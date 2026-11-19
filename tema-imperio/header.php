<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="header"><div class="header__in">
 <a class="crest" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Início">
  <?php if (has_custom_logo()) echo wp_get_attachment_image(get_theme_mod('custom_logo'),'medium'); else echo '<span>BRASÃO</span>'; ?>
 </a>
 <button class="burger" id="burger" aria-expanded="false" aria-controls="menu" type="button">Menu</button>
 <nav class="menu" id="menu" aria-label="Principal">
  <?php wp_nav_menu(['theme_location'=>'primary','container'=>false,'menu_class'=>'','menu_id'=>'','fallback_cb'=>false,'depth'=>0]); ?>
  <form class="m-only" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>"><input type="search" name="s" placeholder="Procurar..." aria-label="Procurar no site"></form>
 </nav>
 <form class="search" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>"><input type="search" name="s" placeholder="Procurar..." aria-label="Procurar no site"></form>
</div></header>