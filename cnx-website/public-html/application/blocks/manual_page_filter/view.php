<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>
<div class="container">
    <?php if (!empty($title)) : ?><h2><?php echo h($title); ?></h2><?php endif; ?>
    <?php if (is_array($entries) and count($entries)) : ?>
        <div class="select-wrap">
            <ul>
              <li class="init"><a href="/">Select</a></li>
                <?php foreach ($entries as $entry) : ?>
                    <?php if (!empty($entry['select_page_link'])) : ?>
                        <li><a href="<?php echo $entry['select_page_link']; ?><?php echo $entry['select_page_ending']; ?>" title="<?php echo h($entry['select_page_title']); ?>" <?php echo $entry['select_page_new_window']; ?>>
                                <?php echo nl2br(h($entry['select_page_text']), false); ?>
                            </a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>
<script>
$(document).ready(function(){
  $(".select-wrap ul").on("click", ".init", function() {
      $(".select-wrap").toggleClass("open");
      $(this).closest(".select-wrap ul").children('li:not(.init)').toggle();
  });

  var allOptions = $(".select-wrap ul").children('li:not(.init)');
  $(".select-wrap ul").on("click", "li:not(.init)", function() {
      allOptions.removeClass('selected');
      $(this).addClass('selected');
      $(".select-wrap ul").children('.init').html($(this).html());
      allOptions.toggle();
  });
});
</script>
