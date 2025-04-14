<?php
defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Package\News\Controller\SinglePage\Dashboard\News\NewsList;


$category = NewsList::getOptions('news_category');
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

        <?php if (isset($pageListTitle) && $pageListTitle) {
        ?>
            <div class="ccm-block-page-list-header">
                <<?php echo $titleFormat; ?>><?php echo h($pageListTitle) ?></<?php echo $titleFormat; ?>>
            </div>
        <?php
        } ?>

        <?php if (isset($rssUrl) && $rssUrl) {
        ?>
            <a href="<?php echo $rssUrl ?>" target="_blank" class="ccm-block-page-list-rss-feed">
                <i class="fas fa-rss"></i>
            </a>
        <?php
        } ?>
        <section class="filter-sec select-box">
            <div class="container">
                <h2>Media releases, community partnerships, event information, and more!</h2>
                <form id="news_cat">
                  <div class="select-wrap">
                    <ul class="news_cat">
                      <li class="init">News</li>
                      <?php echo $form->text('category','News', array('readonly' => 'readonly','class' => 'form-select','placeholder' => 'News','onClick'=>'clickCat(this)')); ?>
                      <?php
                      if (is_array($category) && sizeof($category) > 0) {
                          foreach ($category as $cat) {
                              if ($cat == 'All') {
                                  continue;
                              }
                              echo '<li style="display: none;" onClick="selectCat(this)" value="' . $cat . '">' . $cat . '</li>';
                          }
                      }
                      ?>
                    </ul>
                  </div>
                </form>
            </div>
        </section>


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

            <section class="news-list">
              <div class="container">
          <?php  foreach ($pages as $page) {

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


               // $description = $page->getAttribute('news_description');
                $thumbnail = false;
                if ($displayThumbnail) {
                    $thumbnail = $page->getAttribute('news_thumbnail');
                }
                if (is_object($thumbnail) && $includeEntryText) {
                    $entryClasses = 'ccm-block-page-list-page-entry-horizontal';
                }

                $date = $dh->formatDateTime($page->getCollectionDatePublic(), true);

            ?>


            <div class="news-row row">
              <div class="col-md-12 col-12 news-item">
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
                                      <h3>  <a href="<?php echo h($url) ?>" target="<?php echo h($target) ?>"><?php echo h($title) ?></a></h3>
                                    <?php

                                    } ?>

                            <?php
                            } ?>

                            <?php if (isset($includeDate) && $includeDate) {
                            ?>
                                <div class="ccm-block-page-list-date"><?php echo h($date) ?></div>
                            <?php
                            } ?>

                            <?php if (isset($includeDescription) && $includeDescription) {
                            ?>
                                <div class="ccm-block-page-list-description"><?php echo $description ?></div>
                            <?php
                            } ?>

                            <?php if (isset($useButtonForLink) && $useButtonForLink) {
                            ?>

                                    <a href="<?php echo h($url) ?>" target="<?php echo h($target) ?>" class="<?php echo h($buttonClasses) ?>"><?php echo h($buttonLinkText) ?></a>

                            <?php
                            } ?>
                  <a class="btn-link" href="<?php echo h($url) ?>" title="READ MORE">READ MORE</a>

                    <?php
                    } ?>

</div>
</div>
            <?php
            } ?>



                  <?php if (count($pages) == 0) { ?>

                      <div class="ccm-block-page-list-no-pages"><?php echo h($noResultsMessage) ?></div>

                  <?php } ?>





          <?php if ($showPagination) { ?>

              <?php //echo $pagination; ?>

          <?php } ?>





          <?php if ($showPagination): ?>

          <?php

          $pagination = $list->getPagination();

          if ($pagination->getTotalPages() > 1) {

              $options = array(

                  'proximity'           => 2,

                  'prev_message'        => 'Prev',

                  'next_message'        => 'Next',

                  'dots_message'        => 'Dots',

                  'active_suffix'       => '',

                  'css_container_class' => 'pagination',

                  'css_prev_class'      => 'prev',

                  'css_next_class'      => 'next',

                  'css_disabled_class'  => 'disabled',

                  'css_dots_class'      => 'dots',

                  'css_active_class'    => 'active'

              );

              echo $pagination->renderDefaultView($options);

          }

          ?>

      <?php endif; ?>

    </div><!-- end .ccm-block-page-list-wrapper -->



        </section>
      <!-- end .ccm-block-page-list-pages -->


<script>
$(document).ready(function(){
  $(".select-wrap").on("click", "ul", function() {
      $(".select-wrap").toggleClass("open");
      $(this).closest(".select-wrap ul").children('li:not(.init)').toggle();
  });
  $(".select-wrap ul li.init").text($('#category').val());
});


function selectCat(c){

    var d = $(c).attr('value');
$(".select-wrap ul li.init").text(d);
    $('#category').val(d);
//    $('.news_cat ').slideUp();

    $('#news_cat').submit();



}
function clickCat(c){


    if (!$(c).next().hasClass('active')){

        $(c).addClass('active').next().slideDown().addClass('active');

    }else{

        $(c).removeClass('active').next().slideUp().removeClass('active');
    }




 }
// $(document).mouseup(function(e)
// {
//     var container = $("#category");
//
//     if (!container.is(e.target) && container.has(e.target).length === 0)
//     {
//         $(container).removeClass('active').next().slideUp().removeClass('active');
//     }
// });
</script>

<?php

} ?>
