<?php

// switch display type here
if ($akTextareaDisplayMode == 'text' || $akTextareaDisplayMode == '') { ?>

    <?php
    echo $form->textarea(
        $view->controller->field('value'),
        h($value),
        array('rows' => 5)
    );
    ?>

<?php } else {
    $requestValue = $form->getRequestValue($view->controller->field('value'));
    if (is_string($requestValue)) {
        $value = $requestValue;
    }

    /**
     * @TODO - add another option in the controller for creating a sanitized, simple editor, and make it use
     * `outputSimpleEditor`. Right now we can't just wholesale switch over to the simple editor because people
     * are complaining. This was originally changed for https://hackerone.com/reports/616770 but now must be changed
     * back to the standard editor, until we have an option in the textarea attribute type controller that lets people
     * opt in to the sanitized version
     */
    /*
    echo Core::make('editor')->outputSimpleEditor(
        $view->controller->field('value'),
        h($value)
    );*/
   /*  echo Core::make('editor')->outputStandardEditor(
        $view->controller->field('value'),
        h($value)
    ); */
    $sc= Core::make('editor')->outputStandardEditor(
        $view->controller->field('value'),
        h($value)
    );
	$custom_plugin_content='"plugins":"basicstyles,dialogadvtab,divarea,image,tab,table,tableresize,tableselection,tabletools,toolbar,undo,wysiwygarea,concreteinline,format,normalizeonchange,sourcearea,concretelink,concretestyles,justify,list,liststyle,autogrow,resize,concretefilemanager,concreteuploadimage",';
	preg_match('/\"plugins\"(.*?)\",/', $sc, $output_array);
	echo str_replace($output_array[0],$custom_plugin_content,$sc);

}
