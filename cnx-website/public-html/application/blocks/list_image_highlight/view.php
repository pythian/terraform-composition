<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>

<section class="service-boxes inside-highlights list-highlights">
    <div class="container">
        <div class="text-wrap">
<?php if (!empty($title)): ?>
  <h2>  <?php echo h($title); ?></h2>
<?php endif; ?>


<?php if (!empty($content)): ?>
    <?php echo str_replace('/>', '>', $content); ?>
<?php endif; ?>
</div>


<?php // Repeatable entries ?>

<?php if (is_array($entries) AND count($entries)): ?>
  <div class="row">
    <?php foreach ($entries as $entry): ?>
      <div class="col-md-3 col-12 col-box">

            <?php if (!empty($entry['link_link'])) {  ?>
            <a class="item-wrap" href="<?php echo $entry['link_link']; ?><?php echo $entry['link_ending']; ?>" title="<?php echo h($entry['link_title']); ?>" <?php echo $entry['link_new_window']; ?>>
                <?php }else{ ?>
  <div class="item-wrap">
      <?php } ?>
      <div class="item-icon">
        <?php if (!empty($entry['image_link'])): ?>
            <?php // Original image ?>
            <img class="normal-img" src="<?php echo $entry['image_link']; ?>" alt="<?php echo h($entry['image_alt']); ?>">
        <?php endif; ?>


        <?php if (!empty($entry['hover_image_link'])): ?>
            <?php // Original image ?>
            <img class="hover-img" src="<?php echo $entry['hover_image_link']; ?>" alt="<?php echo h($entry['hover_image_alt']); ?>">
        <?php endif; ?>

</div>
        <?php if (!empty($entry['title'])): ?>
            <h3>  <?php echo h($entry['title']); ?>  </h3>
        <?php endif; ?>
         <?php if (!empty($entry['link_link'])) {  ?>

            </a>


<?php } else { ?>
</div> <?php } ?></div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
</div>
</section>
