<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>

<div class="text-wrap">
<?php if (!empty($title)): ?>
   <h2><?php echo h($title); ?></h2>
<?php endif; ?>


<?php if (!empty($content)): ?>
    <?php echo str_replace('/>', '>', $content); ?>
<?php endif; ?>
</div>
