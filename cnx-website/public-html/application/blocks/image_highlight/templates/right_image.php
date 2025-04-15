<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>

  <div class="highlight-row row g-0 no-gutters">


  <div class="col-md-6 col-12 col-cont">
<?php if (!empty($title)): ?>
  <h2>  <?php echo h($title); ?></h2>
<?php endif; ?>


<?php if (!empty($content)): ?>
    <?php echo str_replace('/>', '>', $content); ?>
<?php endif; ?>


<?php if (!empty($link_link)): ?>
    <a class="btn-link" href="<?php echo $link_link; ?><?php echo $link_ending; ?>" title="<?php echo h($link_title); ?>" <?php echo $link_new_window; ?>>
        <?php echo nl2br(h($link_text), false); ?>
    </a>
<?php endif; ?>
</div>
<div class="col-md-6 col-12 col-img">
  <div class="img-wrap">
<?php if (!empty($image_right_link)): ?>
    <?php // Original image ?>
    <img  class="normal-img" src="<?php echo $image_right_link; ?>" alt="<?php echo h($image_right_alt); ?>" width="<?php echo $image_right_width; ?>" height="<?php echo $image_right_height; ?>">
<?php endif; ?>
</div>
</div>
</div>
