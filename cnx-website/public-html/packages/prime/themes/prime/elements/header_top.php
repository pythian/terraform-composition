<?php defined('C5_EXECUTE') or die("Access Denied.");
$themePath = $view->getThemePath();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <?php
     View::element('header_required', [
         'pageTitle' => isset($pageTitle) ? $pageTitle : '',
         'pageDescription' => isset($pageDescription) ? $pageDescription : '',
         'pageMetaKeywords' => isset($pageMetaKeywords) ? $pageMetaKeywords : ''
     ]);

    ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <link href="<?php echo $view->getThemePath()?>/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo $view->getThemePath()?>/slick/slick.css" rel="stylesheet">
    <link href="<?php echo $view->getThemePath()?>/css/meanmenu.css" rel="stylesheet">
    <link href="<?php echo $view->getThemePath()?>/css/mobilemenubutton.css" rel="stylesheet">
    <!-- <link href="// echo $view->getThemePath()?>/css/animate.css" rel="stylesheet"> -->
<!--
    <link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;500;600;700&display=swap" rel="stylesheet">
-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="<?php echo $view->getThemePath()?>/slick/slick-theme.css" rel="stylesheet">
    <?//php if($c->getCollectionID() == '1'){ ?>
    <link href="<?php echo $view->getThemePath()?>/css/lightbox.css" rel="stylesheet">
  <?//php } ?>
      <?php echo $html->css($view->getStylesheet('styles.less'));?>
      <script>
      jQuery(document).ready(function(){
        var Name = "not-known";
        if (navigator.appVersion.indexOf("Win") != -1){
                    Name = "windows-pc";
        }else if(navigator.appVersion.indexOf("Mac") != -1){
            Name = "MacOS";
        }else if(navigator.appVersion.indexOf("X11") != -1){
            Name ="UNIX";
        }else if (navigator.appVersion.indexOf("Linux") != -1){
            Name = "Linux";
        }
        jQuery(".wrapper").addClass(Name);
      })
      </script>
</head>

<body>

  <div class="<?php echo $c->getPageWrapperClass();?> wrapper">
