<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>

  <div class="cont-sidebar">
      <a class="img-outer" href="<?php echo $link_link; ?><?php echo $link_ending; ?>" title="<?php echo h($link_title); ?>" <?php echo $link_new_window; ?>>
<?php if (!empty($image_left_link)): ?>
    <?php // Original image ?>
    <img src="<?php echo $image_left_link; ?>" alt="<?php echo h($image_left_alt); ?>" width="<?php echo $image_left_width; ?>" height="<?php echo $image_left_height; ?>">
<?php endif; ?>


<?php if (!empty($image_right_link)): ?>
    <?php // Original image ?>
    <img src="<?php echo $image_right_link; ?>" alt="<?php echo h($image_right_alt); ?>" width="<?php echo $image_right_width; ?>" height="<?php echo $image_right_height; ?>">
<?php endif; ?>
</a>



<div class="cont-outer">
<?php if (!empty($title)): ?>
  <h4>  <?php echo h($title); ?></h4>
<?php endif; ?>


<?php if (!empty($content)): ?>
    <?php echo str_replace('/>', '>', $content); ?>
<?php endif; ?>


<?php if (!empty($link_link)): ?>
    <a class="text-link" href="<?php echo $link_link; ?><?php echo $link_ending; ?>" title="<?php echo h($link_title); ?>" <?php echo $link_new_window; ?>>
        <?php echo nl2br(h($link_text), false); ?>
    </a>
<?php endif; ?>
</div>
</div>
