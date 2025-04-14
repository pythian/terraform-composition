<?php
defined('C5_EXECUTE') or die("Access Denied.");
$th = Loader::helper('text');
//$ih = Loader::helper('image'); //<--uncomment this line if displaying image attributes (see below)
//Note that $nh (navigation helper) is already loaded for us by the controller (for legacy reasons)
?>
<?php
$i= 1;
$total_page = count($pages);
$pages_in_single_div = ceil($total_page / 4);
$pages_in_single_div_sm = ceil($total_page / 3);
?>
<div class="row">
	<div class="col-md-3 hidden-sm locations">
		<ul>
  <?php
  $data=array();
    $cities=array();
    $states=array();
   foreach ($pages as $key => $page):
$url = $nh->getLinkToCollection($page);
	$title=$page->getCollectionName();
	$details=(explode(",",$title));
array_push($data,array('city'=>trim($details[0]),'state'=>trim($details[1]),'content'=>'<a href="'.$url.'">'.$title.'</a>'
));

 $cities[]=trim($details[0]);
 $states[]=trim($details[1]);
endforeach;
array_multisort($states, SORT_ASC, $cities, SORT_ASC, $data);
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

        <?php foreach($data as $sarray){ ?>

			  <li><?php echo $sarray['content'] ?></li>

  <?php
	  if(($i % $pages_in_single_div == 0) && ($i > 0)){
			echo '</ul></div><div class="col-md-3 hidden-sm locations"><ul>';
		}
		$i++;}
		?>
		</ul>
  </div>
  <div class="col-sm-4 visible-sm locations">
		<ul>
  <?php
  $data=array();
    $cities=array();
    $states=array();
   foreach ($pages as $key => $page):
$url = $nh->getLinkToCollection($page);
	$title=$page->getCollectionName();
	$details=(explode(",",$title));
array_push($data,array('city'=>trim($details[0]),'state'=>trim($details[1]),'content'=>'<a href="'.$url.'">'.$title.'</a>'
));

 $cities[]=trim($details[0]);
 $states[]=trim($details[1]);
endforeach;
array_multisort($states, SORT_ASC, $cities, SORT_ASC, $data);
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

        <?php foreach($data as $sarray){ ?>

			  <li><?php echo $sarray['content'] ?></li>

  <?php
	  if(($i % $pages_in_single_div_sm == 0) && ($i > 0)){
			echo '</ul></div><div class="visible-sm col-sm-4 locations"><ul>';
		}
		$i++;}
		?>
		</ul>
  </div>
</div>
<!-- end .ccm-page-list -->
