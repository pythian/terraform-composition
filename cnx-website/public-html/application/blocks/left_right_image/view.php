<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>



<?php // Repeatable entries ?>

<?php if (is_array($entries) AND count($entries)): ?>

    <?php foreach ($entries as $entry): ?>


        <?php if (!empty($entry['title'])): ?>
            <?php echo h($entry['title']); ?>
        <?php endif; ?>


        <?php if (!empty($entry['content'])): ?>
            <?php echo str_replace('/>', '>', $entry['content']); ?>
        <?php endif; ?>


        <?php if (!empty($entry['image_link'])): ?>
            <?php // Original image ?>
            <img src="<?php echo $entry['image_link']; ?>" alt="<?php echo h($entry['image_alt']); ?>" width="<?php echo $entry['image_width']; ?>" height="<?php echo $entry['image_height']; ?>">
        <?php endif; ?>


    <?php endforeach; ?>

<?php endif; ?>
