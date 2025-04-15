<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>

<div id="form-container-<?php echo $uniqueID; ?>">

    <div class="tab-content mt-4">

        <div class="js-tab-pane">

            <div class="mb-4">
                <?php echo $form->label($view->field('title'), t('Title')); ?>
                <?php echo $form->text($view->field('title'), $title, ['maxlength'=>'255']); ?>
            </div>

            <div class="mb-4 js-custom-editor-height-<?php echo $view->field('content'); ?>-<?php echo $uniqueID; ?>">
                <?php echo $form->label($view->field('content'), t('Content')); ?>
                <?php echo $app->make('editor')->outputBlockEditModeEditor($view->field('content'), $content); ?>
            </div>

        </div>

    </div>

</div>
