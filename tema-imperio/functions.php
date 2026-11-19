<?php
if (!defined('ABSPATH')) exit;

/* ===== Campos da Home (Aparência → Personalizar → Conteúdo da Home) ===== */
function imp_fields(){ return [
 'hero_tag'      =>['Topo: etiqueta','Império'],
 'hero_title'    =>['Topo: título','A grande joia!'],
 'hero_sub'      =>['Topo: subtítulo','[Subtítulo de duas linhas apresentando o projeto e o Império.]','textarea'],
 'hero_btn1_text'=>['Topo: botão 1 (texto)','Conheça o Império'],
 'hero_btn1_url' =>['Topo: botão 1 (link)','/sobre/'],
 'hero_btn2_text'=>['Topo: botão 2 (texto)','Últimos decretos'],
 'hero_btn2_url' =>['Topo: botão 2 (link)','#noticias'],
 'news_title'    =>['Decretos: título','Decretos e Notícias'],
 'news_sub'      =>['Decretos: subtítulo','As publicações mais recentes do Império.'],
 'news_count'    =>['Carrossel: quantas publicações carregar','8','number'],
 'contact_title' =>['Passaporte: título','Visite o Império e solicite seu passaporte'],
 'contact_text'  =>['Passaporte: texto','Preencha o formulário e responderemos pelo e-mail informado.'],
 'contact_wa'    =>['Passaporte: WhatsApp exibido','(00) 00000-0000'],
 'contact_mail'  =>['Passaporte: e-mail exibido','contato@exemplo.com'],
 'contact_to'    =>['Passaporte: e-mail que RECEBE as solicitações (vazio = e-mail do administrador)',''],
 'footer'        =>['Rodapé: texto','© 2026 Império. Todos os direitos reservados.'],
];}
function imp_mod($k){ $f=imp_fields(); return get_theme_mod('imp_'.$k, isset($f[$k])?$f[$k][1]:''); }
function imp_url($u){ return ($u!=='' && $u[0]==='/') ? home_url($u) : $u; }

add_action('customize_register', function($c){
 $c->add_section('imp_home',['title'=>'Conteúdo da Home','priority'=>25]);
 $c->add_setting('imp_hero_image',['default'=>'','sanitize_callback'=>'esc_url_raw']);
 $c->add_control(new WP_Customize_Image_Control($c,'imp_hero_image',['section'=>'imp_home','label'=>'Topo: imagem de fundo']));
 foreach(imp_fields() as $k=>$f){
  $c->add_setting('imp_'.$k,['default'=>$f[1],'sanitize_callback'=>'sanitize_text_field']);
  $c->add_control('imp_'.$k,['section'=>'imp_home','label'=>$f[0],'type'=>isset($f[2])?$f[2]:'text']);
 }
});

/* ===== Configuração do tema ===== */
add_action('after_setup_theme', function(){
 add_theme_support('title-tag');
 add_theme_support('post-thumbnails');
 add_theme_support('custom-logo',['height'=>240,'width'=>180,'flex-width'=>true,'flex-height'=>true]);
 add_theme_support('html5',['search-form','gallery','caption']);
 add_theme_support('responsive-embeds');
 add_post_type_support('page','excerpt'); // o "Resumo" da página vira o subtítulo do topo
 register_nav_menus(['primary'=>'Menu principal']);
});
add_action('wp_enqueue_scripts', function(){
 wp_enqueue_style('imp-fonts','https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700&family=Spectral:wght@400;500;600&display=swap',[],null);
 wp_enqueue_style('imperio',get_stylesheet_uri(),['imp-fonts'],filemtime(get_stylesheet_directory().'/style.css'));
});

/* ===== Formulário "Solicite seu passaporte" ===== */
add_action('admin_post_nopriv_imp_form','imp_form_handler');
add_action('admin_post_imp_form','imp_form_handler');
function imp_form_handler(){
 $go=function($s){ wp_safe_redirect(home_url('/?enviado='.$s.'#contato')); exit; };
 if(empty($_POST['_n'])||!wp_verify_nonce($_POST['_n'],'imp_form')||!empty($_POST['site'])) $go('erro');
 $n=sanitize_text_field(wp_unslash($_POST['nome']??''));
 $s=sanitize_text_field(wp_unslash($_POST['sobrenome']??''));
 $e=sanitize_email(wp_unslash($_POST['email']??''));
 $m=sanitize_textarea_field(wp_unslash($_POST['msg']??''));
 if(!$n||!is_email($e)) $go('erro');
 $to=imp_mod('contact_to'); if(!is_email($to)) $to=get_option('admin_email');
 $ok=wp_mail($to,'Solicitação de passaporte – '.$n.' '.$s,"Nome: $n $s\nE-mail: $e\n\nMensagem:\n$m",['Reply-To: '.$e]);
 $go($ok?'ok':'erro');
}

/* ===== Blocos prontos (usados nas páginas iniciais e nos Padrões) ===== */
function imp_g($cls,$in){ $a=$cls?' {"className":"'.$cls.'"}':''; $c=$cls?' '.$cls:''; return "<!-- wp:group$a --><div class=\"wp-block-group$c\">$in</div><!-- /wp:group -->"; }
function imp_h($t,$l=2){ $a=$l==2?'':' {"level":'.$l.'}'; return "<!-- wp:heading$a --><h$l class=\"wp-block-heading\">$t</h$l><!-- /wp:heading -->"; }
function imp_p($t,$cls=''){ $a=$cls?' {"className":"'.$cls.'"}':''; $c=$cls?' class="'.$cls.'"':''; return "<!-- wp:paragraph$a --><p$c>$t</p><!-- /wp:paragraph -->"; }
function imp_i($cls){ return "<!-- wp:image {\"className\":\"$cls\"} --><figure class=\"wp-block-image $cls\"><img alt=\"\"/></figure><!-- /wp:image -->"; }
function imp_b($t,$u){ return "<!-- wp:buttons --><div class=\"wp-block-buttons\"><!-- wp:button {\"className\":\"is-style-outline\"} --><div class=\"wp-block-button is-style-outline\"><a class=\"wp-block-button__link wp-element-button\" href=\"$u\">$t</a></div><!-- /wp:button --></div><!-- /wp:buttons -->"; }
function imp_sec($in,$alt=false){ return imp_g('imp-section'.($alt?' imp-alt':''),$in); }
function imp_split($title,$t1,$t2,$rev=false,$alt=false,$btn=''){
 $txt=imp_g('',imp_h($title).imp_p($t1).($t2?imp_p($t2):'').$btn); $img=imp_i('imp-frame');
 return imp_sec(imp_g('imp-split',$rev?$txt.$img:$img.$txt),$alt);
}
function imp_big($num,$title,$t1){ return imp_sec(imp_g('imp-split',imp_p($num,'imp-big').imp_g('',imp_h($title).imp_p($t1))),true); }
function imp_wide($title){ return imp_sec(imp_h($title).imp_i('imp-wide')); }
function imp_cards($title,$items,$alt=false){
 $c=''; foreach($items as $i) $c.=imp_g('imp-box',imp_h($i[0],3).imp_p($i[1]));
 return imp_sec(($title?imp_h($title):'').imp_g('imp-grid3',$c),$alt);
}
function imp_members(){
 $m=''; foreach([['Ivan VII','Imperador e Autocrata de Todas as Rússias'],['Maria Carolina Ferdinandovna','Imperatriz de Todas as Rússias']] as $x)
  $m.=imp_g('imp-box imp-member',imp_i('imp-portrait').imp_h($x[0],3).imp_p($x[1]));
 return imp_sec(imp_g('imp-grid3 imp-grid3--2',$m));
}

add_action('init', function(){
 register_block_pattern_category('imperio',['label'=>'Império']);
 $t1='[Texto 1]'; $t2='[Texto 2]';
 $p=[
  'imagem-texto'=>['Seção: imagem à esquerda + texto',imp_split('[Título da seção]',$t1,$t2)],
  'texto-imagem'=>['Seção: texto + imagem à direita (fundo destacado)',imp_split('[Título da seção]',$t1,$t2,true,true)],
  'cartoes'=>['Seção: 3 cartões',imp_cards('[Título da seção]',[['[Título 1]','[Descrição]'],['[Título 2]','[Descrição]'],['[Título 3]','[Descrição]']])],
  'numero'=>['Seção: número grande + texto',imp_big('2018','[Título]',$t1)],
  'imagem-larga'=>['Seção: imagem/mapa largo',imp_wide('[Título da seção]')],
  'membros'=>['Seção: retratos (Casa Imperial)',imp_members()],
 ];
 foreach($p as $s=>$a) register_block_pattern('imperio/'.$s,['title'=>$a[0],'categories'=>['imperio'],'content'=>$a[1]]);
});

/* ===== Páginas iniciais (criadas uma única vez) ===== */
function imp_pages(){
 $t1='[Texto 1]'; $t2='[Texto 2]';
 return [
 'inicio'=>['Início','',imp_split('[Título da seção sobre]','[Primeiro parágrafo de apresentação do Império.]','[Segundo parágrafo com mais detalhes.]',false,false,imp_b('Saiba mais',home_url('/sobre/')))],
 'sobre'=>['Sobre o Império','Origem, fundação e configuração atual.',
   imp_split('O que é o Império Russo',$t1,$t2).imp_big('2018','Fundação',$t1).
   imp_cards('Configuração atual',[['Governo','[Descrição]'],['Capital','[Descrição]'],['Cidadãos','[Descrição]']])],
 'territorio'=>['Território','Mapa oficial, limites e divisão administrativa.',
   imp_wide('Mapa oficial').imp_split('Delimitação territorial',$t1,$t2,true,true).
   imp_cards('Divisão administrativa',[['[Divisão 1]','[Descrição e sede administrativa]'],['[Divisão 2]','[Descrição e sede administrativa]'],['[Divisão 3]','[Descrição e sede administrativa]']])],
 'simbolos-nacionais'=>['Símbolos Nacionais','Brasão, bandeira, hino, lema e demais símbolos oficiais.',
   imp_split('Brasão de armas',$t1,$t2).imp_split('Bandeira',$t1,$t2,true,true).
   imp_cards('',[['Hino','[Player de áudio do hino: insira aqui um bloco “Áudio”]'],['Lema','[Lema oficial]'],['Outros símbolos','[Descrição dos demais símbolos oficiais]']])],
 'o-czar'=>['O Czar','O Imperador e Autocrata de Todas as Rússias.',imp_split('Título da seção',$t1,$t2,true)],
 'a-casa-imperial'=>['A Casa Imperial','A família reinante e a linha de sucessão.',imp_members().imp_sec(imp_h('Linhagem e sucessão').imp_p($t1).imp_p($t2),true)],
 'diplomacia'=>['Diplomacia','Relações exteriores e acordos internacionais.',
   imp_split('[Título da seção]',$t1,$t2).imp_cards('Representações',[['[País / Organização 1]','[Descrição]'],['[País / Organização 2]','[Descrição]'],['[País / Organização 3]','[Descrição]']],true)],
 ];
}
function imp_setup_site(){
 foreach(['decretos'=>'Decretos','noticias'=>'Notícias'] as $s=>$n) if(!term_exists($s,'category')) wp_insert_term($n,'category',['slug'=>$s]);
 $dec=get_term_by('slug','decretos','category'); $not=get_term_by('slug','noticias','category');
 if($dec) update_option('default_category',$dec->term_id);          // novo post = Decreto por padrão
 if($not && get_post_type(1)==='post') wp_set_post_categories(1,[$not->term_id]); // "Olá, mundo!" vira Notícia
 $ids=[];
 foreach(imp_pages() as $slug=>$p){
  $ex=get_page_by_path($slug);
  $ids[$slug]=$ex?$ex->ID:wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_title'=>$p[0],'post_name'=>$slug,'post_excerpt'=>$p[1],'post_content'=>wp_slash($p[2])]);
 }
 update_option('show_on_front','page'); update_option('page_on_front',$ids['inicio']);
 if(!wp_get_nav_menu_object('Menu principal')){
  $mid=wp_create_nav_menu('Menu principal');
  $add=function($t,$u,$parent=0) use($mid){ return wp_update_nav_menu_item($mid,0,['menu-item-title'=>$t,'menu-item-url'=>$u,'menu-item-type'=>'custom','menu-item-status'=>'publish','menu-item-parent-id'=>$parent]); };
  $pg=function($slug,$parent) use($mid,$ids){ wp_update_nav_menu_item($mid,0,['menu-item-object-id'=>$ids[$slug],'menu-item-object'=>'page','menu-item-type'=>'post_type','menu-item-status'=>'publish','menu-item-parent-id'=>$parent]); };
  $o=$add('O Império','#'); foreach(['sobre','territorio','simbolos-nacionais'] as $s) $pg($s,$o);
  $m=$add('Monarquia','#'); foreach(['o-czar','a-casa-imperial'] as $s) $pg($s,$m);
  $pg('diplomacia',0);
  $add('Contato',home_url('/#contato'));
  $add('Decretos e Notícias',home_url('/#noticias'));
  $loc=get_theme_mod('nav_menu_locations',[]); $loc['primary']=$mid; set_theme_mod('nav_menu_locations',$loc);
 }
}
add_action('admin_init', function(){
 if(current_user_can('manage_options') && !get_option('imp_setup_done')){ update_option('imp_setup_done',1); imp_setup_site(); }
});

/* ===== Página "Decretos e Notícias" (lista completa) ===== */
function imp_news_url(){ $p=get_option('page_for_posts'); return $p?get_permalink($p):home_url('/'); }

add_action('pre_get_posts', function($q){
 if(!is_admin() && $q->is_main_query() && $q->is_home()){
  $q->set('posts_per_page',12);
 }
});

add_action('admin_init', function(){
 if(!current_user_can('manage_options') || get_option('imp_setup_v2')) return;
 update_option('imp_setup_v2',1);
 $pg=get_page_by_path('decretos-e-noticias');
 $id=$pg ? $pg->ID : wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_title'=>'Decretos e Notícias','post_name'=>'decretos-e-noticias']);
 update_option('page_for_posts',$id);
 $m=wp_get_nav_menu_object('Menu principal');
 if($m) foreach(wp_get_nav_menu_items($m->term_id) as $it){
  if($it->type==='custom' && $it->title==='Decretos e Notícias' && strpos($it->url,'#noticias')!==false){
   wp_update_nav_menu_item($m->term_id,$it->ID,['menu-item-title'=>'Decretos e Notícias','menu-item-object-id'=>$id,'menu-item-object'=>'page','menu-item-type'=>'post_type','menu-item-status'=>'publish','menu-item-position'=>$it->menu_order,'menu-item-parent-id'=>$it->menu_item_parent]);
  }
 }
});

/* ===== Remove o prefixo "Categoria:" dos títulos das páginas de arquivo ===== */
add_filter('get_the_archive_title', function($t){ 
 return is_category() ? single_cat_title('',false) : $t; 
}); 