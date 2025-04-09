<?php defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header_top.php');?>

<header>
  <div class="row no-gutters g-0">
    <div class="top-bar col-12">
        <?php $a = new GlobalArea('Top Nav'); $a->display(); ?>
      <?php $a = new GlobalArea(' Account Button'); $a->display(); ?>
    </div>
    <div class="col-md-3 col-12 col-logo">
      <?php $a = new GlobalArea('Logo'); $a->display(); ?>

    </div>
    <div class="col-md-9 col-12 nav-right">
      <div class="nav-outer">
      <?php $a = new GlobalArea('Main Nav'); $a->display(); ?>
      </div>
      <div class="search-box">  <?php $a = new GlobalArea('Search'); $a->display(); ?></div>
      <a class="menu-icon d-xl-none d-block" href="javascript:void(0);">
        <span></span>
        <span></span>
        <span></span>
      </a>
    </div>
		<?php
		$c = Page::getCurrentPage();
		$alertBox = $c->getAttribute('alert_box');
		// checked - string(1) "1"
		// unchecked - string(1) "0"
	/*	if ($alertBox != '') {
		    echo '<style type="text/css">
				.home-banner {
		    margin-top: 128px !important;
		}</style>';
		} */
		 ?>
  </div>
</header>
<div class="mobile-menu">
  <a class="close-icon" href="javascript:void(0);">
    <span></span>
    <span></span>
    <span></span>
  </a>
  <div class="mobile-menu-inner">
    <div class="mobile-menu-top">
      <?php $stack = Stack::getByName('Mobile Menu Top'); $stack->display(); ?>
    </div>
    <div class="mobile-menu-btm">
      <?php $stack = Stack::getByName('Mobile Menu'); $stack->display(); ?>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
  $(window).scroll(function() {
    if ($(window).scrollTop() >= 200) {
        $("header").addClass("header_fixed");
    }else{
        $("header").removeClass("header_fixed");
    }
  });
  $('.menu-icon').click(function(){
    $('body').addClass('nav-open');
$('.menu-icon').addClass('active');
$('.mobile-menu').addClass('open');
});
$('.close-icon').click(function(){
  $('body').removeClass('nav-open');
$('.menu-icon').removeClass('active');
$('.mobile-menu').removeClass('open');
});
      if ($('#cyear').length) { $("#cyear").html(new Date().getFullYear()); }
         $('.arrow_up').click(function(){
           $("html, body").animate({
             scrollTop: 0
             }, 600);
         })



});

</script>
<?php 	$c = Page::getCurrentPage();
$alert_box = $c->getAttribute('alert_box');
if ($alert_box != '') {

    echo '<div class="alert_box"><div class="alert_box_text">';
    echo '<a href="';
    echo $c->getCollectionAttributeValue('outage_alert_link');
    echo '">';
    echo $c->getCollectionAttributeValue('alert_box_text');
    echo '</a>';
    echo '</div><button aria-label="Close Alert Box" class="alert_box_close">X</button></div>';

} else {
    echo '';
}
?>



<script>
    $(".alert_box_close").click(function(){
    $(".alert_box").hide();
    $("body").removeClass("alert-covid19");
});
</script>
<!-- Start AccessiBe Script -->
<script> (function(){ var s = document.createElement('script'); var h = document.querySelector('head') || document.body; s.src = 'https://acsbapp.com/apps/app/dist/js/app.js'; s.async = true; s.onload = function(){ acsbJS.init({ statementLink : '', footerHtml : '', hideMobile : false, hideTrigger : false, disableBgProcess : false, language : 'en', position : 'left', leadColor : '#474747', triggerColor : '#e31122', triggerRadius : '50%', triggerPositionX : 'left', triggerPositionY : 'bottom', triggerIcon : 'people', triggerSize : 'medium', triggerOffsetX : 20, triggerOffsetY : 65, mobile : { triggerSize : 'small', triggerPositionX : 'left', triggerPositionY : 'bottom', triggerOffsetX : 10, triggerOffsetY : 65, triggerRadius : '50%' } }); }; h.appendChild(s); })(); </script>
<!-- End AccessiBe Script -->
