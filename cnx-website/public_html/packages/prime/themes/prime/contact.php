<?php
defined('C5_EXECUTE') or die("Access Denied.");
$view->inc('elements/header.php');?>
<section class="clear contact">
  <div class="container">
    <div class="row">
          <div class="col-12 col-md-6" data-aos="fade-left">
          <?php $a = new Area('Left Content');
            $a->display();?>
          </div>
          <div class="col-12 col-md-6" data-aos="fade-right">
          <?php $a = new Area('Form');
            $a->display();?>
          </div>
    </div>
  </div>
</section>
<div class="clear">
<?php $a = new Area('Contact Cta');
  $a->display();?>
</div>
<?php $view->inc('elements/footer.php');?>
