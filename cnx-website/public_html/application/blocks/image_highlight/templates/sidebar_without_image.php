<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>


<?php if (!empty($link_link)): ?><?php endif; ?>
  <a class="cont-graphic-sidebar" href="<?php echo $link_link; ?><?php echo $link_ending; ?>" title="<?php echo h($link_title); ?>" <?php echo $link_new_window; ?>>
<?php if (!empty($title)): ?>
  <h4>  <?php echo h($title); ?></h4>
<?php endif; ?>


<?php if (!empty($content)): ?>
    <?php echo str_replace('/>', '>', $content); ?>
<?php endif; ?>

<span class="text-link"><?php echo nl2br(h($link_text), false); ?></span>
    </a>
