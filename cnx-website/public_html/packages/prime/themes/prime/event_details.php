<?php defined('C5_EXECUTE') or die(_("Access Denied."));
$view->inc('elements/header.php');
$page = Page::getCurrentPage();
$ih = Loader::helper("image"); ?>

<?php $a = new GlobalArea('Events Banner');
$a->display();?>
<section class="news-details">
  <div class="container">
  <?php $a = new Area('Main');
$a->display();?>
    <?php

    $thumbnail = $page->getAttribute('news_thumbnail');
    $title = $page->getCollectionName();
    $description = $page->getAttribute('news_description');
    $category = $page->getAttribute('news_category');
    ?>

<?php echo $description ?>
</div>

</section>


<?php $view->inc('elements/footer.php'); ?>
