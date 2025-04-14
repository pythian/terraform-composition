<?php $df = Loader::helper('form/date_time');

$form = Loader::helper('form');

$dh = Core::make('helper/date');

$page = isset($page) ? $page : '';

$pageDate = isset($pageDate) ? $pageDate : '';

$page_title = isset($page_title) ? $page_title : '';

$slug = isset($slug) ? $slug : '';

$aValue = isset($aValue) ? $aValue : '';

$cDescription = isset($cDescription) ? $cDescription : '';



$currentActiveLocale = isset($currentActiveLocale) ? $currentActiveLocale : '';

$OtherslocaleEditLinks = isset($OtherslocaleEditLinks) ? $OtherslocaleEditLinks : '';



Loader::model("attribute/categories/collection");



use Concrete\Core\Multilingual\Page\Section\Section;



global $u;

$u = new User();

$uID = $u->uID;

if (is_object($page)) {

    $page_title = $page->getCollectionName();

    $pageDescription = $page->getCollectionDescription();

    $pageDate = $page->getCollectionDatePublic();

    $cParentID = $page->getCollectionParentID();

    $slug = $page->getCollectionHandle();

    $cHandle = $page->getCollectionHandle();

    $ctID = $page->getCollectionTypeID();

    $uID = $page->getCollectionUserID();

    $cDescription = $page->getCollectionDescription();

    $task = 'update';

    $buttonText = t('Update News');

    $title = 'Update';

    if ($page->isActive()) {

        $status = 1;

    } else {

        $status = 0;

    }

} else {

    $task = 'add';

    $buttonText = t('Add News Entry');

    $title = 'Add';

    $cHandle = '';

    $status = 0;

}

if (is_object($page)) {

    foreach ($languageSections as $ml) {

        if (!in_array($ml->getSiteTreeID(), $allowedSiteTreeIDs)) {

            continue;

        }

        $db = Database::get();

        $section = Section::getBySectionOfSite($ml);

        $region = $section->getCountry();

        $val = \Core::make('helper/validation/strings');

        if ($val->alphanum($region, false, true)) {

            $aregion = h(strtolower($region));

        } else {

            $aregion = false;

        }

        if ($currentActiveLocale == $ml->getSiteTreeID()) {

            $selectedText = 'selected="selected"';

        } else {

            $selectedText = '';

        }

        $siteHomePageHandle = Page::getByID($page->getSiteHomePageID())->getCollectionHandle();

        //$tempPath = ltrim($page->getCollectionPath(), '/' . $siteHomePageHandle);

        $countrylength = strlen('/' . $siteHomePageHandle);

        $pathlength = strlen($page->getCollectionPath()) - $countrylength;

        $tempPath = @substr($page->getCollectionPath(), $countrylength, $pathlength);

        if ($siteHomePageHandle != $ml->getCollectionHandle()) {

            //. Page::getByID($page->getSiteHomePageID())->getCollectionHandle()

            if ($ml->getCollectionHandle() == '') {

                $otherLocalePath = '/' . $tempPath;

            } else {

                $otherLocalePath = '/' . $ml->getCollectionHandle() . '/' . $tempPath;

            }

            $otherLocalePath = str_replace('//', '/', $otherLocalePath);

            $otherLocalePage = Page::getByPath($otherLocalePath);

            if (is_object($otherLocalePage) && $otherLocalePage->getCollectionID() > 0) {

                $OtherslocaleEditLinks .= '<li><a href="' . $this->url('/dashboard/news/add_edit', 'edit', $otherLocalePage->getCollectionID()) . '"><img src="' . BASE_URL . '/concrete/images/countries/' . strtolower($ml->getIcon()) . '.png">&nbsp;' . $ml->getLanguageText() . '</a></li>';

            }

        }

    }

    if ($OtherslocaleEditLinks != '') {

        echo '<div class="edit_other_sections"><span>Edit Other Sections:</span><ul>' . $OtherslocaleEditLinks . '</ul></div>';

    }

}



$fullwidth = 'style="width:100%;"' ?>



<!-- <div class="links float-end"> <a class="btn btn-success"

        href="<?php echo View::url('/dashboard/news/news_list') ?>">News List</a></div> -->



<?php if ($this->controller->getTask() == 'edit') { ?>

    <form method="post" class="form-horizontal" action="<?php echo $this->action($task) ?>" id="page-form">

        <?php echo $form->hidden('pageID', $page->getCollectionID()) ?>

    <?php } else { ?>

        <form method="post" class="form-horizontal" action="<?php echo $this->action($task) ?>" id="page-form">

        <?php } ?>

        <div class="clearfix" style="float: right;clear: both;padding: 0px;">
            <div class="input" style="margin: 0px;float: right;display: flex;width: auto;">
                <label for="status" class="control-label"
                       style="display: inline-block;margin-right: 5px;margin-left: 20px;padding-top: 0px;">Status</label>
                <?php
                if (is_object($page)) {
                    if ($page->isActive()) {
                        $status = 1;
                    } else {
                        $status = 0;
                    }
                } else {
                    $status = 0;
                }
                $values = array(1 => t('Published'), 0 => t('Un-published'));
                ?>
                <?php echo $form->select('status', $values, $status) ?>&nbsp;&nbsp;
                <a class="btn btn-success" href="<?php echo View::url('/dashboard/news/news_list') ?>">News List</a>
            </div>
        </div>

        <div class="pane content">

            <fieldset>



                <?php Loader::model('config');

                echo $form->hidden('ctID', Config::get('concrete.news_page_type_id'));

                echo $form->hidden('ptID', Config::get('concrete.news_page_template_id')); ?>



                <div class="row" style="display:none;">

                    <div class="form-group">

                        <?php echo $form->label('cName', t('Author'), array('class' => 'col-sm-3')) ?>

                        <div class="col-sm-7">

                            <div class="input-group">

                                <?php $user = Loader::helper('form/user_selector');

                                print $user->selectUser('uID', $uID); ?>

                            </div>

                        </div>

                    </div>

                </div>



                <div class="row date_section">

                    <div class="form-group">

                        <?php echo $form->label('page_date_time', t('Date/Time <span class="required">*</span>'), array('class' => 'col-sm-3')) ?>

                        <div class="col-sm-7">

                            <div class="input-group">

                                <?php echo $df->datetime('page_date_time', $pageDate) ?>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="form-group">

                        <?php echo $form->label('pageTitle', t('Title <span class="required">*</span>'), array('class' => 'col-sm-3')) ?>

                        <div class="col-sm-7">

                            <div class="input-group">

                                <?php echo $form->text('page_title', $page_title, array('class' => 'required', 'data-name' => 'Blog Name')) ?>



                            </div>

                        </div>

                    </div>

                </div>



                <div class="row">

                    <div class="form-group">

                        <?php echo $form->label('cDescription', t('Short Description'), array('class' => 'col-sm-3')) ?>

                        <div class="col-sm-7">

                            <div class="input-group">

                                <?php echo $form->textarea('cDescription', $cDescription) ?>



                            </div>

                        </div>

                    </div>

                </div>



                <div class="row d-none">

                    <div class="form-group">

                        <?php echo $form->label('slug', t('Slug <span class="required">*</span>'), array('class' => 'col-sm-3')) ?>

                        <div class="col-sm-7">

                            <div class="input-group">

                                <?= $form->text('slug', $slug, array('class' => 'required', 'data-name' => 'URL Slug')); ?>



                            </div>

                        </div>

                    </div>

                </div>



                <div class="row d-none">

                    <div class="form-group">

                        <?php echo $form->label('cParentID', t('Language <span class="required">*</span>'), array('class' => 'col-sm-3')) ?>

                        <div class="col-sm-7">

                            <div class="input-group">

                                <?php if (count($sections) == 0) { ?>

                                    <?php echo t('No sections defined. Please create a page with the attribute "news_section" set to true.') ?>

                                <?php } else {



                                    if (is_object($page)) {



                                        echo $form->select('cParentID', $sections, $cParentID, array('readonly' => 'readonly'));

                                    } else {



                                        foreach ($sections as $key => $section) {



                                            echo '<input type="checkbox" checked="checked" name="cParentIDs[]" value="' . $key . '"  />&nbsp;' . $section . '<br/>';

                                        }

                                    }

                                } ?>



                            </div>





                        </div>

                    </div>

                </div>



            </fieldset>

            <fieldset class="blog-fld-st">

                <?php

                Loader::model('config');

                $attributeset_id = Config::get('concrete.news_attribute_set_id');

                $set = AttributeSet::getByID($attributeset_id);

                if (is_object($set)) {

                    $setAttribs = $set->getAttributeKeys();

                    if ($setAttribs) {

                        foreach ($setAttribs as $ak) {

                            if (is_object($page)) {

                                $aValue = $page->getAttributeValueObject($ak);

                            }



                            $handle = $ak->getAttributeKeyName();



                            if ($handle != 'SEO Score News') {



                                echo '<div class="row" id="' . $ak->getAttributeKeyHandle() . '">

      <div class="form-group">';

                                if ($ak->getAttributeKeyName() == 'Type') {

                                    echo '<label for="form_script" class="form-label">' . $ak->getAttributeKeyName() . '<a style="color:red" href="' . BASE_URL . '/index.php/dashboard/pages/attributes/edit/' . $ak->getAttributeKeyID() . '" target="_blank" title="Add Type">  (Add/Edit)</a></label>';

                                } else if ($ak->getAttributeKeyName() == 'Alternative Thumbnail') {

                                    echo '<label for="form_script" class="form-label">' . $ak->getAttributeKeyName() . '  <i style="font-size:11px">(350px x 420px)</i></label>';

                                } else {

                                    echo $form->label($ak->getAttributeKeyHandle(), t($ak->getAttributeKeyName()), array('class' => 'col-sm-3'));

                                }

                                echo '  <div class="col-sm-7">

          <div class="input-group" ' . $fullwidth . '>';

                                echo $ak->render('form', $aValue);

                                echo '</div>

        </div>

      </div>

    </div>';

                            }

                        }

                    }

                } else {

                    echo "No Attribute Set Defined/Created";

                }

              /*   $news_content = \CollectionAttributeKey::getByHandle('news_description');

                $kID = $news_content->getAttributeKeyID();

                $r = 'textarea[name="akID[' . $kID . '][value]"]'; */

                ?>





              <!--   <script type="text/javascript">

                    var contentVar = $('<? //= $r ?>').attr('id');

                    console.log(contentVar);

                    $(window).on('load', function () {

                        CKEDITOR.instances[contentVar].on('change', function () {

                            YoastSEO.app.refresh()

                        });

                    })

                </script> -->

            </fieldset>

        </div>





        <div class="ccm-dashboard-form-actions-wrapper">

            <div class="ccm-dashboard-form-actions">

                <a href="<?php echo View::url('/dashboard/news/news_list') ?>" class="btn btn-default pull-left">

                    <?php echo t('Cancel') ?>

                </a>

                <?php echo Loader::helper("form")->submit('add', t($title . ' News'), array('class' => 'btn btn-primary pull-right')) ?>



            </div>

        </div>

        </div>

    </form>





    <script type="text/javascript">

        var concreteComposerAddPageTimer = false;

        $(function () {

            var $urlSlugField = $('#snippet-editor-slug');



            $('#page_title').on('keyup', function () {

                var input = $(this);

                var send = {

                    token: '<?= Loader::helper('validation/token')->generate('get_url_slug') ?>',

                    name: input.val()

                };

                var parentID = input.closest('form').find('input[name=cParentID]').val();

                if (parentID) {

                    send.parentID = parentID;

                }

                clearTimeout(concreteComposerAddPageTimer);

                concreteComposerAddPageTimer = setTimeout(function () {

                    $('.ccm-composer-url-slug-loading').show();

                    $.post(

                        '<?php echo BASE_URL ?>/index.php/tools/required/pages/url_slug',

                        send,

                        function (r) {

                            $('.ccm-composer-url-slug-loading').hide();

                            $urlSlugField.val(r);

                        }

                    );

                }, 150);

            });

        });

    </script>



    <style>

        div#news_image2 label {

            display: none;

        }



        div#news_image1,

        div#news_image2 {

            display: inline-block;

            margin-right: 10px;

        }

    </style>
