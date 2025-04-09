<?php    defined('C5_EXECUTE') or die(_("Access Denied."));



$form = Loader::helper('form');

$dth = Loader::helper('form/date_time');

$text = Loader::helper('text');



$al = Loader::helper('concrete/asset_library');

if(isset($t)){

	$id=$t->id;

$author=$t->author;

$date_added=($t->date_added) ? $t->date_added : date('m/d/Y');

$content=$t->content;

$extra=$t->extra;

$rating=$t->rating;

$approved=$t->approved;

$video=$t->video;

$image=$t->image;

$tcategories=(array)$t->getCategories();

}else{

	$id='';

	$author='';

$date_added=date('m/d/Y');

$content='';

$extra='';

$rating='';

$approved='';

$video='';

$image='';

$tcategories=array();

}

?>





    <?php     if (($this->controller->getTask() == 'add') || ($this->controller->getTask() == 'edit')){ ?>



		<style>

		#testimonial-form .help-block{font-size: 11px; color: #777; padding-top: 3px;}

		</style>



        <form method="post" action="<?php    echo $this->action('add'); ?>" id="testimonial-form">



			<div class="form-group">

				<?php    echo $form->label('author', t('Author')); ?>

				<?php    echo $form->text('author', $author); ?>

			</div>

			<div class="form-group">

				<?php    echo $form->label('date_added', t('Date Added')); ?>

				<?php    echo $dth->date('date_added', $date_added); ?>

			</div>

			<div class="form-group">

				<?php    echo $form->label('content', t('Content')); ?>

				<?php    echo $form->textarea('content', $content, array('rows' => '5')); ?>

			</div>

			<div class="form-group">

				<?php    echo $form->label('extra', t('Extra')); ?>

				<?php    echo $form->textarea('extra', $extra)?><div class="help-block"><?php    echo t('Anything entered here will appear below the author (eg. Title, Company, etc.). Anything beginning with http:// will automatically be linked.')?></div>

			</div>

            <div class="form-group">

				<?php    echo $form->label('rating', t('Rating')); ?>

				<?php    echo $form->select('rating', array('1' => t('1'), '2' => t('2'), '3' => t('3'), '4' => t('4'), '5' => t('5')), $rating, array('style' => 'width: 125px;'))  ?>

                <div class="help-block"><?php  echo t('Rating')?></div>

			</div>

			<div class="form-group">

				<?php   echo $form->label('approved', t('Approved')); ?>

				<div class="radio">

				<label class="radio-inline"><?php   echo $form->radio('approved', 1, $approved); ?> <?php   echo t('Yes') ?></label>

				<label class="radio-inline"><?php   echo $form->radio('approved', 0, $approved); ?> <?php   echo t('No') ?></label>

				</div>

			</div>



      <div class="form-group">

				<?php    echo $form->label('Video', t('Video')); ?>

				<?php

				if($video){

					$videoFile = File::getByID($video);

				}else{

					$videoFile='';

				}

				echo $al->file('chooseVideo', 'video', t('Select Video'), $videoFile); ?> </td>

			</div>



			<div class="form-group">

				<?php    echo $form->label('image', t('Image')); ?>

				<?php

				if($image){

					$file = File::getByID($image);

				}else{

					$file='';

				}

				echo $al->file('chooseImage', 'image', t('Select Image'), $file); ?> </td>

			</div>

			<div class="form-group">

				<hr>

				<?php   echo $form->label('categories', t('Add to Categories')); ?>

				<?php

					if(is_array($categories) && count($categories)){

						foreach($categories as $category){

							if(isset($t)){ $selected = in_array($category, $tcategories); }else { $selected =''; }

							echo '<div class="checkbox"><label>'.$form->checkbox('category[]', $category, $selected),' ',$category,'</label></div>';

						}

					} else {

						echo '<p>'.t('No categories have been added yet.').'</p>';

					} ?>

			</div>

		</table>



		<div class="ccm-dashboard-form-actions-wrapper">

		<div class="ccm-dashboard-form-actions">

                <?php

					if($id){

						echo $form->hidden('id', $id);

					}

                    echo '<a href="'.$this->url('/dashboard/studio_testimonials_pro').'" class="btn btn-secondary float-start">Cancel</a>';

					echo $form->submit('submit', t('Save Testimonial'), [], 'btn-primary float-end');

                ?>

		</div>

		</div>

        </form>



    <?php    } else {



    $testimonials = $list->getPage();



    ?>



    <div class="ccm-dashboard-header-buttons btn-group">

		<a class="btn btn-primary" href="<?php   echo $this->action('add')?>"><?php   echo t('Add New Testimonial')?></a>

	</div>



	<style>

		#catList{list-style: none; margin: 0; padding: 0; }

			#catList li{width: 200px; background: #ededed; border: 1px solid #ddd; margin-bottom: 5px; padding: 5px;}

			#catList li a{float: right; }

	</style>





    <div data-search-element="wrapper">

		<form id="searchForm" method="get" class="form-inline ccm-search-fields">

			<div class="ccm-search-fields-row">

                <div class="form-group">

                    <?php   echo $form->label('keywords', t('Search'))?>

                    <div class="ccm-search-field-content">

                        <div class="ccm-search-main-lookup-field">

                            <i class="fa fa-search"></i>

                            <?php   echo $form->search('query', array('placeholder' => t('Keywords')))?>

                            <button type="submit" class="btn btn-primary" tabindex="-1"><?php   echo t('Search')?></button>

                        </div>

                    </div>

                </div>

            </div>



			<?php    if($categories): ?>

            <div class="ccm-search-fields-row">

                <div class="form-group">

                    <?php   echo $form->label('category', t('Category'))?>

                    <div class="ccm-search-field-content">

                    	<select id="filterCategory" name="category" class="form-control" style="width: 360px;;">

							<option value=""><?php    echo t('** Select Category'); ?></option>

							<?php    foreach((array)$categories as $cat): ?>

								<option value="<?php    echo $cat; ?>"<?php    if(isset($_GET['category']) && $_GET['category'] == $cat){echo ' selected="selected"';} ?>><?php    echo $cat; ?></option>

							<?php    endforeach; ?>

						</select>

                    </div>

                </div>

            </div>

			<?php    endif; ?>

            <div class="ccm-search-fields-submit">

            	<div class="btn-group pull-right">

                <button type="submit" class="btn btn-primary"><?php   echo t('Search')?></button>

				<a class="btn btn-secondary" href="<?php    echo $this->action(''); ?>"><?php    echo t('Reset Search'); ?></a>

				</div>

            </div>

		</form>

	</div>



		<table class="table ccm-results-list">

            <tr>

                <th class=" <?php    echo $list->getSearchResultsClass('author')?>">

				<a href="<?php    echo $list->getSortByUrl('author','asc'); ?>"><?php    echo t('Author'); ?></a></th>

                <th><?php    echo t('Testimonial'); ?></th>

                <th><?php    echo t('Category'); ?></th>

                <th class=" <?php    echo $list->getSearchResultsClass('date_added')?>">

				<a href="<?php    echo $list->getSortByUrl('date_added','asc'); ?>"><?php    echo t('Date'); ?></th>

                <th class=" <?php    echo $list->getSearchResultsClass('approved')?>">

				<a href="<?php    echo $list->getSortByUrl('approved','asc'); ?>"><?php    echo t('Approved'); ?></th>

                <th scope="col">&nbsp;</th>

            </tr>

            <?php    foreach($testimonials as $testimonial){?>

            <tr>

                <td><?php    echo $testimonial->author; ?></td>

                <td><?php    echo $text->shorten($testimonial->content, 35); ?></td>

                <td><?php    echo implode(', ', (array)$testimonial->getCategories()); ?></td>

                <td><?php    echo $testimonial->date_added; ?></td>

                <td><?php    echo (($testimonial->approved) ?t('Yes'):t('No')); ?></td>

                <td>

                <a class="btn btn-primary btn-xs" href="<?php    echo $this->action('edit', $testimonial->id); ?>"><?php    echo t('Edit'); ?></a>

                <a class="btn btn-danger btn-xs" href="<?php    echo $this->action('delete', $testimonial->id); ?>" onclick="return confirm('<?php    echo t('Are you sure you want to delete this testimonial?'); ?>');"><?php    echo t('Delete'); ?></a>

                <a class="btn btn-secondary btn-xs" href="<?php    echo $this->action('approve', $testimonial->id); ?>"><?php    echo (($testimonial->approved) ?t('Unapprove'):t('Approve')); ?></a>

                </td>

            </tr>

            <?php    } ?>

        </table>

		<hr/>

		<?php    echo $list->displayPaging(); ?>



		<h3><?php    echo t('Manage Categories'); ?></h3>

		<ul id="catList">

		<?php

		if(is_array($categories)){

		foreach($categories as $category){?>

			<li><?php    echo $category; ?> <a href="<?php    echo $this->action('delete_category', $category) ?>" title="<?php   echo t('Delete category.'); ?>" onclick="return confirm('<?php    echo t('Are you sure you want to delete this category?')?>')"> <i class="fa fa-trash"></i></a></li>

		<?php    }

		}?>

		</ul>

		<br>

		<form method="post" action="<?php    echo $this->action('new_category'); ?>"  class="form-inline" id="new-category">

		<div class="form-group">

		<?php    echo $form->text('new_category', '', array('style'=>'width: 200px')); ?>

		<button type="submit" class="btn btn-primary"><?php   echo t('Add Category') ?></button>

		</div>



		</form>



		<script>

		$(function(){

			$("#filterCategory").change(function(){

				$("#searchForm").submit();

			})

		})

		</script>



    <?php    } ?>

<style>

	.form-inline .form-control {

    display: inline-block;

    width: auto;

    vertical-align: middle;

}

</style>
