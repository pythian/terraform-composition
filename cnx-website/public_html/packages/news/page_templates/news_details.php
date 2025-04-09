<?php defined('C5_EXECUTE') or die(_("Access Denied."));
$view->inc('elements/header.php');
$page = Page::getCurrentPage();
$ih = Loader::helper("image"); ?>

<?php $a = new Area('News Banner');
$a->display();?>
<section class="news-details">
  <div class="container">
    <?php

    $thumbnail = $page->getAttribute('news_thumbnail');
    $title = $page->getCollectionName();
    $description = $page->getAttribute('news_description');
    $category = $page->getAttribute('news_category');
    ?>

    <h2><?//= $title; ?></h2>
    <h3><?= $category; ?></h3>
    <?php if (is_object($thumbnail)) {
    ?>
        <div class="ccm-block-page-list-page-entry-thumbnail">
            <?php
            $img = Core::make('html/image', ['f' => $thumbnail]);
            $tag = $img->getTag();
            $tag->addClass('img-fluid');
            echo $tag; ?>
        </div>
    <?php
    } ?>
<?php echo $description ?>
</div>

</section>


<?php $view->inc('elements/footer.php'); ?>
