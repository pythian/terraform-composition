<?php defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php');
$c = Page::getCurrentPage();
 ?>

 <?php $a = new Area('Banner'); $a->display(); ?>


 <?php $a = new Area('Inside Service');
  // $a->enableGridContainer();
   $a->display($c); ?>
 <?php $a = new Area('Inside Service Highlight'); ?>
<?php if ($a->getTotalBlocksInArea($c) > 0 || $c->isEditMode()) { ?>
 <section class="service-boxes inside-highlights">
    <div class="container">
<?php $a = new Area('Inside Service Highlight');
$a->enableGridContainer();
$a->display($c);?>
</div>
</section>
<?php }?>


   <?php $a = new Area('Main');
  // $a->enableGridContainer();
   $a->display($c); ?>
   <?php $a = new Area('Main Grid');
  $a->enableGridContainer();
   $a->display($c); ?>
<?php $this->inc('elements/footer.php'); ?>
