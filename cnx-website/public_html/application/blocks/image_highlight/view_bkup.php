<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>


<?php if (!empty($image_left_link)): ?>
    <?php // Original image ?>
    <img src="<?php echo $image_left_link; ?>" alt="<?php echo h($image_left_alt); ?>" width="<?php echo $image_left_width; ?>" height="<?php echo $image_left_height; ?>">
<?php endif; ?>

<?php if (!empty($image_left_fullscreenLink)): ?>
    <?php // Fullscreen image ?>
    <img src="<?php echo $image_left_fullscreenLink; ?>" alt="<?php echo h($image_left_alt); ?>" width="<?php echo $image_left_fullscreenWidth; ?>" height="<?php echo $image_left_fullscreenHeight; ?>">
<?php endif; ?>

<?php if (!empty($image_left_thumbnailLink)): ?>
    <?php // Thumbnail image ?>
    <img src="<?php echo $image_left_thumbnailLink; ?>" alt="<?php echo h($image_left_alt); ?>" width="<?php echo $image_left_thumbnailWidth; ?>" height="<?php echo $image_left_thumbnailHeight; ?>">
<?php endif; ?>


<?php if (!empty($image_right_link)): ?>
    <?php // Original image ?>
    <img src="<?php echo $image_right_link; ?>" alt="<?php echo h($image_right_alt); ?>" width="<?php echo $image_right_width; ?>" height="<?php echo $image_right_height; ?>">
<?php endif; ?>

<?php if (!empty($image_right_fullscreenLink)): ?>
    <?php // Fullscreen image ?>
    <img src="<?php echo $image_right_fullscreenLink; ?>" alt="<?php echo h($image_right_alt); ?>" width="<?php echo $image_right_fullscreenWidth; ?>" height="<?php echo $image_right_fullscreenHeight; ?>">
<?php endif; ?>

<?php if (!empty($image_right_thumbnailLink)): ?>
    <?php // Thumbnail image ?>
    <img src="<?php echo $image_right_thumbnailLink; ?>" alt="<?php echo h($image_right_alt); ?>" width="<?php echo $image_right_thumbnailWidth; ?>" height="<?php echo $image_right_thumbnailHeight; ?>">
<?php endif; ?>


<?php if (!empty($title)): ?>
    <?php echo h($title); ?>
<?php endif; ?>


<?php if (!empty($content)): ?>
    <?php echo str_replace('/>', '>', $content); ?>
<?php endif; ?>


<?php if (!empty($link_link)): ?>
    <a href="<?php echo $link_link; ?><?php echo $link_ending; ?>" title="<?php echo h($link_title); ?>" <?php echo $link_new_window; ?>>
        <?php echo nl2br(h($link_text), false); ?>
    </a>
<?php endif; ?>
