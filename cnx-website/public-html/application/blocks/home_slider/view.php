<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>
<?php if (is_array($entries) and count($entries)) : ?>
    <div class="banner-slider">
        <?php foreach ($entries as $entry) : ?>
            <div class="banner-item">
                <?php if (!empty($entry['image_link'])) : ?><img src="<?php echo $entry['image_link']; ?>" alt="<?php echo h($entry['image_alt']); ?>" width="<?php echo $entry['image_width']; ?>" height="<?php echo $entry['image_height']; ?>"><?php endif; ?>
                <div class="banner-cont">
                    <?php if (!empty($entry['title'])) : ?><h1 class="banner-title"><?php echo h($entry['title']); ?></h1><?php endif; ?>
                    <?php if (!empty($entry['content'])) : ?><?php echo str_replace('/>', '>', $entry['content']); ?><?php endif; ?>
                    <?php if (!empty($entry['link_link'])) : ?>
                        <a class="btn btn-black" href="<?php echo $entry['link_link']; ?><?php echo $entry['link_ending']; ?>" title="<?php echo h($entry['link_title']); ?>" <?php echo $entry['link_new_window']; ?>>
                            <?php echo nl2br(h($entry['link_text']), false); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="slick-slider-dots"></div>
<?php endif; ?>
