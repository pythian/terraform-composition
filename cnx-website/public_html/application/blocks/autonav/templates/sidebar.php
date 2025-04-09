<?php defined('C5_EXECUTE') or die('Access Denied.');

/** @var \Concrete\Block\Autonav\Controller $controller */

$navItems = $controller->getNavItems(true);

$c = \Concrete\Core\Page\Page::getCurrentPage();

foreach ($navItems as $ni) {

    $classes = [];

    if ($ni->isCurrent) { $classes[] = 'nav-selected'; }

    if ($ni->inPath) { $classes[] = 'nav-path-selected';}

    $ni->classes = implode(' ', $classes);

}

if (count($navItems) > 0) {

    $count = 1;

    echo '<div class="nav-sidebar">';

    foreach ($navItems as $k => $ni) {

        if($ni->cObj->getAttribute('include_sidenav') && $ni->level==1){

        echo '<div class="nav-item ' . $ni->classes . '">';

        if ($ni->isCurrent || $ni->inPath) { $class = 'active'; }else{ $class = ''; }


        //$class = ($count==3551)?'active':'';



        $children = Page::getByID($ni->cID)->getCollectionChildrenArray(1);

        if ($children) { $cls = " ";

          echo '<h3 class="'.$class. $cls .' nav_accordion"><a href="'.$ni->url.'" title="'.h($ni->name).'"> '. h($ni->name). '</a><span>&nbsp;</span></h3>';
        }else{$cls = " no-chl";
          echo '<h3 class="'.$class. $cls .' nav_accordion"><a href="'.$ni->url.'" title="'.h($ni->name).'"> '. h($ni->name). '</a></h3>';
        }



        if ($children) {



        ?>

            <ul class="panel" <?php if($class=='active'){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?> >

                <?php foreach ($children as $childID) {

                    $child = Page::getByID($childID);
                    if($child->getAttribute('exclude_nav'))continue;
                    ?>

                    <li class="<?= ($childID == $c->getCollectionID())?' nav-selected nav-path-selected ' : '';?>" ><a href="<?= $child->getCollectionLink(); ?>" title="<?= $child->getCollectionName(); ?>"><?= $child->getCollectionName(); ?></a></li>

                <?php } ?>

            </ul>

        <?php }

         echo '</div>';

         $count++; }

        }

    echo '</div>';

} elseif (is_object($c) && $c->isEditMode()) { ?><div class="ccm-edit-mode-disabled-item"><?=t('Empty Auto-Nav Block.')?></div><?php } ?>



<script>

$(document).ready(function(){

  $(".nav-sidebar .nav-item ul").each(function(){

     if($(this).find("li").hasClass("nav-selected")||$(this).find("li").hasClass("nav-path-selected")){

      $(this).parent(".nav-item").find("ul").slideDown();

      $(this).parent(".nav-item").find(".nav_accordion").addClass("active");

    }

  })

  if(!$(".nav-sidebar .nav-item").find(".nav_accordion").hasClass("active")){

    $(".nav-sidebar .nav-item:first-child").find("ul").slideDown();

    $(".nav-sidebar .nav-item:first-child").find(".nav_accordion").addClass("active");

  }



$(".nav-sidebar .nav_accordion span").click(function(){

  if($(this).parent().hasClass("active")){

    $(this).parent().next("ul").slideUp();

    $(".nav-sidebar .nav_accordion").removeClass("active");

  }else{

    $(".nav-sidebar ul").not($(this).next("ul")).slideUp();

    $(this).parent().next("ul").slideDown();

    $(".nav-sidebar .nav_accordion").removeClass("active");

    $(this).parent().addClass("active");

  }

})

})



</script>

<style>

    h3.no-chl.nav_accordion::before{

        display : none;

    }

      h3.no-chl.nav_accordion::after{

        display : none;

    }

    .nav-item.nav-selected.nav-path-selected h3 {

    color: #ed2939 !important;

}
h3.nav_accordion {
  position: relative;
}
h3.nav_accordion a{
  color:#000000;
}

h3.nav_accordion span{
  position: absolute;
  right: 0;
  width: 25px;
  height: 100%;
  z-index: 999;
}

</style>
