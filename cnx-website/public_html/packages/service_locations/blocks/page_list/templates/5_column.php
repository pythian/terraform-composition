<?php
defined('C5_EXECUTE') or die("Access Denied.");
$th = Loader::helper('text');
//$ih = Loader::helper('image'); //<--uncomment this line if displaying image attributes (see below)
//Note that $nh (navigation helper) is already loaded for us by the controller (for legacy reasons)
?>
<?php
$i= 1;
$total_page = count($pages);
$pages_in_single_div = ceil($total_page / 5);
?>
<div class="row">
	<div class="col-md-15 locations">
		<ul>
  <?php foreach ($pages as $key => $page):

		// Prepare data for each page being listed...


		//Other useful page data...

		//$date = $page->getCollectionDatePublic(DATE_APP_GENERIC_MDY_FULL);

		//$last_edited_by = $page->getVersionObject()->getVersionAuthorUserName();

		//$original_author = Page::getByID($page->getCollectionID(), 1)->getVersionObject()->getVersionAuthorUserName();

		/* CUSTOM ATTRIBUTE EXAMPLES:
		 * $example_value = $page->getAttribute('example_attribute_handle');
		 *
		 * HOW TO USE IMAGE ATTRIBUTES:
		 * 1) Uncomment the "$ih = Loader::helper('image');" line up top.
		 * 2) Put in some code here like the following 2 lines:
		 *      $img = $page->getAttribute('example_image_attribute_handle');
		 *      $thumb = $ih->getThumbnail($img, 64, 9999, false);
		 *    (Replace "64" with max width, "9999" with max height. The "9999" effectively means "no maximum size" for that particular dimension.)
		 *    (Change the last argument from false to true if you want thumbnails cropped.)
		 * 3) Output the image tag below like this:
		 *		<img src="<?php echo $thumb->src ?>" width="<?php echo $thumb->width ?>" height="<?php echo $thumb->height ?>" alt="" />
		 *
		 * ~OR~ IF YOU DO NOT WANT IMAGES TO BE RESIZED:
		 * 1) Put in some code here like the following 2 lines:
		 * 	    $img_src = $img->getRelativePath();
		 * 	    list($img_width, $img_height) = getimagesize($img->getPath());
		 * 2) Output the image tag below like this:
		 * 	    <img src="<?php echo $img_src ?>" width="<?php echo $img_width ?>" height="<?php echo $img_height ?>" alt="" />
		 */

		/* End data preparation. */

		/* The HTML from here through "endforeach" is repeated for every item in the list... */ ?>
  <?php $title = $th->entities($page->getCollectionName());
		$url = $nh->getLinkToCollection($page);
		$target = ($page->getCollectionPointerExternalLink() != "" && $page->openCollectionPointerExternalLinkInNewWindow()) ? "_blank" : $page->getAttribute("nav_target");
		$target = empty($target) ? "_self" : $target;
		$description = $page->getCollectionDescription();
		$description = $controller->truncateSummaries ? $th->wordSafeShortText($description, $controller->truncateChars) : $description;
		$description = $th->entities($description);

		?>
   <li><a href="<?php echo $url ?>" title="<?php echo $title ?>"><?php echo $title ?></a> </li>
  <?php
	  if(($i % $pages_in_single_div == 0) && ($i > 0)){
			echo '</ul></div><div class="col-md-15 locations"><ul>';
		}
		$i++;
		endforeach; ?>
		</ul>
  </div>
</div>
<!-- end .ccm-page-list -->
<style>
.col-xs-15,
.col-sm-15,
.col-md-15,
.col-lg-15 {
    position: relative;
    min-height: 1px;
    padding-right: 10px;
    padding-left: 10px;
}
.col-xs-15 {
    width: 20%;
    float: left;
}
.col-md-15.locations ul li:before {
    display: none;
}
@media (min-width: 220px) and (max-width:767px) {
	.col-md-15.locations ul {
    padding:0;
}
}
@media (min-width: 768px) {
.col-sm-15 {
        width: 20%;
        float: left;
    }
}
@media (min-width: 992px) {
    .col-md-15 {
        width: 20%;
        float: left;
    }
}
@media (min-width: 1200px) {
    .col-lg-15 {
        width: 20%;
        float: left;
    }
}
</style>
