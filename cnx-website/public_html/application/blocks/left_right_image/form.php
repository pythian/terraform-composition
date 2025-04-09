<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>

<div id="form-container-<?php echo $uniqueID; ?>">

    <div class="tab-content mt-4">

        <div class="js-tab-pane">

            <div class="mb-3 entries-actions">
                <button type="button" class="btn btn-primary entries-action-button js-add-entry" data-action="prepend"><?php echo t('Add at the top'); ?></button>
                <button type="button" class="btn btn-primary entries-action-button js-add-entry" data-action="append"><?php echo t('Add at the bottom'); ?></button>
                <button type="button" class="btn btn-primary entries-action-button js-copy-last-entry" data-action="append"><?php echo t('Copy last entry'); ?></button>
                <span class="entries-actions-links">
                    <a href="#" class="entries-action-link js-expand-all"><i class="far fa-plus-square"></i> <?php echo t('Expand all'); ?></a>
                    <a href="#" class="entries-action-link js-collapse-all"><i class="far fa-minus-square"></i> <?php echo t('Collapse all'); ?></a>
                </span>
                <a href="#" class="entries-action-link entries-action-link-remove-all js-remove-all" data-confirm-text="<?php echo t('Are you sure?'); ?>"  title="<?php echo t('Remove all'); ?>"><i class="fas fa-times-circle"></i></a>
            </div>

            <div class="entries" id="entries-<?php echo $uniqueID; ?>" data-entries="<?php echo htmlspecialchars(json_encode($entries)); ?>" data-column-names="<?php echo h(json_encode($entryColumnNames)); ?>"></div>

            <div class="mb-3 entries-actions">
                <button type="button" class="btn btn-primary entries-action-button js-add-entry" data-action="prepend"><?php echo t('Add at the top'); ?></button>
                <button type="button" class="btn btn-primary entries-action-button js-add-entry" data-action="append"><?php echo t('Add at the bottom'); ?></button>
                <button type="button" class="btn btn-primary entries-action-button js-copy-last-entry" data-action="append"><?php echo t('Copy last entry'); ?></button>
                <span class="entries-actions-links">
                    <a href="#" class="entries-action-link js-expand-all"><i class="far fa-plus-square"></i> <?php echo t('Expand all'); ?></a>
                    <a href="#" class="entries-action-link js-collapse-all"><i class="far fa-minus-square"></i> <?php echo t('Collapse all'); ?></a>
                </span>
                <a href="#" class="entries-action-link entries-action-link-remove-all js-remove-all" data-confirm-text="<?php echo t('Are you sure?'); ?>"  title="<?php echo t('Remove all'); ?>"><i class="fas fa-times-circle"></i></a>
            </div>

            <div class="mb-3">
                <div class="form-check-inline">
                    <input type="checkbox"
                        name="<?php echo $view->field('disableSmoothScroll'); ?>"
                        class="form-check-input js-disable-smooth-scroll"
                        value="1"
                        id="<?php echo $view->field('disableSmoothScroll'); ?>"
                    />
                    <label for="<?php echo $view->field('disableSmoothScroll'); ?>" class="form-check-label"><?php echo t('Disable smooth scroll'); ?></label>
                </div>
                <div class="form-check-inline">
                    <input type="checkbox"
                        name="<?php echo $view->field('keepAddedEntryCollapsed'); ?>"
                        class="form-check-input js-keep-added-entry-collapsed"
                        value="1"
                        id="<?php echo $view->field('keepAddedEntryCollapsed'); ?>"
                    />
                    <label for="<?php echo $view->field('keepAddedEntryCollapsed'); ?>" class="form-check-label"><?php echo t('Keep added/copied entry collapsed'); ?></label>
                </div>
            </div>

            <script type="text/template" class="js-entry-template">

                <div class="well entry js-entry" data-position="<%=_.escape(position)%>">

                    <div class="entry-header">
                        <button type="button" class="entry-header-action entry-header-duplicate-entry-and-add-at-the-end js-duplicate-entry-and-add-at-the-end" title="<?php echo t('Duplicate entry and add at the end'); ?>"><i class="far fa-clone"></i></button>
                        <button type="button" class="entry-header-action entry-header-duplicate-entry js-duplicate-entry" title="<?php echo t('Duplicate entry'); ?>"><i class="fas fa-clone"></i></button>
                        <div class="entry-header-action entry-header-remove-entry js-remove-entry" data-confirm-text="<?php echo t('Are you sure?'); ?>"  title="<?php echo t('Remove entry'); ?>"><i class="fas fa-times"></i></div>
                        <div class="entry-header-action entry-header-move-entry js-move-entry"><i class="fas fa-arrows-alt"></i></div>
                        <div class="entry-header-action entry-header-toggle-entry js-toggle-entry" data-action="collapse"><i class="far fa-minus-square"></i></div>
                        <div class="entry-header-title">
                            <span class="js-entry-title">
                                #<%=_.escape(position)%>
                            </span>
                        </div>
                    </div>

                    <div class="entry-content js-entry-content" <% if (keepAddedEntryCollapsed) { %>style="display: none;"<% } %>>

                        <div class="mb-4">
                            <label for="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][title]" class="form-label"><?php echo t('Title'); ?></label>
                            <input type="text" id="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][title]" name="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][title]" value="<%=_.escape(title)%>" class="form-control" maxlength="255" />
                        </div>

                        <div class="mb-4 js-custom-editor-height-<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][content]-<?php echo $uniqueID; ?>">
                            <label for="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][content]" class="form-label"><?php echo t('Content'); ?></label>
                            <textarea style="display: none;" class="js-editor-content" name="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][content]" id="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][content]"><%=_.escape(content)%></textarea>
                        </div>

                        <hr/>

                        <div class="mb-4 js-image-wrapper">

                            <div class="row margin-bottom">
                                <div class="col-12">
                                    <label for="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][image]" class="form-label"><?php echo t('Image'); ?></label>
                                </div>
                                <div class="col-12 col-lg-6 margin-bottom-on-mobile">
                                    <div data-concrete-file-input="js-file-selector">
                                        <concrete-file-input :file-id="<%= image ? _.escape(image) : '0' %>"
                                            choose-text="<?php echo t('Choose File'); ?>"
                                            input-name="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][image]"
                                        ></concrete-file-input>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <span class="toggle-additional-image-fields <% if (parseInt(image_show_additional_fields)) { %>toggle-additional-image-fields-active<% } %> btn btn-secondary js-toggle-additional-image-fields"
                                        data-show-text="<?php echo t('Show additional fields'); ?>"
                                        data-hide-text="<?php echo t('Hide additional fields'); ?>"
                                    ><i class="fas fa-caret-right"></i> <span class="js-toggle-additional-image-fields-text"><% if (parseInt(image_show_additional_fields)) { %><?php echo t('Hide additional fields'); ?><% } else { %><?php echo t('Show additional fields'); ?><% } %></span></span>
                                    <input type="hidden" class="js-toggle-additional-image-fields-value" id="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][image_show_additional_fields]" name="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][image_show_additional_fields]" value="<%=_.escape(image_show_additional_fields)%>" maxlength="255" />
                                </div>
                            </div>

                            <div class="js-additional-image-fields-wrapper" <% if (!parseInt(image_show_additional_fields)) { %>style="display: none;"<% } %>>

                                <div class="mb-4">
                                    <label for="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][image_alt]" class="form-label"><?php echo t('Alt text'); ?></label>
                                    <input type="text" id="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][image_alt]" name="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][image_alt]" value="<%=_.escape(image_alt)%>" class="form-control" maxlength="255" />
                                </div>

                            </div>

                        </div><?php // .js-image-wrapper ?>

                    </div>

                </div>

            </script>

            <script type="text/template" class="js-template-no-entries">

                <div class="alert alert-info js-alert"><?php echo t('No entries found.'); ?></div>

            </script>

            <script>

                var CCM_EDITOR_SECURITY_TOKEN = '<?php echo $app->make('helper/validation/token')->generate('editor'); ?>';

                var activateEditor = <?php echo $app->make('editor')->outputStandardEditorInitJSFunction(); ?>;

                $(function () {

                    Concrete.event.publish('open.block.left-right-image', {
                        'uniqueID' : '<?php echo $uniqueID; ?>'
                    });

                });

            </script>

        </div>

    </div>

</div>
