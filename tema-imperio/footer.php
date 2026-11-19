<footer><div class="wrap"><?php echo esc_html(imp_mod('footer')); ?></div></footer>
<script>
(function(){
 var b=document.getElementById('burger'),m=document.getElementById('menu');
 b.addEventListener('click',function(){var o=m.classList.toggle('open');b.setAttribute('aria-expanded',o);});
 document.querySelectorAll('.menu .menu-item-has-children>a').forEach(function(a){
  a.addEventListener('click',function(e){
   var h=a.getAttribute('href');if(!h||h==='#')e.preventDefault();
   var li=a.parentNode,was=li.classList.contains('open');
   document.querySelectorAll('.menu .open').forEach(function(x){x.classList.remove('open');});
   if(!was)li.classList.add('open');
  });
 });
 document.addEventListener('click',function(e){
  if(!e.target.closest('.menu'))document.querySelectorAll('.menu .open').forEach(function(x){x.classList.remove('open');});
 });
})();
</script>
<?php wp_footer(); ?>
</body>
</html>