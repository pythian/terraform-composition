<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>
<section class="inside-banner has-graphic">
<?php if (!empty($image_link)): ?>
    <?php // Original image ?>
    <img src="<?php echo $image_link; ?>" alt="<?php echo h($image_alt); ?>" width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>">
<?php endif; ?>


<div class="banner-cont">
    <div class="container">
<?php if (!empty($title)): ?>
  <h1> <?php echo h($title); ?></h1>
  <?php else: ?>
                      <h1>  <?php echo $c->getCollectionName(); ?></h1>
<?php endif; ?>
</div>
</div>
</section>
