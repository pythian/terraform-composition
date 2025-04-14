<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>
<section class="features-sec">
  <div class="container">
    <div class="row">
<?php if (!empty($title)): ?><div class="col-md-6 col-12"><h2><?php echo h($title); ?></h2></div><?php endif; ?>
<?php if (!empty($content)): ?><div class="col-md- col-12"><?php echo str_replace('/>', '>', $content); ?></div><?php endif; ?>
    </div>
  </div>

<?php if (is_array($entries) AND count($entries)): ?>
<div class="features-slider">
<?php foreach ($entries as $entry): ?>
    <div class="feature-item">
			<a href="<?php echo $entry['link_link']; ?><?php echo $entry['link_ending']; ?>" title="<?php echo h($entry['link_title']); ?>" <?php echo $entry['link_new_window']; ?>>

<?php if (!empty($entry['image_link'])): ?><img src="<?php echo $entry['image_link']; ?>" alt="<?php echo h($entry['image_alt']); ?>" width="<?php echo $entry['image_width']; ?>" height="<?php echo $entry['image_height']; ?>"><?php endif; ?>
        <div class="feature-text">
<?php if (!empty($entry['title'])): ?><h4><?php echo h($entry['title']); ?></h4><?php endif; ?>
<?php if (!empty($entry['content'])): ?><?php echo str_replace('/>', '>', $entry['content']); ?><?php endif; ?>
<?php if (!empty($entry['link_link'])): ?>
	<?php echo nl2br(h($entry['link_text']), false); ?>
	<?php endif; ?>



        </div>
				    </a>
      </div>
<?php endforeach; ?>
</div>
<?php endif; ?>
</section>
