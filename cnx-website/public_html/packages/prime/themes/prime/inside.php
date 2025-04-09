<?php defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php');
$c = Page::getCurrentPage();
 ?>

 <?php $a = new Area('Banner'); $a->display(); ?>



 <?php $a = new Area('Inside Service Highlight'); ?>
<?php if ($a->getTotalBlocksInArea($c) > 0 || $c->isEditMode()) { ?>
 <section class="service-boxes inside-highlights">
    <div class="container">
<?php $a = new Area('Inside Service Highlight');
$a->display();?>
</div>
</section>
<?php }?>

<section class="inside-content">
   <?php $a = new Area('Main');
   $a->enableGridContainer();
   $a->display($c); ?>
</section>
<?php $this->inc('elements/footer.php'); ?>
