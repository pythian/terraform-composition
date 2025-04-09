<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>



<?php // Repeatable entries ?>

<?php if (is_array($entries) AND count($entries)): ?>
<section class="img-cont-highlights">
    <?php foreach ($entries as $entry): ?>

    <div class="highlight-row row g-0 no-gutters">

<div class="col-md-6 col-12 col-cont">
        <?php if (!empty($entry['title'])): ?>
          <h2>  <?php echo h($entry['title']); ?></h2>
        <?php endif; ?>


        <?php if (!empty($entry['content'])): ?>
            <?php echo str_replace('/>', '>', $entry['content']); ?>
        <?php endif; ?>


        <?php if (!empty($entry['link_link'])): ?>
            <a class="btn-line" href="<?php echo $entry['link_link']; ?><?php echo $entry['link_ending']; ?>" title="<?php echo h($entry['link_title']); ?>" <?php echo $entry['link_new_window']; ?>>
                <?php echo nl2br(h($entry['link_text']), false); ?>
            </a>
        <?php endif; ?>
      </div>

      <div class="col-md-6 col-12 col-img">
        <div class="img-wrap">
        <?php if (!empty($entry['image_link'])): ?>
            <?php // Original image ?>
            <img class="normal-img" src="<?php echo $entry['image_link']; ?>" alt="<?php echo h($entry['image_alt']); ?>" width="<?php echo $entry['image_width']; ?>" height="<?php echo $entry['image_height']; ?>">
        <?php endif; ?>
      </div>
    </div>

        <?php if (!empty($entry['image_position'])): ?>
             <?/*php //echo $entry['image_position']; ?><br>
             <?php// echo h($entry_image_position_options[$entry['image_position']] ?? '')*/?>
        <?php endif; ?>
</div>

    <?php endforeach; ?>
</section>
<?php endif; ?>
