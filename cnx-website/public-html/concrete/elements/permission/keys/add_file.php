<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<?php $included = $permissionAccess->getAccessListItems(); ?>
<?php $excluded = $permissionAccess->getAccessListItems(PermissionKey::ACCESS_TYPE_EXCLUDE); ?>
<?php

$extensions = Loader::helper('concrete/file')->getAllowedFileExtensions();

?>
<?php $form = Loader::helper('form'); ?>

<?php if (count($included) > 0 || count($excluded) > 0) {
    ?>

<?php if (count($included) > 0) {
    ?>

<fieldset>

<legend><?=t('Who can add what?')?></legend>

<?php foreach ($included as $assignment) {
    $entity = $assignment->getAccessEntityObject();
    ?>


<div class="form-group">
	<label class="col-form-label"><?=$entity->getAccessEntityLabel()?></label>
	<?=$form->select('fileTypesIncluded[' . $entity->getAccessEntityID() . ']', array('A' => t('All File Types'), 'C' => t('Custom')), $assignment->getFileTypesAllowedPermission())?>
</div>


<div data-list="extensions" class="form-group" <?php if ($assignment->getFileTypesAllowedPermission() != 'C') {
?>style="display: none"<?php
}
?>>
<?php
$index=1;
foreach ($extensions as $ext) {
$checked = ($assignment->getFileTypesAllowedPermission() == 1 || ($assignment->getFileTypesAllowedPermission() == 'C' && in_array($ext, $assignment->getFileTypesAllowedArray())));
?>
		<div class="form-check"><input type="checkbox" class="form-check-input" id="extensionInclude[<?=$entity->getAccessEntityID()?>][]_<?=$index?>" name="extensionInclude[<?=$entity->getAccessEntityID()?>][]" value="<?=$ext?>" <?php if ($checked) {
?> checked="checked" <?php
}
?> /> <label for="extensionInclude[<?=$entity->getAccessEntityID()?>][]_<?=$index?>" class="form-check-label"> <?=$ext?></label></div>
	<?php
    $index++;
}
?>
</div>

</fieldset>


<?php
}
}
    ?>


<?php if (count($excluded) > 0) {
    ?>

<fieldset>
<legend><?=t('Who can\'t add what?')?></legend>

<?php foreach ($excluded as $assignment) {
    $entity = $assignment->getAccessEntityObject();
    ?>


<div class="form-group">
	<label><?=$entity->getAccessEntityLabel()?></label>
	<?=$form->select('fileTypesExcluded[' . $entity->getAccessEntityID() . ']', array('N' => t('No File Types'), 'C' => t('Custom')), $assignment->getFileTypesAllowedPermission())?>

</div>


<div data-list="extensions" class="form-group" <?php if ($assignment->getFileTypesAllowedPermission() != 'C') {
?>style="display: none"<?php
}
?>>
<?php
$index =1;
foreach ($extensions as $ext) {
$checked = in_array($ext, $assignment->getFileTypesAllowedArray());
?>
		<div class="form-check"><input class="form-check-input" type="checkbox" id="extensionExclude[<?=$entity->getAccessEntityID()?>][]_<?=$index?>" name="extensionExclude[<?=$entity->getAccessEntityID()?>][]" value="<?=$ext?>" <?php if ($checked) {
?> checked="checked" <?php
}
?> /> <label for="extensionExclude[<?=$entity->getAccessEntityID()?>][]_<?=$index?>" class="form-check-input"> <?=$ext?></label></div>
	<?php
    $index++;
}
?>
</div>


</fieldset>

<?php
}
}
    ?>

<?php
} else {
    ?>
	<p><?=t('No users or groups selected.')?></p>
<?php
} ?>


<script type="text/javascript">
$(function() {
	$("#ccm-tab-content-custom-options select").change(function() {
		if ($(this).val() == 'C') {
			$(this).closest('fieldset').find('div[data-list=extensions]').show();
		} else {
			$(this).closest('fieldset').find('div[data-list=extensions]').hide();
		}
	});
});
</script>
