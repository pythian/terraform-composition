<?php



$ci = Loader::helper('concrete/urls');

$h = \Core::make('helper/lists/countries');



$remove_name = isset($remove_name) ? $remove_name : '';

$like = isset($like) ? $like : '';

$category = isset($category) ? $category : '';



$u = new User();

$g = \Concrete\Core\User\Group\Group::getByName('Administrators');

if ($u->inGroup($g)) {

  $userGroup = "admin";

} else {

  $userGroup = "";

}



use Concrete\Core\Multilingual\Page\Section\Section;



if (isset($_COOKIE['currentActiveLocale'])) {

  $currentActiveLocale = $_COOKIE['currentActiveLocale'];

}

if (isset($_GET['siteTreeID']) > 0) {

  $currentActiveLocale = $_GET['siteTreeID'];

}



?>



<script type="text/javascript">

  var runCount = 0;

</script>

<select class="form-control form-select d-none" id="lang-select">

  <?php

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

    if ( isset($currentActiveLocale) && $currentActiveLocale == $ml->getSiteTreeID()) {

      $selectedText = 'selected="selected"';

    } else {

      $selectedText = '';

    }

    $locale = $ml->getLocale();

    if ($locale == 'zh_HK') {

      $country_name = str_replace($h->getCountryName($region), 'Hong Kong CN', $h->getCountryName($region));

    } elseif ($locale == 'en_HK') {

      $country_name = str_replace($h->getCountryName($region), 'Hong Kong EN', $h->getCountryName($region));

    } else {

      $country_name = $h->getCountryName($region);

    }

    echo '<option value="' . $ml->getSiteTreeID() . '" ' . $selectedText . ' data-imagesrc="' . BASE_URL . '/concrete/images/countries/' . strtolower($ml->getIcon()) . '.png"

            data-description="">' . $country_name . '</option>';

  }

  ?>

</select>



<?php if ($remove_name) { ?>

  <div class="alert-message block-message delete">

    <a class="close" href="<?php echo $this->action('clear_warning'); ?>">×</a>

    <p><strong style="font-size:20px;">

        <?php echo t('This is a warning!'); ?>

      </strong></p>

    <p>

      <?php echo t('Are you sure you want to delete ') . t($remove_name) . '?'; ?>

    </p>

    <p>

      <?php echo t('This action may not be undone!'); ?>

    </p>

    <div class="alert-actions"> <a class="btn btn-danger"

        href="<?php echo BASE_URL ?>/index.php/dashboard/news/news_list/delete/<?php echo $remove_cid; ?>/<?php echo $remove_name; ?>/">

        <?php echo t('Yes Remove This'); ?>

      </a> <a class="btn btn-primary" href="<?php echo $this->action('clear_warning'); ?>">

        <?php echo t('Cancel'); ?>

      </a> </div>

  </div>

<?php } ?>





<form method="get" action="<?php echo $this->action('view') ?>" style="float:left; margin-bottom:20px; width:100%;">

  <?php $form = Loader::helper('form');

  $sections[0] = '** All';

  asort($sections); ?>

  <table class="ccm-results-list" style="float:left;">

    <tr>

      <th><strong>

          <?php echo t('By Name') ?>

        </strong></th>

      <th><strong>

          <?php echo t('Category') ?>

        </strong></th>

      <th></th>

    </tr>

    <tr>

      <td>

        <?php echo $form->text('like', $like) ?>

      </td>

      <td>

        <?php echo $form->select('category', $category, isset($_REQUEST['category'])) ?>

      </td>

      <td>

        <?php echo $form->submit('submit', t('Search'), array('class' => 'btn-dark')) ?>

      </td>

      <td><a class="btn btn-success" href="<?php echo BASE_URL; ?>/index.php/dashboard/news/add_edit">

          <?php echo t('Add News'); ?>

        </a></td>

      <td><a class="btn btn-info" href="<?php echo BASE_URL; ?>/index.php/dashboard/news/news_list">

          <?php echo t('Reset'); ?>

        </a></td>

    </tr>

  </table>



</form>

<br />

<?php $nh = Loader::helper('navigation');

$fm = Loader::helper('form');

$dh = Core::make('helper/date');

//$ccm_order_dir='desc';

if (sizeof($pageList) > 0) { ?>

  <?php echo t('Total Pages: ' . count($totalPages) . ''); ?>

  <table border="0" class="ccm-results-list table table-striped team_list_icon" cellspacing="0" cellpadding="0">

    <tr>



      <th class="list_th_class_bg">  <?php echo t('Name') ?>

      </th>



      <th class="list_th_class_bg"> <?php echo t('Date') ?> </th>



      <th class="list_th_class_bg">

        <?php echo t('Category') ?>

      </th>





      <th class="list_th_class_bg">

        <?php echo t('Actions') ?>

      </th>

      <th class="list_th_class_bg"> <?php echo t('Save as a draft') ?> </th>



    </tr>

    <?php foreach ($pageList as $cobj) {



      if (is_object($cobj)) {

        $section_id = $cobj->getCollectionParentID();

        $sec_page = Page::getByID($section_id);

        if ($sec_page->cParentID != 1) {

          $prefix = Page::getByID($sec_page->cParentID)->getCollectionName() . '-';

        } else {

          $prefix = '';

        }

        $page_section = $prefix . $sec_page->getCollectionName();

        $pkt = Loader::helper('concrete/urls');

      } ?>

      <tr>



        <td class="align_top"><a href="<?php echo $nh->getLinkToCollection($cobj) ?>">

            <?php echo $cobj->getCollectionName() ?>

          </a></td>



        <td>

          <?php

          echo date('M d, Y', strtotime($cobj->getCollectionDatePublic()));

          ?>

        </td>



        <td>

          <?= $cobj->getAttribute('news_category') ?>

        </td>



        <td class="align_top"><a

            href="<?php echo $this->url('/dashboard/news/add_edit', 'edit', $cobj->getCollectionID()) ?>"

            class="pagetooltip btn btn-info">Edit</a> <a

            href="<?php echo $this->url('/dashboard/news/news_list', 'delete_check', $cobj->getCollectionID(), $cobj->getCollectionName()) ?>"

            class="pagetooltip btn btn-danger">Delete</a>

            </td>

            <td><?php
                    if (!$cobj->isActive()) {
                        echo '<a class="btn btn-success" href="' . $this->url('/dashboard/news/news_list', 'approvethis', $cobj->getCollectionID(), base64_encode($cobj->getCollectionName())) . '">Approve This</a>';
                    } else {
                        echo 'Published';
                    }
                    ?></td>



      </tr>

    <?php } ?>

  </table>

  <br />

  <?php if ($showPagination) { ?>

    <?php echo $pagination; ?>

  <?php } ?>

<?php } else {

  print t('No page entries found.');

} ?>

<div class="ccm-search-results-pagination"></div>
