<?php
    defined('C5_EXECUTE') or die('Access Denied.');
    /** @var \Concrete\Block\Content\Controller $controller */
    /** @var string $content */

    $c = \Concrete\Core\Page\Page::getCurrentPage();
    if (!$content && is_object($c) && $c->isEditMode()) {
        ?>
		<div class="ccm-edit-mode-disabled-item"><?=t('Empty Content Block.')?></div>
	<?php
    } else {
        echo $content;
    }
