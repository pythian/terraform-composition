<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php $this->inc('elements/header.php');
$c = Page::getCurrentPage();?>

<?php $a = new Area('Banner'); $a->display(); ?>
<section class="righ-sidebar">
  <div class="container">
    <div class="row">
        <div class="col-md-9 col-12 col-left">
          <?php $a = new Area('Main');

          $a->enableGridContainer();
          if ($c->getCollectionParentID() > 0) {
               $a->display($c);
          } else {
               $a->display();
          }
           ?>
      </div>
        <div class="col-md-3 col-12 col-right">
          <div class="sidebar-outer">
            <?php $a = new Area('sidebar'); $a->display(); ?>
          </div>
        </div>
    </div>
  </div>
</section>


<?php $this->inc('elements/footer.php'); ?>
