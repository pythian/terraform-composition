<?php defined('C5_EXECUTE') or die(_("Access Denied."));
$view->inc('elements/header.php');
$page = Page::getCurrentPage();
$ih = Loader::helper("image"); ?>
<?php $a = new GlobalArea('News Detail Banner');
$a->display();?>
<section class="news-details">
  <div class="container">
    <?php

    $thumbnail = $page->getAttribute('news_thumbnail');
    $title = $page->getCollectionName();
    $description = $page->getAttribute('news_description');
    $category = $page->getAttribute('news_category');
    ?>

    <h2><? $title; ?></h2>

<?php //$para_content=\Concrete\Core\Editor\LinkAbstractor::translateFrom($description);;
$para_content=$description;

preg_match_all('/\\[(.*)]/', $para_content, $result_arr);

for($i=0;$i<@sizeof($result_arr);$i++){

if(isset($result_arr[1][$i]) && $result_arr[1][$i]!='' && stristr($result_arr[1][$i],'youtu')!=''){

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

                 $video_embed='<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="https://www.youtube.com/embed/'.$videoID.'"></iframe></div>';
/* <iframe width="100%" height="450" src="https://www.youtube.com/embed/'.$videoID.'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe> */
               $para_content=str_replace($result_arr[0][$i],$video_embed,$para_content);

              }

}

}

echo $para_content;

 ?>
</div>

</section>

<script>$(document).ready(function(){ $('.news-details img').each(function(){ if($(this).attr('alt')!=''){
$(this).wrapAll('<figure style="'+$(this).attr('style')+'" />');
$(this).parent().append('<figcaption style="text-align:center">'+$(this).attr('alt')+'</figcaption>');
$(this).removeAttr('style');
 } }); });</script>
<?php $view->inc('elements/footer.php'); ?>
