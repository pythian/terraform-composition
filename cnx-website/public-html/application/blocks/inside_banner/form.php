<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>

<div id="form-container-<?php echo $uniqueID; ?>">

    <div class="tab-content mt-4">

        <div class="js-tab-pane">

            <div class="mb-4">
                <?php echo $form->label($view->field('title'), t('Title')); ?>
                <?php echo $form->text($view->field('title'), $title, ['maxlength'=>'255']); ?>
            </div>

            <hr/>

            <div class="mb-4 js-image-wrapper">

                <div class="row margin-bottom">
                    <div class="col-12">
                        <?php echo $form->label($view->field('image'), t('Image')); ?>
                    </div>
                    <div class="col-12 col-lg-6 margin-bottom-on-mobile">
                        <?php echo $app->make('helper/concrete/asset_library')->image('image-'.$uniqueID, $view->field('image'), t('Choose Image'), !empty($image) ? File::getByID($image) : null); ?>
                    </div>
                    <div class="col-12 col-lg-6">
                        <span class="toggle-additional-image-fields <?php if (!empty($image_data['show_additional_fields'])): ?>toggle-additional-image-fields-active<?php endif; ?> btn btn-secondary js-toggle-additional-image-fields-image-<?php echo $uniqueID; ?>"
                            data-show-text="<?php echo t('Show additional fields'); ?>"
                            data-hide-text="<?php echo t('Hide additional fields'); ?>"
                        ><i class="fas fa-caret-right"></i> <span class="js-toggle-additional-image-fields-text-image-<?php echo $uniqueID; ?>"><?php if (!empty($image_data['show_additional_fields'])): ?><?php echo t('Hide additional fields'); ?><?php else: ?><?php echo t('Show additional fields'); ?><?php endif; ?></span></span>
                        <?php echo $form->hidden($view->field('image_show_additional_fields'), $image_data['show_additional_fields'] ?? null, ['class'=>'js-toggle-additional-image-fields-value-image-'.$uniqueID, 'maxlength'=>'255']); ?>
                    </div>
                </div>

                <div class="js-additional-image-fields-wrapper-image-<?php echo $uniqueID; ?>" <?php if (empty($image_data['show_additional_fields'])): ?>style="display: none;"<?php endif; ?>>

                    <div class="mb-4">
                        <?php echo $form->label($view->field('image_alt'), t('Alt text')); ?>
                        <?php echo $form->text($view->field('image_alt'), $image_alt, ['maxlength'=>'255']); ?>
                    </div>

                </div>

                <script>
                    $(function() {
                        $('.js-toggle-additional-image-fields-image-<?php echo $uniqueID; ?>').on('click', function() {
                            var toggleAdditionalFieldsValue = parseInt($('.js-toggle-additional-image-fields-value-image-<?php echo $uniqueID; ?>').val());
                            var showText = $('.js-toggle-additional-image-fields-image-<?php echo $uniqueID; ?>').attr('data-show-text');
                            var hideText = $('.js-toggle-additional-image-fields-image-<?php echo $uniqueID; ?>').attr('data-hide-text');
                            if (toggleAdditionalFieldsValue) {
                                $('.js-additional-image-fields-wrapper-image-<?php echo $uniqueID; ?>').hide();
                                $('.js-toggle-additional-image-fields-image-<?php echo $uniqueID; ?>').removeClass('toggle-additional-image-fields-active');
                                $('.js-toggle-additional-image-fields-value-image-<?php echo $uniqueID; ?>').val(0);
                                $('.js-toggle-additional-image-fields-text-image-<?php echo $uniqueID; ?>').text(showText);
                            } else {
                                $('.js-additional-image-fields-wrapper-image-<?php echo $uniqueID; ?>').show();
                                $('.js-toggle-additional-image-fields-image-<?php echo $uniqueID; ?>').addClass('toggle-additional-image-fields-active');
                                $('.js-toggle-additional-image-fields-value-image-<?php echo $uniqueID; ?>').val(1);
                                $('.js-toggle-additional-image-fields-text-image-<?php echo $uniqueID; ?>').text(hideText);
                            }
                        });
                    });
                </script>

            </div><?php // .js-image-wrapper ?>

        </div>

    </div>

</div>
