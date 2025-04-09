<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>
<section class="about-sec style-set-2">
  <div class="container">
    <div class="row">
      <div class="col-md-5 col-12 col-vid">
          <div class="vid-wrap">
             <iframe src="<?php echo h($video_url); ?>" width="" height=""></iframe>
          </div>
      </div>
      <div class="col-md-4 col-12 col-cont">
      <?php if (!empty($title)): ?><h2><?php echo h($title); ?></h2><?php endif; ?>
<?php if (!empty($content)): ?><?php echo str_replace('/>', '>', $content); ?><?php endif; ?>
<?php if (!empty($link_link)): ?>
    <a class="btn-line" href="<?php echo $link_link; ?><?php echo $link_ending; ?>" title="<?php echo h($link_title); ?>" <?php echo $link_new_window; ?>>
        <?php echo nl2br(h($link_text), false); ?>
    </a>
    <?php endif; ?>
      </div>
      <div class="col-md-3 col-12 col-img">
     <?php if (!empty($image_left_link)): ?><img src="<?php echo $image_left_link; ?>" alt="<?php echo h($image_left_alt); ?>" width="<?php echo $image_left_width; ?>" height="<?php echo $image_left_height; ?>"><?php endif; ?>
<?php if (!empty($image_right_link)): ?><img src="<?php echo $image_right_link; ?>" alt="<?php echo h($image_right_alt); ?>" width="<?php echo $image_right_width; ?>" height="<?php echo $image_right_height; ?>"><?php endif; ?>
      </div>
    </div>
  </div>
</section>
