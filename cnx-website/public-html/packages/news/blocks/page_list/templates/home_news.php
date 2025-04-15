<?php
defined('C5_EXECUTE') or die('Access Denied.');


$c = Page::getCurrentPage();

/** @var \Concrete\Core\Utility\Service\Text $th */
$th = Core::make('helper/text');
/** @var \Concrete\Core\Localization\Service\Date $dh */
$dh = Core::make('helper/date');

if (is_object($c) && $c->isEditMode() && $controller->isBlockEmpty()) {
?>
    <div class="ccm-edit-mode-disabled-item"><?php echo t('Empty Page List Block.') ?></div>
<?php
} else {
?>





        <?php if (isset($rssUrl) && $rssUrl) {
        ?>
            <a href="<?php echo $rssUrl ?>" target="_blank" class="ccm-block-page-list-rss-feed">
                <i class="fas fa-rss"></i>
            </a>
        <?php
        } ?>


            <?php

            $includeEntryText = false;
            if (
                (isset($includeName) && $includeName)
                ||
                (isset($includeDescription) && $includeDescription)
                ||
                (isset($useButtonForLink) && $useButtonForLink)
            ) {
                $includeEntryText = true;
            }?>
            <section class="news-sec">
              <div class="container">
                <div class="row">
                  <?php if (isset($pageListTitle) && $pageListTitle) {
                  ?>
                    <div class="col-md-3 col-12 col-cont">
                          <h2><?php echo h($pageListTitle) ?></h2>
                  </div>
                  <?php
                  } ?>
          <?php   foreach ($pages as $page) {

                // Prepare data for each page being listed...
                $buttonClasses = 'ccm-block-page-list-read-more';
                $entryClasses = 'ccm-block-page-list-page-entry';
                $title = $page->getCollectionName();
                $target = '_self';
                if ($page->getCollectionPointerExternalLink() != '') {
                    $url = $page->getCollectionPointerExternalLink();
                    if ($page->openCollectionPointerExternalLinkInNewWindow()) {
                        $target = '_blank';
                    }
                } else {
                    $url = $page->getCollectionLink();
                    $target = $page->getAttribute('nav_target');
                }
                $description = $page->getCollectionDescription();
                $description = $controller->truncateSummaries ? $th->wordSafeShortText($description, $controller->truncateChars) : $description;
                $thumbnail = false;
                if ($displayThumbnail) {
                    $thumbnail = $page->getAttribute('news_thumbnail');
                }
                if (is_object($thumbnail) && $includeEntryText) {
                    $entryClasses = 'ccm-block-page-list-page-entry-horizontal';
                }

                $date = $dh->formatDateTime($page->getCollectionDatePublic(), true);

            ?>

                <div class="col-md-3 col-12 col-news">
  <h4>
                    <?php if (is_object($thumbnail)) {
                    ?>

                            <?php
                            $img = Core::make('html/image', ['f' => $thumbnail]);
                            $tag = $img->getTag();
                            $tag->addClass('img-fluid');
                            echo $tag; ?>

                    <?php
                    } ?>

                    <?php if ($includeEntryText) {
                    ?>


                            <?php if (isset($includeName) && $includeName) {
                            ?>

                                    <?php if (isset($useButtonForLink) && $useButtonForLink) {
                                    ?>
                                        <?php echo h($title); ?>
                                    <?php

                                    } else {
                                    ?>
                                        <a href="<?php echo h($url) ?>" target="<?php echo h($target) ?>"><?php echo h($title) ?></a>
                                    <?php

                                    } ?>

                            <?php
                            } ?>

                            <?php if (isset($includeDate) && $includeDate) {
                            ?>
                                <div class="ccm-block-page-list-date"><?php echo h($date) ?></div>
                            <?php
                            } ?>

                            <?/*php if (isset($includeDescription) && $includeDescription) {
                            ?>
                                <div class="ccm-block-page-list-description"><?php echo h($description) ?></div>
                            <?php
                          } */?>

                            <?php if (isset($useButtonForLink) && $useButtonForLink) {
                            ?>

                                    <a href="<?php echo h($url) ?>" target="<?php echo h($target) ?>" class="<?php echo h($buttonClasses) ?>">
                                      <?php echo h($buttonLinkText) ?></a>

                            <?php
                          }?>
                            </h4>
  <a class="btn-link" href="<?php echo h($url) ?>" title="VIEW MORE NEWS" >Read More</a>

                    <?php
                    } ?>

</div>
            <?php
            } ?>

</div>
</div>
</section>
<!-- end .ccm-block-page-list-wrapper -->



<?php

} ?>
