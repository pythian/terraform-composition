<?php defined('C5_EXECUTE') or die('Access Denied.');
$c= Page::getCurrentPage(); ?>

<section class="inside-banner has-graphic no-img">
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
