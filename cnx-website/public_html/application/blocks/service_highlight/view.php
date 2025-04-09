<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>
<?php if (is_array($entries) and count($entries)) : ?>
    <section class="service-boxes">
        <div class="container">
            <div class="row">
                <?php foreach ($entries as $entry) : ?>
                    <div class="col-md-3 col-12 col-box">
                         <?php if (!empty($entry['link_link'])) { ?>
                         <a href="<?php echo $entry['link_link']; ?><?php echo $entry['link_ending']; ?>" title="<?php echo h($entry['link_title']); ?>" <?php echo $entry['link_new_window']; ?> class="item-wrap">
                    <?php }else{ ?>
                    <div class="item-wrap">
                        <?php } ?>
                            <h4>
                                <?php if (!empty($entry['image_link'])) : ?><img class="normal-img" src="<?php echo $entry['image_link']; ?>" alt="<?php echo h($entry['image_alt']); ?>" width="<?php echo $entry['image_width']; ?>" height="<?php echo $entry['image_height']; ?>"><?php endif; ?>
                                <?php if (!empty($entry['hover_image_link'])) : ?><img class="hover-img" src="<?php echo $entry['hover_image_link']; ?>" alt="<?php echo h($entry['hover_image_alt']); ?>" width="<?php echo $entry['hover_image_width']; ?>" height="<?php echo $entry['hover_image_height']; ?>"><?php endif; ?>
                                <?php if (!empty($entry['title'])) : ?><?php echo h($entry['title']); ?><?php endif; ?>
                            </h4>
                            <?php if (!empty($entry['content'])) : ?><?php echo str_replace('/>', '>', $entry['content']); ?><?php endif; ?>


                         <?php if (!empty($entry['link_link'])) { ?>
                       </a>
                    <?php }else{ ?>
                  </div>
                        <?php } ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
