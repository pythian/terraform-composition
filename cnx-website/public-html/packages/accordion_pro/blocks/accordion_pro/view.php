<?php
/**
 * @project:   Counter Up add-on for concrete5
 *
 * @author     Fabian Bitter
 * @copyright  (C) 2016 Bitter Webentwicklung (www.bitter-webentwicklung.de)
 * @version    1.0.0.5
 */

defined('C5_EXECUTE') or die('Access denied');

?>
<section class="accordion-sec">
    <div class="container">
<?php if (is_object(Page::getCurrentPage()) && Page::getCurrentPage()->isEditMode()): ?>
    <div class="ccm-edit-mode-disabled-item">
        <?php echo t('Accordion is disabled in edit mode.') ?>
    </div>
<?php else: ?>
  <div class="text-wrap">
    <h2>Frequently Asked Questions</h2>
  </div>
    <div id="faq_acc" class="faq accordion-container style-preset-<?php echo intval($stylePresetId); ?>" data-collapse="<?php echo intval($collapse); ?>"  data-scroll-to-active-item="<?php echo intval($scrollToActiveItem); ?>" data-animation-duration="<?php echo intval($animationDuration); ?>">
        <?php foreach ($items as $item): ?>
            <div class="accordion-item <?php echo $item->getIsOpen() ? "open" : ""; ?>">
                <div class="accordion-header <?php echo is_object($stylePreset) ? $stylePreset->getTitleIconOrientation() : ""; ?>">
                    <div class="accordion-title-overlay"></div>

                    <div class="accordion-header-inner">

                        <div aria-hidden="true" class="accordion-icon">
                            &nbsp;
                        </div>

                        <div class="accordion-title">
                            <?php echo sprintf("<%s>", $semanticTag); ?>
                                <?php echo $item->getTitle() ?>
                            <?php echo sprintf("</%s>", $semanticTag); ?>
                        </div>
                    </div>
                </div>

                <div class="accordion-outer-content">
                <div class="accordion-content">
                    <?php $para_content=\Concrete\Core\Editor\LinkAbstractor::translateFrom($item->getParagraph());;

preg_match_all('/\\[(.*)]/', $para_content, $result_arr);

for($i=0;$i<@sizeof($result_arr);$i++){

if(isset($result_arr[1][$i]) && $result_arr[1][$i]!=''){

    $url = parse_url($result_arr[1][$i]);

    $pathParts = explode('/', rtrim($url['path'], '/'));

    $videoID = end($pathParts);

if (isset($url['query']) === true) {

parse_str($url['query'], $query);



if (isset($query['list']) === true) {

    $playListID = $query['list'];

    $videoID = '';

} else {

    $videoID = isset($query['v']) ? $query['v'] : $videoID;

    $videoID = strtok($videoID, '?');

}

}

              if($videoID!=''){

                 $video_embed='<iframe width="100%" height="315" src="https://www.youtube.com/embed/'.$videoID.'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';

               $para_content=str_replace($result_arr[0][$i],$video_embed,$para_content);

              }

}

}

echo $para_content;

 ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
      <a href="/" class="btn-line">VIEW MORE FAQS</a>

<?php endif; ?>


</div>
</section>
