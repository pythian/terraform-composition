<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>
<?php // Repeatable entries ?>
<?php if (is_array($entries) AND count($entries)): ?>
 <div class="banner-highlights">
    <?php foreach ($entries as $entry): ?>
			<?php if (!empty($entry['link_link'])){ ?>
				  <a class="highlight-item" href="<?php echo $entry['link_link']; ?><?php echo $entry['link_ending']; ?>" title="<?php echo h($entry['link_title']); ?>" <?php echo $entry['link_new_window']; ?>>
					<?php }else{ ?>
     <div class="highlight-item">
		 <?php } ?>
            <?php if (!empty($entry['image_link'])): ?>
            <?php // Original image ?>
            <img src="<?php echo $entry['image_link']; ?>" alt="<?php echo h($entry['image_alt']); ?>" width="<?php echo $entry['image_width']; ?>" height="<?php echo $entry['image_height']; ?>">
        <?php endif; ?>
        <div class="highlight-cont">
        <?php if (!empty($entry['title'])): ?><h3><?php echo h($entry['title']); ?></h3><?php endif; ?>
        <?php if (!empty($entry['content'])): ?><?php echo str_replace('/>', '>', $entry['content']); ?><?php endif; ?>
				   </div>
				<?php if (!empty($entry['link_link'])){ ?>
				</a>
						<?php }else{ ?>
	       </div>
			 <?php } ?>


    <?php endforeach; ?>
</div>
<?php endif; ?>
