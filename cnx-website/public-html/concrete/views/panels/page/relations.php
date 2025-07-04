<?php
defined('C5_EXECUTE') or die("Access Denied.");
?>

<?php if ($siblingRelations) { ?>
    <header><h5><?=t('Sibling Pages')?></h5></header>
    <section>
        <ul class="nav d-block item-select-list">
    <?php foreach($siblingRelations as $relation) {
        $formatter = $relation->getFormatter();
        $relatedPage = $relation->getPageObject();
        ?>

        <li><a href="<?=$relatedPage->getCollectionLink()?>"><i class="fas fa-file"></i> <?=$formatter->getDisplayName()?></a></li>

    <?php } ?>
    </ul>
    </section>
<?php } ?>

<?php if (count($multilingualSectionList)) { ?>

    <header><h5><?=t('Multilingual Relations')?></h5></header>
    <section>
    <ul class="nav d-block item-select-list">
        <?php foreach ($multilingualSectionList as $m) {
    $relatedID = $m->getTranslatedPageID($c);
    $icon = $ih->getSectionFlagIcon($m);
    $locale = $m->getLocale();
    $cParent = Page::getByID($c->getCollectionParentID());
    $cParentRelatedID = $m->getTranslatedPageID($cParent);
    ?>
            <li>
                <?php if ($relatedID && $currentSection->getCollectionID() != $m->getCollectionID()) {
    $relatedPage = Page::getByID($relatedID, 'RECENT');
    ?>
                    <a href="<?=$relatedPage->getCollectionLink()?>"><?=$icon?> <?=$relatedPage->getCollectionName()?></a>
                <?php
} else {
    ?>
                    <a href="#" class="ccm-panel-multilingual-section-no-mappings" data-launch-multilingual-menu="<?=$m->getCollectionID()?>"><?=$icon?> <span><?=t('None Created')?></span></a>
                    <div class="popover fade" data-multilingual-menu="<?=$m->getCollectionID()?>">
                        <div class="popover-inner">
                            <ul class="dropdown-menu">
                                <?php if ($cParentRelatedID || $c->isPageDraft()) { ?>
                                    <li><a href="#" class="dropdown-item" data-multilingual-create-page="<?=$m->getCollectionID()?>"><?=t('Create Page')?></a></li>
                                <?php
} else {
    ?>
                                    <li><a href="#" class="disabled dropdown-item" title="<?=t('Parent page does not exist. Create the parent page in this tree first.')?>"><?=t('Create Page')?></a></li>
                                <?php
}
    ?>
                                <li class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" data-multilingual-map-page="<?=$m->getCollectionID()?>"><?=t('Map Existing Page')?></a></li>
                            </ul>
                        </div>
                    </div>
                <?php
}
    ?>
            </li>
        <?php
} ?>
    </ul>
    </section>
<?php } ?>

<script type="text/javascript">
    replaceLinkWithPage = function(menuID, link, icon, name) {
        var $link = $('a[data-launch-multilingual-menu=' + menuID + ']');
        var newLink = '<a href="' + link + '">' + icon + ' ' + name + '<\/a>';
        $link.replaceWith($(newLink));
    }

    $(function() {
        $('a.ccm-panel-multilingual-section-no-mappings').each(function() {
            $(this).concreteMenu({
                menu: 'div[data-multilingual-menu=' + $(this).attr('data-launch-multilingual-menu') + ']',
                menuLauncherHoverClass: 'ccm-panel-multilingual-menu-hover',
                menuContainerClass: 'ccm-panel-multilingual-menu-container'
            });
        });
        $('a[data-multilingual-create-page]').on('click', function(e) {
            e.preventDefault();
            var cID = $(this).attr('data-multilingual-create-page');
            $.concreteAjax({
                url: '<?=$multilingualController->action('create_new')?>',
                method: 'post',
                data: {
                    'section': cID,
                    'cID': '<?=$c->getCollectionID()?>'
                },
                success: function(r) {
                    ConcreteAlert.notify({
                        'message': r.message,
                        'title': r.title
                    });
                    if (r.link) {
                        ConcreteMenuManager.reset();
                        replaceLinkWithPage(cID, r.link, r.icon, r.name);
                    }
                }
            });
        });

        $('a[data-multilingual-map-page]').on('click', function(e) {
            e.preventDefault();
            var cID = $(this).attr('data-multilingual-map-page');
            ConcretePageAjaxSearch.launchDialog(function(data) {
                $.concreteAjax({
                    url: '<?=$multilingualController->action('assign')?>',
                    method: 'post',
                    data: {
                        'destID': data.cID,
                        'cID': cID
                    },
                    success: function(r) {
                        ConcreteAlert.notify({
                            'message': r.message,
                            'title': r.title
                        });
                        if (r.link) {
                            ConcreteMenuManager.reset();
                            replaceLinkWithPage(cID, r.link, r.icon, r.name);
                        }

                    }
                });
            });
        });

    });
</script>
