<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
</div>
<script type="text/javascript" src="<?php echo $this->getThemePath(); ?>/js/lib/tether.min.js"></script>
<script type="text/javascript" src="<?php echo $this->getThemePath(); ?>/slick/slick.min.js"></script>
<script type="text/javascript" src="<?php echo $this->getThemePath(); ?>/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $this->getThemePath(); ?>/js/parallax.js"></script>
<script type="text/javascript" src="<?php echo $this->getThemePath(); ?>/js/lightbox.js"></script>
<script src="<?php echo $view->getThemePath()?>/js/jquery.meanmenu.js"></script>
<script>
   $( document ).ready(function() {
    $('a[href^="http://"]').not('a[href*=".connexusenergy.com"]').attr('rel','noopener noreferrer');
    $('a[target^="_blank"]').attr('rel','noopener noreferrer');
  $('.banner-slider').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: true,
    infinite: true,
    fade: true,
    dots: true,
    arrows: true,
    speed: 500,
    appendArrows: $('.slick-slider-dots'),
    appendDots: $('.slick-slider-dots')
});
$('.features-slider').slick({
    slidesToShow: 3,
  slidesToScroll: 1,
  infinite: true,
  arrows: true,
  dots: false,
  variableWidth: true
});

$('.testimonial-slider').slick({
    slidesToShow: 1,
  slidesToScroll: 1,
  autoplay: true,
  arrows: true,
  autoplaySpeed: 2000
});

});
</script>

<?php View::element('footer_required'); ?>

</body>
</html>
