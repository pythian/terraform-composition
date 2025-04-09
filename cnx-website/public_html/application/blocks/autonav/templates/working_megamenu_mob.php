<?php defined('C5_EXECUTE') or die('Access Denied.');

/** @var \Concrete\Block\Autonav\Controller $controller */

$navItems = $controller->getNavItems(true);

$c = \Concrete\Core\Page\Page::getCurrentPage();

$pageID = $c->getCollectionID();

foreach ($navItems as $ni) {

    $classes = [];

    if ($ni->isCurrent) {

        $classes[] = 'nav-selected';

    }

    if ($ni->inPath) {

        $classes[] = 'nav-path-selected';

    }

    if ($ni->cObj->getAttribute('has_megamenu')) {

        $classes[] = ' has-meganav';

    }

    $ni->classes = implode(' ', $classes);

}



if (count($navItems) > 0) {

    $count = 0; ?>

    <ul class="nav mob-mega-nav">

        <?php foreach ($navItems as $ni) {

            if ($ni->cObj->getAttribute('header_nave')) { ?>

                <li class="<?= $ni->classes; ?>">

                    <a href="<?= $ni->url; ?>" target="<?= $ni->target; ?>" class="<?= $ni->classes; ?>"><?= h($ni->name); ?></a>

                    <?php  if ($ni->cObj->getAttribute('has_megamenu')) {

                    ?>
                           <!--  <p class="nav-title"><?= h($ni->name); ?></p> -->

                            <ul class="nav">

                                <?php $children = Page::getByID($ni->cID)->getCollectionChildrenArray(1);
//$sub_content='';
                                if ($children) {

                                    $subSub = array();

                                    foreach ($children as $k => $childID) {

                                        $child = Page::getByID($childID);
                                        $sbChildren = Page::getByID($child->getCollectionID())->getCollectionChildrenArray(1);
                                        if (sizeof($sbChildren)) {
                                          $childParentName="has-par-sub ";
                                          $childArrow='<span class="sub-arrow"></span>';
                                        }else {
                                        $childParentName="";
                                        $childArrow="";
                                        }?>

                                        <li class=" <?= $childParentName.currentPage($childID,$pageID)?>" data-id="<?=$childID?>-<?= $k+1; ?>"><a href="<?= $child->getCollectionLink(); ?>" title="<?= $child->getCollectionName(); ?>"><?= $child->getCollectionName(); ?></a>
                                          <?= $childArrow?>






<?php
if($child->getAttribute('mega_nav_content') != ""){
echo '<div class="def-cont list-cont-'.$childID.'-'.($k+1).'">'.$child->getAttribute('mega_nav_content').'</div>';
}?>
<?php /** Next sub nave */





                                        if (sizeof($sbChildren)) {?>
<ul><?php
    $totalNav = (count($sbChildren) < 3) ? count($sbChildren) : round(count($sbChildren) / 3);
                                            foreach ($sbChildren as $ckey=>$childID) {
                                                $childObj = Page::getByID($childID);

                                                $subSub[$child->getCollectionHandle()][] = array('id' => $childObj->getCollectionID(), 'nodeID' =>$k, 'sub_handle' => $childObj->getCollectionHandle(), 'name' => $childObj->getCollectionName(), 'link' => $childObj->getCollectionLink());
                                                $children = Page::getByID($childObj->getCollectionID())->getCollectionChildrenArray(1);
if (sizeof($children)>0) {
  $childName="has-mob-sub";
}else {
$childName="";
}
            echo '<li class="'.$childName.' '.currentPage($childID,$pageID).'" ><a href="'.$childObj->getCollectionLink().'" title="'.$childObj->getCollectionName().'">'.$childObj->getCollectionName().'</a>';

                                                            if (sizeof($children)>0) {?>

<ul class="sub-nav">

                                                              <?php     foreach ($children as $cchildID) {

                                                                        $child = Page::getByID($cchildID);

echo'<li class="'.currentPage($cchildID,$pageID).'" ><a href="'.$child->getCollectionLink().'" title="'.$child->getCollectionName().'">'.$child->getCollectionName().'</a></li>';

                                                                     }?>

                                                                    </ul>

                                                      <?php        }?>
          </li>
                                      <?php        }?>
                                            </ul>

                                    <?php      }?>





</li>

                              <?php            }

                                    // echo '<pre>';

                                    $navs = array_merge($subSub);

                                    // print_r($navs);

                                }
                                //echo $sub_content;?>

                            </ul>

 </li>

<?php } else {

   $children = Page::getByID($ni->cID)->getCollectionChildrenArray(1);

   if ($children) {

    foreach ($children as $childID) {

        $child = Page::getByID($childID); ?>

        <li class="<?= currentPage($childID,$pageID)?>"><a href="<?= $child->getCollectionLink(); ?>" title="<?= $child->getCollectionName(); ?>"><?= $child->getCollectionName(); ?></a></li>

<?php } } ?>

</li>

<?php } ?>

<?php }

            $count++;

        } ?>

</ul>

<?php } elseif (is_object($c) && $c->isEditMode()) { ?><div class="ccm-edit-mode-disabled-item"><?= t('Empty Auto-Nav Block.') ?></div><?php } ?>

<?php //function currentPage($id,$pageID){ return ($pageID == $id) ? ' nav-selected nav-path-selected ' : ''; } ?>
<script>
$(document).ready(function(){
  $(".mob-mega-nav li.has-meganav >a").click(function(e){
    if(!$(this).hasClass("clicked")){
      e.preventDefault();
      $(this).addClass("clicked");
    }
    $(this).parent("li.has-meganav").addClass("open-nav");
  })
  $(".mob-mega-nav li.has-par-sub .sub-arrow").click(function(e){
    $(this).parent("li.has-par-sub").toggleClass("open-nav");
  })
    $(".mobile-menu-top li.nav-dropdown >a").click(function(e){
      if(!$(this).hasClass("clicked")){
        e.preventDefault();
        $(this).addClass("clicked");
      }
      $(this).parent("li.nav-dropdown").addClass("open-nav");
    })
})
</script>
