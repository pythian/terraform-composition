<?php defined('C5_EXECUTE') or die("Access Denied.");

$iconTag = $iconTag ?? '';
?>

<?php if ($linkURL) {
    ?>
    <a href="<?=$linkURL?>">
<?php
} ?>
<div class="ccm-block-feature-item-hover-wrapper" data-toggle="tooltip" data-placement="bottom" title="<?=h(strip_tags($paragraph))?>">
    <div class="ccm-block-feature-item-hover">
        <div class="ccm-block-feature-item-hover-icon"><?=$iconTag?></div>
    </div>
    <div class="ccm-block-feature-item-hover-title"><?=h($title)?></div>
</div>

<?php if ($linkURL) {
    ?>
    </a>
<?php
} ?>
