<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>
<section class="contact-highlight">
<?php if (!empty($image_link)): ?><img src="<?php echo $image_link; ?>" alt="<?php echo h($image_alt); ?>" width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>"><?php endif; ?>
  <div class="highlight-wrap">
    <div class="container">
      <div class="row">
        <div class="col-md-9 col-12">
<?php if (!empty($title)): ?><h2><?php echo h($title); ?></h2><?php endif; ?>
<?php if (!empty($content)): echo str_replace('/>', '>', $content); endif; ?>
        </div>
<?php if (!empty($link_link)): ?>
<div class="col-md-3 col-12">
    <a class="btn btn-line" href="<?php echo $link_link; ?><?php echo $link_ending; ?>" title="<?php echo h($link_title); ?>" <?php echo $link_new_window; ?>>
        <?php echo nl2br(h($link_text), false); ?>
    </a>
</div>
<?php endif; ?>
      </div>
    </div>
  </div>
</section>
