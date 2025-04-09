<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>

<div id="form-container-<?php echo $uniqueID; ?>">

    <?php
    echo $app->make('helper/concrete/ui')->tabs([
        ['basic-information-tab-'.$uniqueID, t('Basic information'), true],
        ['entries-tab-'.$uniqueID, t('Entries')],
    ]);
    ?>

    <div class="tab-content mt-4">

        <div class="js-tab-pane tab-pane show active" id="basic-information-tab-<?php echo $uniqueID; ?>">

            <div class="mb-4">
                <?php echo $form->label($view->field('title'), t('Title')); ?>
                <?php echo $form->text($view->field('title'), $title, ['maxlength'=>'255']); ?>
            </div>

        </div>

        <div class="js-tab-pane tab-pane" id="entries-tab-<?php echo $uniqueID; ?>">

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
                            <label for="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][select_page]" class="form-label"><?php echo t('Select Page'); ?></label>
                            <div data-concrete-page-input="js-page-selector">
                                <concrete-page-input :page-id="<%= select_page ? _.escape(select_page) : false %>"
                                    input-name="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][select_page]"
                                    choose-text="<?php echo t('Choose Page'); ?>"
                                    :include-system-pages="false"
                                    :ask-include-system-pages="false"
                                ></concrete-page-input>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][select_page_ending]" class="form-label"><?php echo t('Custom string at the end of URL'); ?></label>
                            <input type="text" id="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][select_page_ending]" name="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][select_page_ending]" value="<%=_.escape(select_page_ending)%>" class="form-control" maxlength="255" />
                            <div class="form-text"><?php echo t('(e.g. #contact-form or ?ccm_paging_p=2)'); ?></div>
                        </div>

                        <div class="mb-4">
                            <label for="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][select_page_text]" class="form-label"><?php echo t('Text'); ?></label>
                            <textarea id="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][select_page_text]" name="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][select_page_text]" class="form-control" maxlength="255"><%=_.escape(select_page_text)%></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][select_page_title]" class="form-label"><?php echo t('Title'); ?></label>
                            <input type="text" id="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][select_page_title]" name="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][select_page_title]" value="<%=_.escape(select_page_title)%>" class="form-control" maxlength="255" />
                        </div>

                        <div class="mb-4">
                            <label for="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][select_page_new_window]" class="form-label"><?php echo t('Open in new window'); ?></label>
                            <select id="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][select_page_new_window]" name="<?php echo $view->field('entry'); ?>[<%=_.escape(position)%>][select_page_new_window]" class="form-select">
                                <option value="0" <% if (!select_page_new_window) { %>selected="selected"<% } %>><?php echo t('No'); ?></option>
                                <option value="1" <% if (select_page_new_window==1) { %>selected="selected"<% } %>><?php echo t('Yes'); ?></option>
                            </select>
                        </div>

                    </div>

                </div>

            </script>

            <script type="text/template" class="js-template-no-entries">

                <div class="alert alert-info js-alert"><?php echo t('No entries found.'); ?></div>

            </script>

            <script>

                $(function () {

                    Concrete.event.publish('open.block.manual-page-filter', {
                        'uniqueID' : '<?php echo $uniqueID; ?>'
                    });

                });

            </script>

        </div>

    </div>

</div>
