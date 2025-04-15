<?php defined('C5_EXECUTE') or die(_("Access Denied."));
$form = Loader::helper('form'); ?>
<form method="post" class="form-horizontal"  id="settings-form" action="<?php echo $this->action('save_settings');?>">
  <?php  echo $this->controller->token->output('save_settings')?>
  <fieldset>
    <legend><?php echo t('Settings')?></legend>
    <div class="row">
      <div class="form-group">
        <?php  echo $form->label('NEWS_ATTRIBUTE_SET_ID', t('Choose Attribute Set'),array('class'=>'col-sm-3'))?>
        <div class="col-sm-7">
          <div class="input-group">
           <?php if(sizeof($attribute_sets)>0){ ?>
      <?php echo $form->select('NEWS_ATTRIBUTE_SET_ID',$attribute_sets,$attribute_set_id)?>
      <?php }else { echo 'No Attribute sets found'; }?>
            <span class="input-group-addon"><i class="fa fa-asterisk"></i></span> </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form-group">
        <?php  echo $form->label('NEWS_COLLECTION_TYPE_ID', t('Choose Page Template'),array('class'=>'col-sm-3'))?>
        <div class="col-sm-7">
          <div class="input-group">
          <?php if(sizeof($attribute_sets)>0){ ?>
      <?php echo $form->select('NEWS_PAGE_TEMPLATE_ID',$PageTemplates,$page_template_id)?>
      <?php }else { echo 'No Pagetypes found'; }?>
            <span class="input-group-addon"><i class="fa fa-asterisk"></i></span> </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form-group">
        <?php  echo $form->label('NEWS_COLLECTION_TYPE_ID', t('Choose Page Type'),array('class'=>'col-sm-3'))?>
        <div class="col-sm-7">
          <div class="input-group">
          <?php if(sizeof($attribute_sets)>0){ ?>
      <?php echo $form->select('NEWS_PAGE_TYPE_ID',$PageTypes,$page_type_id)?>
      <?php }else { echo 'No Pagetypes found'; }?>
            <span class="input-group-addon"><i class="fa fa-asterisk"></i></span> </div>
        </div>
      </div>
    </div>
  </fieldset>
  <div class="ccm-dashboard-form-actions-wrapper">
    <div class="ccm-dashboard-form-actions"> <a href="<?php echo View::url('/dashboard/news')?>" class="btn btn-default pull-left"><?php echo t('Cancel')?></a> <?php echo Loader::helper("form")->submit('add', 'Save Settings', array('class' => 'btn btn-primary pull-right'))?> </div>
  </div>
</form>
