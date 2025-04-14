<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>


<?php if (!empty($title)): ?>
    <?php echo h($title); ?>
<?php endif; ?>


<?php if (!empty($content)): ?>
    <?php echo str_replace('/>', '>', $content); ?>
<?php endif; ?>



<?php // Repeatable entries ?>

<?php if (is_array($entries) AND count($entries)): ?>

    <?php foreach ($entries as $entry): ?>


        <?php if (!empty($entry['title'])): ?>
            <?php echo h($entry['title']); ?>
        <?php endif; ?>


        <?php if (!empty($entry['description'])): ?>
            <?php echo str_replace('/>', '>', $entry['description']); ?>
        <?php endif; ?>


    <?php endforeach; ?>

<?php endif; ?>
