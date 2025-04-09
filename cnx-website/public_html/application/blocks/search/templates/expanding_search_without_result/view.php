<?php      defined('C5_EXECUTE') or die("Access Denied.");
if (!isset($query) || !is_string($query)) {
    $query = '';
}

if( strlen($title)>0 ){
	$placeholder = $title;
} else {
	$placeholder =  t('Search...');
}
?>

<?php      if (isset($error)) { ?>
	<?php      echo $error?><br/><br/>
<?php      } ?>



<div class="sep_bar"></div>

<div id="sb-search<?php     echo $bID?>" class="sb-search without_res">

<form action="<?php echo $this->url( $resultTargetURL )?>" method="get" class="ccm-search-block-form" id="search_site">

	<?php      if(strlen($query)==0){ ?>
	<input name="search_paths[]" type="hidden" value="<?php      echo htmlentities($baseSearchPath, ENT_COMPAT, APP_CHARSET) ?>" />
	<?php      } else if (is_array($_REQUEST['search_paths'])) {
		foreach($_REQUEST['search_paths'] as $search_path){ ?>
			<input name="search_paths[]" type="hidden" value="<?php      echo htmlentities($search_path, ENT_COMPAT, APP_CHARSET) ?>" />
	<?php       }
	} ?>


	<input name="query" id="search" type="text" value="<?php echo htmlentities($query, ENT_COMPAT, APP_CHARSET)?>" placeholder="<?php echo $placeholder?>" class="sb-search-input ccm-search-block-text form-control2" autocomplete="off" />
	<label for="search" class="form-control-placeholder2">Search</label>
	<button name="submit" type="submit" value="<?php echo $buttonText?>" class="sb-search-submit ccm-search-block-submit" ></button>
	<div class="sb-icon-search"> </div>

</form>

</div>

<script type="text/javascript">

	$(document).ready(function(){
		new UISearch( document.getElementById( 'sb-search<?php echo $bID?>' ) );
	});

    if ($(".sb-search-input").hasClass("keyboard-focus")) {
        $(".sb-search").addClass("show");
        $(".sb-search-input").addClass("keyboard-focus");
    };

    if (!$(".sb-search-submit").hasClass("keyboard-focus")) {
        $(".sb-search").removeClass("show");
		$(".sb-search-submit").addClass("kb-focus");
    };

    /*if ($(".sb-search-input").hasClass("keyboard-focus")) {
        $(".sb-icon-search").addClass("hidden");
    };*/

    if ($(".sb-search-submit").hasClass("keyboard-focus")) {
        $(".sb-search").addClass("show");
        $(".sb-search-input").addClass("keyboard-focus");
    };

    $(".sb-search-input").focus(function(){
        $(".sb-search").addClass("show");
        $(".sb-icon-search").addClass("kb-focus");
        //$(".sb-icon-search-text").addClass("hidden");
        $(".sb-search").addClass("over");
    });

    $(".sb-search-submit").focus(function(){
        $(".sb-search-input").addClass("keyboard-focus");
        $(".sb-search-input").addClass("form-control2");
        $(".sb-search-input").addClass("focus");
    });

    $(".sb-search-submit").blur(function(){
         $(".sb-search").removeClass("show");
         $(".sb-icon-search").removeClass("kb-focus");
    });

    $(".sb-icon-search").click(function(){
        $(".sb-search").addClass("show");
        $(".sb-icon-search").addClass("both");
    });

    $(".sb-search-submit").blur(function(){
         $(".sb-search-input").addClass("hide");
         $(".sb-icon-search").removeClass("kb-focus");
    });

</script>
