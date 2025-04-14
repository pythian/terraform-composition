<?php defined('C5_EXECUTE') or die("Access Denied.");
use \Concrete\Core\Block\BlockController;
use \Concrete\Core\Editor\LinkAbstractor;
$this->inc('elements/header.php');?>
<?php $cp=Page::getByID($c->cParentID);?>

  <?php $a = new GlobalArea('Banner'); $a->display(); ?>

<section class="righ-sidebar">
  <div class="container">
    <div class="row">
      <div class="col-md-9 col-12 col-left">

          <?php echo LinkAbstractor::translateFrom(LinkAbstractor::translateTo($c->getAttribute('service_content')));?>


          <?php $a = new GlobalArea('Service Gallery'); $a->display(); ?>
        </div>
        <div class="col-md-3 col-12 col-right">
            <div class="sidebar-outer">
           <?php $a = new GlobalArea('Form Sidebar');
          $a->display(); ?>

    <?php $a = new GlobalArea('sidebar'); $a->display(); ?>


 </div>
    </div>
  </div>
</section>

<?php  $this->inc('elements/footer.php'); ?>
