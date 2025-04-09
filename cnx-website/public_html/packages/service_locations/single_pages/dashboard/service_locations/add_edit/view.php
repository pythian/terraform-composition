<style type="text/css">
/* .form-group {

  padding-bottom: 45px;
} */
.help {
	font-style: normal;
	font-weight: normal;
	border-color: #02890d;
	border-width: 1px;
	border-style: solid;
	max-width: 235px;
	padding: 16px;
	MARGIN-left: 85px;
	background-color: #f5f5f5;
	position: absolute;
	-moz-border-radius: 5px;
-webkit-border-radius: 5px;
}
#dates_wrap div {
	margin-top: 12px;
}
.small {
	width: 52px!important;
}
</style>
<?php
$pkg = Package::getByHandle("service_locations");

	$df = Loader::helper('form/date_time');
	Loader::model("attribute/categories/collection");
	if (isset($page) && is_object($page)) {
		$pageTitle = $page->getCollectionName();
		$pageDescription = $page->getCollectionDescription();
		$pageDate = $page->getCollectionDatePublic();
		$cParentID = $page->getCollectionParentID();
		$ctID = $page->getCollectionTypeID();
		$task = 'update';
		$buttonText = t('Update Page');
		$title = 'Update';
	} else {
		$task = 'add';
		$buttonText = t('Add Page Entry');
		$title = 'Add';
	}

	?>
<?php //echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Service Locations Add/Edit').'<span class="label" style="position:relative;top:-3px;left:12px;">'.t('* required field').'</span>', false, false, false);?>
<?php   if ($this->controller->getTask() == 'edit') { ?>
<form method="post" action="<?php  echo $this->action($task,$page->getCollectionID())?>" id="page-form">
<?php  echo $form->hidden('pageID', $page->getCollectionID())?>
<?php   }else{ ?>
<form method="post" action="<?php  echo $this->action($task)?>" id="page-form">
  <?php  } ?>
  <fieldset>
    <legend><?php echo t('Basic Attributes')?></legend>
    <div class="row">
		<div class="col-sm-8">
		<div class="form-group">
	<?php  echo $form->label('pageTitle', t('Page Title'))?>
                      <div class="float-end">
            <span class="text-muted small">
                Required            </span>
            </div>
            <?php  echo $form->text('pageTitle', isset($pageTitle)?$pageTitle:'')?>		</div>

			<div class="form-group">
	<?php  echo $form->label('pageDescription', t('Page Description'))?>
            <?php   echo $form->textarea('pageDescription', isset($pageDescription)?$pageDescription:'')?>		</div>
			<div class="form-group">
			<?php  echo $form->label('cParentID', t('Parent Page'))?>
                      <div class="float-end">
            <span class="text-muted small">
                Required            </span>
            </div>
            <?php   if (count($sections) == 0) { ?>
            <?php  echo t('No sections defined. Please create a page with the attribute "service_locations_section" set to true.')?>
            <?php   } else { ?>
            <?php  echo $form->select('cParentID', $sections,isset($cParentID)?$cParentID:'' )?>
            <?php   } ?>		</div>
			<div class="form-group">
			<?php  echo $form->label('pageTitle', t('Date/Time'))?>
			<?php   echo $df->datetime('page_date_time', isset($pageDate)?$pageDate:'')?>		</div>

		</div>

    </div>


    <?php
   Loader::model('config');
  echo  $form->hidden('ctID',$pkg->getConfig()->get('service.SERVICE_LOCATIONS_COLLECTION_TYPE_ID'));
  echo  $form->hidden('ptID',$pkg->getConfig()->get('service.SERVICE_LOCATIONS_PAGE_TEMPLATE_ID')) ?>
  </fieldset>
  <fieldset>
    <legend><?php echo t('Addtional Attributes')?></legend>
    <div class="row">
		<div class="col-sm-8">
  <?php
     Loader::model('config');
	$attributeset_id=$pkg->getConfig()->get('service.SERVICE_LOCATIONS_ATTRIBUTE_SET_ID');
	//print_r($attributeset_id);
	   if(isset($attributeset_id) && $attributeset_id>0){
    $set = AttributeSet::getByID($attributeset_id);
	if(is_object($set)){
		$setAttribs = $set->getAttributeKeys();
			if($setAttribs){
				foreach ($setAttribs as $ak) {
					if(isset($page) && is_object($page)) {
						$aValue = $page->getAttributeValueObject($ak);
					}

	 ?>
	 	<div class="form-group">
	<?php  echo $ak->render('label')?>
            <?php   $ak->render('form', isset($aValue)?$aValue:'')?>		</div>
	 <?php
				}
			}
	}else{
		echo "No Attribute Set Defined/Created";
	}
}else{
	echo "No Attribute Set Defined/Created";
}


    	?>
		</div>
	</div>
  </fieldset>

  <div class="ccm-dashboard-form-actions-wrapper">
    <div class="ccm-dashboard-form-actions"> <a href="<?php echo View::url('/dashboard/service_locations/service_list')?>" class="btn btn-secondary float-start"><?php echo t('Cancel')?></a> <?php echo Loader::helper("form")->submit('add', t($title.' Page'), array('class' => 'btn btn-primary float-end'))?> </div>
  </div>
</form>
