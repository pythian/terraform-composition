<?php      defined('C5_EXECUTE') or die("Access Denied.");

if( strlen($title)>0 ){
	$placeholder = $title;
} else {
	$placeholder =  t('Enter your keyword here...');
}
?>

<?php      if (isset($error)) { ?>
	<?php      echo $error?><br/><br/>
<?php      } ?>

<?php      if( strlen($title)>0){ ?><h3><?php      echo $title?></h3><?php      } ?>

<div id="sb-search<?php     echo $bID?>" class="sb-search with_res">

<form action="<?php  echo $view->url( $resultTargetURL )?>" method="get" class="ccm-search-block-form">

	<?php      if(strlen($query)==0){ ?>
	<input name="search_paths[]" type="hidden" value="<?php      echo htmlentities($baseSearchPath, ENT_COMPAT, APP_CHARSET) ?>" />
	<?php      } else if (is_array($_REQUEST['search_paths'])) {
		foreach($_REQUEST['search_paths'] as $search_path){ ?>
			<input name="search_paths[]" type="hidden" value="<?php      echo htmlentities($search_path, ENT_COMPAT, APP_CHARSET) ?>" />
	<?php       }
	} ?>

	<label for="search">Search</label><input name="query" type="text" name="search" value="<?php      echo htmlentities($query, ENT_COMPAT, APP_CHARSET)?>" placeholder="<?php      echo $placeholder?>" class="sb-search-input ccm-search-block-text" autocomplete="off" />

	<?php  if($buttonText) { ?>
	<input name="submit" type="submit" value="<?php      echo $buttonText?>" class="sb-search-submit ccm-search-block-submit" />
	<span class="sb-icon-search"></span>
    <?php  } ?>

</form>

</div>

<?php
$tt = Loader::helper('text');
if ($do_search) {
	if(count($results)==0){ ?>
		<h4 style="margin-top:32px" class="no_result"><?php      echo t('There were no results found. Please try another keyword or phrase.')?></h4>
	<?php      }else{ ?>
		<div id="searchResults">
		<?php      foreach($results as $r) {
			$currentPageBody = $this->controller->highlightedExtendedMarkup($r->getPageIndexContent(), $query);?>
			<div class="searchResult">

				<h2><a href="<?php  echo $r->getCollectionLink()?>"><?php  echo $r->getCollectionName()?></a></h2>
				<p>
					<?php  if ($r->getCollectionDescription()) { ?>
						<?php   echo $this->controller->highlightedMarkup($tt->shortText($r->getCollectionDescription()),$query)?><br/>
					<?php      } ?>
					<?php      echo $currentPageBody; ?>
					<a href="<?php   echo $r->getCollectionLink(); ?>" class="pageLink"><?php   echo $this->controller->highlightedMarkup($r->getCollectionLink(),$query)?></a>
				</p>
			</div>
		<?php      	}//foreach search result ?>
		</div>

		<?php
		 $pages = $pagination->getCurrentPageResults();

        if ($pagination->getTotalPages() > 1 && $pagination->haveToPaginate()) {
            $showPagination = true;
            echo $pagination->renderDefaultView();
        }
	} //results found
}
?>
<script type="text/javascript">
	$(document).ready(function(){
		new UISearch( document.getElementById( 'sb-search<?php     echo $bID?>' ) );
	});
</script>
