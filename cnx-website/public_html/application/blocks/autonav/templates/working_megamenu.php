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

    <ul class="nav">

        <?php foreach ($navItems as $ni) {

            if ($ni->cObj->getAttribute('header_nave')) { ?>

                <li class="<?= $ni->classes; ?>">

                    <a href="<?= $ni->url; ?>" target="<?= $ni->target; ?>" class="<?= $ni->classes; ?>"><?= h($ni->name); ?></a>

                    <?php  if ($ni->cObj->getAttribute('has_megamenu')) {

                    ?>


                    <div class="meganav">

                        <div class="meganav-left">

                             <p class="nav-title"><?= h($ni->name); ?></p>

                            <ul class="nav-list nav">

                                <?php $children = Page::getByID($ni->cID)->getCollectionChildrenArray(1);
$sub_content='';
                                if ($children && !$ni->cObj->getAttribute('exclude_subpages_from_nav')) {

                                    $subSub = array();

                                    foreach ($children as $k => $childID) {

                                        $child = Page::getByID($childID); ?>

                                        <li class="mega_item <?= currentPage($childID,$pageID)?>" data-id="<?=$childID?>-<?= $k+1; ?>"><a href="<?= $child->getCollectionLink(); ?>" title="<?= $child->getCollectionName(); ?>"><?= $child->getCollectionName(); ?></a></li>





                                <?php /** Next sub nave */
$sub_content.='<div class="meganav-def-cont list-cont-'.$childID.'-'.($k+1).'">'.$child->getAttribute('mega_nav_content').'</div>';
                                        $sbChildren = Page::getByID($child->getCollectionID())->getCollectionChildrenArray(1);

                                        if (sizeof($sbChildren) && !$child->getAttribute('exclude_subpages_from_nav')) {
$sub_content.='<div class="meganav-list-cont list-cont-'.$childID.'-'.($k+1).'">

<div class="meganav-cont">

    <div class="row">';
    $totalNav = (count($sbChildren) < 3) ? count($sbChildren) : round(count($sbChildren) / 3);
                                            foreach ($sbChildren as $ckey=>$childID) {
if($ckey==0) { $sub_content.='<div class="col-12 col-md-4"><ul class="nav">'; }elseif($ckey%$totalNav==0){ $sub_content.=' </ul></div><div class="col-12 col-md-4"><ul class="nav">';  }
                                                $childObj = Page::getByID($childID);

                                                $subSub[$child->getCollectionHandle()][] = array('id' => $childObj->getCollectionID(), 'nodeID' =>$k, 'sub_handle' => $childObj->getCollectionHandle(), 'name' => $childObj->getCollectionName(), 'link' => $childObj->getCollectionLink());
            $sub_content.='<li class="'.currentPage($childID,$pageID).'" ><a href="'.$childObj->getCollectionLink().'" title="'.$childObj->getCollectionName().'">'.$childObj->getCollectionName().'</a>';
            $children = Page::getByID($childObj->getCollectionID())->getCollectionChildrenArray(1);

                                                            if (sizeof($children)>0 && !$childObj->getAttribute('exclude_subpages_from_nav')) {

$sub_content.='<ul class="sub-nav">';

                                                                   foreach ($children as $cchildID) {

                                                                        $child = Page::getByID($cchildID);

$sub_content.='<li class="'.currentPage($cchildID,$pageID).'" ><a href="'.$child->getCollectionLink().'" title="'.$child->getCollectionName().'">'.$child->getCollectionName().'</a></li>';

                                                                     }

                                                                     $sub_content.='</ul>';

                                                            }
            $sub_content.='</li>';
                                            }
                                            $sub_content.=' </ul>

                                            </div>

                                            </div>

                                        </div>  </div>';

                                        }

                                    }

                                    // echo '<pre>';

                                    $navs = array_merge($subSub);

                                    // print_r($navs);

                                } ?>

                            </ul>
                        </div>
                        <div class="meganav-right">
                       <div class="meganav-def-cont list-cont-main"><?=$ni->cObj->getAttribute('mega_nav_content')?></div>

                            <?=$sub_content?>


                        </div>

                    </div>

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

<?php function currentPage($id,$pageID){ return ($pageID == $id) ? ' nav-selected nav-path-selected ' : ''; } ?>

<style>

    ul.nav li a {

        color: white;

    }

</style>

<script>
/* $(document).ready((function(){$(".mega_item").mouseenter((function(e){e.preventDefault();$(".list-cont-"+$(this).data("id")).length?($(".meganav-def-cont,.meganav-list-cont").hide(),$(".list-cont-"+$(this).data("id")).show()):$(".meganav-def-cont,.meganav-list-cont").hide()})),$("li.has-meganav").mouseenter((function(){$(".mega_item.nav-selected",this).length>0?$(".list-cont-"+$(".mega_item.nav-selected",this).data("id")).length?($(".meganav-def-cont,.meganav-list-cont").hide(),$(".list-cont-"+$(".mega_item.nav-selected",this).data("id")).show()):$(".meganav-def-cont,.meganav-list-cont").hide():""!=$(".mega_item",this).first().html()&&($(".list-cont-"+$(".mega_item",this).first().data("id")).length?($(".meganav-def-cont,.meganav-list-cont").hide(),$(".list-cont-"+$(".mega_item",this).first().data("id")).show()):$(".meganav-def-cont,.meganav-list-cont").hide())})),$(".meganav").each((function(){$(".mega_item.nav-selected",this).length>0&&($(".list-cont-"+$(".mega_item.nav-selected",this).data("id")).length?($(".meganav-def-cont,.meganav-list-cont").hide(),$(".list-cont-"+$(".mega_item.nav-selected",this).data("id")).show()):$(".meganav-list-cont").hide())}))})); */
$(document).ready(function() {

$('.mega_item').mouseenter(function(e){
    e.preventDefault();
if($('.list-cont-'+$(this).data('id')).length){
$('.meganav-def-cont,.meganav-list-cont').hide();
$('.list-cont-'+$(this).data('id')).show();
}else{
$('.meganav-def-cont,.meganav-list-cont').hide();
}

});
$('div.meganav').mouseleave(function(e){ e.preventDefault();
    $('.meganav-def-cont,.meganav-list-cont').hide();
    $('.list-cont-main').show();
})
$('div.meganav').mouseenter(function(e){ e.preventDefault();
    if($('.mega_item.nav-selected',this).length>0){

if($('.list-cont-'+$('.mega_item.nav-selected',this).data('id')).length){
        $('.meganav-def-cont,.meganav-list-cont').hide();
        $('.list-cont-'+$('.mega_item.nav-selected',this).data('id')).show();
}else{
    $('.meganav-def-cont,.meganav-list-cont').hide();
}

}else{
    $('.meganav-def-cont,.meganav-list-cont').hide();
    $('.list-cont-main').show();

}
})
$('li.has-meganav').mouseenter(function(){

    $('.meganav-def-cont,.meganav-list-cont').hide();
    $('.list-cont-main').show();
    /*
    if($('.mega_item.nav-selected',this).length>0){

if($('.list-cont-'+$('.mega_item.nav-selected',this).data('id')).length){
        $('.meganav-def-cont,.meganav-list-cont').hide();
        $('.list-cont-'+$('.mega_item.nav-selected',this).data('id')).show();
}else{
    $('.meganav-def-cont,.meganav-list-cont').hide();
}

}else{
    $('.meganav-def-cont,.meganav-list-cont').hide();
    $('.list-cont-main').show();

} */
});

$('.meganav').each(function(){
if($('.mega_item.nav-selected',this).length>0){

if($('.list-cont-'+$('.mega_item.nav-selected',this).data('id')).length){
        $('.meganav-def-cont,.meganav-list-cont').hide();
        $('.list-cont-'+$('.mega_item.nav-selected',this).data('id')).show();
}else{
    $('.meganav-def-cont,.meganav-list-cont').hide();
}

}
});

});
</script>





<!-- <ul class="nav">

    <li class="has-meganav">

        <a href="" title="">Residential</a>

        <div class="meganav">

            <div class="meganav-left">

                <p class="nav-title">Residential</p>

                <ul class="nav-list nav">

                    <li data-id="nav-list-item-1"><a href="" title="">Account Services</a></li>

                    <li data-id="nav-list-item-2"><a href="" title="">Billing & Rates</a></li>

                    <li data-id="nav-list-item-3"><a href="" title="">Outage Center</a></li>

                    <li data-id="nav-list-item-4"><a href="" title="">Save Money & Energy</a></li>

                    <li data-id="nav-list-item-5"><a href="" title="">Rules & Regulations</a></li>

                </ul>

            </div>

            <div class="meganav-right">

                <div class="meganav-def-cont">

                    <p class="def-title">west's largest electric cooperative.</p>

                    <p>Our vision is simple – our syestem is your most powerful membership®. These four words focus on the most important component of our cooperative: our members. In a cooperative, everything we do is for the benefit of our membership.</p>

                </div>

                <div class="meganav-list-cont list-cont-1">

                    <div class="meganav-cont">

                        <div class="row">

                            <div class="col-12 col-md-4">

                                <ul class="nav">

                                    <li><a href="">--text--</a>

                                        <ul class="sub-nav">

                                            <li><a href="">--text--</a></li>

                                            <li><a href="">--text--</a></li>

                                        </ul>

                                    </li>

                                </ul>

                                <ul class="nav">

                                    <li><a href="">--text--</a>

                                    </li>

                                </ul>

                            </div>

                            <div class="col-12 col-md-4">

                                <ul class="nav">

                                    <li><a href="">--text--</a>

                                        <ul class="sub-nav">

                                            <li><a href="">--text--</a></li>

                                            <li><a href="">--text--</a></li>

                                        </ul>

                                    </li>

                                </ul>

                                <ul class="nav">

                                    <li><a href="">--text--</a>

                                    </li>

                                </ul>

                            </div>

                            <div class="col-12 col-md-4">

                                <ul class="nav">

                                    <li><a href="">--text--</a>

                                        <ul class="sub-nav">

                                            <li><a href="">--text--</a></li>

                                            <li><a href="">--text--</a></li>

                                        </ul>

                                    </li>

                                </ul>

                                <ul class="nav">

                                    <li><a href="">--text--</a>

                                    </li>

                                </ul>

                            </div>

                        </div>

                    </div>

                    <div class="meganav-list-cont list-cont-2">

                    </div>

                    <div class="meganav-list-cont list-cont-3">

                    </div>

                    <div class="meganav-list-cont list-cont-4">

                    </div>

                    <div class="meganav-list-cont list-cont-5">

                    </div>

                </div>



            </div>

        </div>

    </li>

    <li><a href="" title="">--nav text--</a></li>

    <li><a href="" title="">--nav text--</a></li>

    <li><a href="" title="">--nav text--</a></li>

    </ul>-->
