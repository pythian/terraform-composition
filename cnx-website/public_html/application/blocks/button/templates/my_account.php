<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>


<?php if (!empty($link_link)): ?>
    <a class="my-account" href="<?php echo $link_link; ?><?php echo $link_ending; ?>" title="<?php echo h($link_title); ?>" <?php echo $link_new_window; ?>>
        <i class="fa fa-lock"></i><?php echo nl2br(h($link_text), false); ?>
    </a>
<?php endif; ?>
