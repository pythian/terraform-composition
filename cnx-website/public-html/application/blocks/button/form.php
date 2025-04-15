<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>

<div id="form-container-<?php echo $uniqueID; ?>">

    <div class="tab-content mt-4">

        <div class="js-tab-pane">

            <div class="mb-4 js-link-wrapper">

                <div class="row margin-bottom">
                    <div class="col-12">
                        <?php echo $form->label($view->field('link_link_type'), t('Link')); ?>
                    </div>
                    <div class="col-12 col-lg-6 margin-bottom-on-mobile">
                        <?php echo $form->select($view->field('link_link_type'), $linkTypes, $link['link_type'] ?? null, ['class' => 'form-select js-link-type-link-'.$uniqueID]); ?>
                    </div>
                    <div class="col-12 col-lg-6">
                        <span class="toggle-additional-fields <?php if (!empty($link['show_additional_fields']) AND $link['show_additional_fields']): ?>toggle-additional-fields-active<?php endif; ?> btn btn-secondary js-toggle-additional-fields-link-<?php echo $uniqueID; ?>"
                            data-show-text="<?php echo t('Show additional fields'); ?>"
                            data-hide-text="<?php echo t('Hide additional fields'); ?>"
                            <?php if (empty($link['link_type'])): ?>style="display: none;"<?php endif; ?>
                        ><i class="fas fa-caret-right"></i> <span class="js-toggle-additional-fields-text-link-<?php echo $uniqueID; ?>"><?php if (!empty($link['show_additional_fields'])): ?><?php echo t('Hide additional fields'); ?><?php else: ?><?php echo t('Show additional fields'); ?><?php endif; ?></span></span>
                        <?php echo $form->hidden($view->field('link_show_additional_fields'), $link['show_additional_fields'] ?? null, ['class'=>'js-toggle-additional-fields-value-link-'.$uniqueID, 'maxlength'=>'255']); ?>
                    </div>
                </div>

                <div class="row margin-bottom js-link-wrapper-link-<?php echo $uniqueID; ?> js-link-wrapper-link_from_sitemap-link-<?php echo $uniqueID; ?>" style="display: none;">
                    <div class="col-12">
                        <?php echo $app->make('helper/form/page_selector')->selectPage($view->field('link_link_from_sitemap'), (!empty($link['link_from_sitemap']) AND !Page::getByID($link['link_from_sitemap'])->isError() AND !Page::getByID($link['link_from_sitemap'])->isInTrash()) ? $link['link_from_sitemap'] : null); ?>
                    </div>
                </div>

                <div class="row margin-bottom  js-link-wrapper-link-<?php echo $uniqueID; ?> js-link-wrapper-link_from_file_manager-link-<?php echo $uniqueID; ?>" style="display: none;">
                    <div class="col-12">
                        <?php echo $app->make('helper/concrete/asset_library')->file('link_link_from_file_manager-'.$uniqueID, $view->field('link_link_from_file_manager'), t('Choose File'), !empty($link['link_from_file_manager']) ? File::getByID($link['link_from_file_manager']) : null); ?>
                    </div>
                </div>

                <div class="row margin-bottom js-link-wrapper-link-<?php echo $uniqueID; ?> js-link-wrapper-external_link-link-<?php echo $uniqueID; ?>" style="display: none;">
                    <div class="col-12 col-lg-3 margin-bottom-on-mobile">
                        <?php echo $form->select($view->field('link_protocol'), $externalLinkProtocols, $link['protocol'] ?? 'http://', ['class'=>'form-select js-external-link-protocol-link-'.$uniqueID]); ?>
                    </div>
                    <div class="col-12 col-lg-9">
                        <?php echo $form->text($view->field('link_external_link'), $link['external_link'] ?? null, ['maxlength'=>'255', 'class'=>'form-control js-external-link-url-link-'.$uniqueID]); ?>
                    </div>
                    <script>
                        $(function() {
                            $('.js-external-link-url-link-<?php echo $uniqueID; ?>').on('keyup change', function() {
                                var url = $(this).val();
                                if (url.indexOf('https://') == 0) {
                                    $(this).val(url.substring(8));
                                    $('.js-external-link-protocol-link-<?php echo $uniqueID; ?>').val(url.substring(0, 8));
                                } else if (url.indexOf('http://') == 0) {
                                    $(this).val(url.substring(7));
                                    $('.js-external-link-protocol-link-<?php echo $uniqueID; ?>').val(url.substring(0, 7));
                                }
                            });
                        });
                    </script>
                </div>

                <div class="row js-additional-fields-wrapper-link-<?php echo $uniqueID; ?>" style="display: none;">
                    <div class="col-12 margin-bottom">
                        <?php echo $form->label($view->field('link_ending'), t('Custom string at the end of URL')); ?>
                        <?php echo $form->text($view->field('link_ending'), $link['ending'] ?? null, ['maxlength'=>'255']); ?>
                        <div class="form-text"><?php echo t('(e.g. #contact-form or ?ccm_paging_p=2)'); ?></div>
                    </div>
                    <div class="col-12 margin-bottom">
                        <?php echo $form->label($view->field('link_text'), t('Text')); ?>
                        <?php echo $form->textarea($view->field('link_text'), $link['text'] ?? null, ['maxlength'=>'255']); ?>
                    </div>
                    <div class="col-12 margin-bottom">
                        <?php echo $form->label($view->field('link_title'), t('Title')); ?>
                        <?php echo $form->text($view->field('link_title'), $link['title'] ?? null, ['maxlength'=>'255']); ?>
                    </div>
                    <div class="col-12">
                        <?php echo $form->label($view->field('link_new_window'), t('Open in new window')); ?>
                        <?php echo $form->select($view->field('link_new_window'), ['0'=>t('No'), '1'=>t('Yes')], $link['new_window'] ?? null); ?>
                    </div>
                </div>

                <script>
                    $(function() {
                        var linkType = $('.js-link-type-link-<?php echo $uniqueID; ?>').val();
                        var toggleAdditionalFieldsValue = $('.js-toggle-additional-fields-value-link-<?php echo $uniqueID; ?>').val();
                        if (linkType!=0) {
                            $('.js-link-wrapper-'+linkType+'-link-<?php echo $uniqueID; ?>').show();
                            if (toggleAdditionalFieldsValue!=0) {
                                $('.js-additional-fields-wrapper-link-<?php echo $uniqueID; ?>').show();
                            }
                        }
                        $('.js-link-type-link-<?php echo $uniqueID; ?>').on('change', function() {
                            var linkType = $('.js-link-type-link-<?php echo $uniqueID; ?>').val();
                            var toggleAdditionalFieldsValue = parseInt($('.js-toggle-additional-fields-value-link-<?php echo $uniqueID; ?>').val());
                            $('.js-link-wrapper-link-<?php echo $uniqueID; ?>').hide();
                            $('.js-additional-fields-wrapper-link-<?php echo $uniqueID; ?>').hide();
                            $('.js-toggle-additional-fields-link-<?php echo $uniqueID; ?>').hide();
                            if (linkType!=0) {
                                $('.js-link-wrapper-'+linkType+'-link-<?php echo $uniqueID; ?>').show();
                                $('.js-toggle-additional-fields-link-<?php echo $uniqueID; ?>').show();
                                if (toggleAdditionalFieldsValue==1) {
                                    $('.js-additional-fields-wrapper-link-<?php echo $uniqueID; ?>').show();
                                }
                            }
                        });
                        $('.js-toggle-additional-fields-link-<?php echo $uniqueID; ?>').on('click', function() {
                            var toggleAdditionalFieldsValue = parseInt($('.js-toggle-additional-fields-value-link-<?php echo $uniqueID; ?>').val());
                            var showText = $('.js-toggle-additional-fields-link-<?php echo $uniqueID; ?>').attr('data-show-text');
                            var hideText = $('.js-toggle-additional-fields-link-<?php echo $uniqueID; ?>').attr('data-hide-text');
                            if (toggleAdditionalFieldsValue) {
                                $('.js-additional-fields-wrapper-link-<?php echo $uniqueID; ?>').hide();
                                $('.js-toggle-additional-fields-link-<?php echo $uniqueID; ?>').removeClass('toggle-additional-fields-active');
                                $('.js-toggle-additional-fields-value-link-<?php echo $uniqueID; ?>').val(0);
                                $('.js-toggle-additional-fields-text-link-<?php echo $uniqueID; ?>').text(showText);
                            } else {
                                $('.js-additional-fields-wrapper-link-<?php echo $uniqueID; ?>').show();
                                $('.js-toggle-additional-fields-link-<?php echo $uniqueID; ?>').addClass('toggle-additional-fields-active');
                                $('.js-toggle-additional-fields-value-link-<?php echo $uniqueID; ?>').val(1);
                                $('.js-toggle-additional-fields-text-link-<?php echo $uniqueID; ?>').text(hideText);
                            }
                        });
                    });
                </script>

            </div><?php // .js-link-wrapper ?>

        </div>

    </div>

</div>
