<?php
namespace Concrete\Package\StudioTestimonialsPro\Src;
use \Concrete\Core\Legacy\Model;
use Loader;

class Testimonial extends Model
{

	public $_table = 'StudioTestimonialsPro';
	public $id;
	public $date_added;
	public $content;
	public $extra;
	public $rating;
	public $approved;
	public $video;
	public $image;


	public function getID(){
		return $this->id;
	}

	public static function add($data){
		$db = Loader::db();
		$dth = Loader::helper('form/date_time');
		$text = Loader::helper('text');

		$t = new Testimonial();
		// update existing
		if(isset($data['id']) && $data['id']){
			$t->load('id=?', array($data['id']));
		}

		$t->author = $text->sanitize($data['author'], 250);
		if(isset($data['date_added'])){ $t->date_added = date('Y-m-d', strtotime($data['date_added'])); }
		else { $t->date_added = date('Y-m-d'); }
		$t->content = $text->sanitize($data['content']);
		$t->extra = $text->sanitize($data['extra']);
		$t->rating = isset($data['rating'])?intval($data['rating']):0;
		$t->approved = isset($data['approved'])?intval($data['approved']):0;
		$t->image = isset($data['image'])?intval($data['image']):0;
		$t->video = isset($data['video'])?intval($data['video']):0;

		$t->Save();

		// get new testimonial id
		$id = ($t->id) ? $t->id : $db->lastInsertId();

		// update categories
		$db->Execute('delete from StudioTestimonialsCategories where testimonial_id = ?', $id);
		if(isset($data['category']) && is_array($data['category']) && sizeof($data['category'])>0){
		foreach((array)$data['category'] as $category){
			$db->Execute('insert into StudioTestimonialsCategories (testimonial_id, category) values (?,?)', array($id, $category));
		}
	}

		return $t;
	}

	public static function getByID($id){
		$t = new Testimonial();

		$t->load('id=?', $id);

		return $t;
	}

    public function delete(){
        parent::delete();
    }

	public static function approve($id){
		$t = new Testimonial();
		$t->load('id=?', $id);
		if($t->approved){
			$t->approved = '0';
		} else {
			$t->approved = '1';
		}
		$t->save();
	}

	public function getCategories(){
		$db = Loader::db();
		$res = $db->getCol('select category from StudioTestimonialsCategories where testimonial_id=?', array($this->id));

		return $res;
	}

	public function select_testimonials(){

		$form = Loader::helper('form');
		$text = Loader::helper('text');

		$list = new TestimonialList();
		$list->filter('approved', 1);
		$testimonials = $list->get();

		?>
		<script type="text/javascript">
		chooseSelected = function(){
			var choices = new Array();
			$('#choose-testimonials input:checked').each(function(index, ele){
				choices[index] = $(this).val();
			});
			$("#testimonial_ids").val(choices.toString());
			jQuery.fn.dialog.closeTop();
		}
		$(function(){
			current = $("#testimonial_ids").val();
			if(current){
				current_array = current.split(',');
				for(var i in current_array)
				{
					$('input[value='+current_array[i]+']').attr('checked','checked');
				}
			}
		});
		</script>
		<div id="choose-testimonials" class="ccm-ui">
		<table class="table table-striped table-condensed">
			<tr>
				<th scope="col">&nbsp;</th>
				<th scope="col"><?php    echo t('Author'); ?></th>
				<th scope="col"><?php    echo t('Testimonial'); ?></th>
			</tr>
		<?php    foreach($testimonials as $testimonial){ ?>
			<tr>
				<td><input type="checkbox" name="selected-testimonials" value="<?php    echo $testimonial->id; ?>"></td>
				<td><?php    echo $testimonial->author; ?></td>
				<td><?php    echo $text->shorten($testimonial->content, 50); ?></td>
			</tr>
		<?php    } ?>
		</table>
		<br/>
		<div class="ccm-pane-footer">
		<button type="button" class="btn btn-default pull-left" onclick="jQuery.fn.dialog.closeTop()"><?php   echo t('Cancel') ?></button>
		<button type="button" class="btn btn-primary pull-right" onclick="chooseSelected()"><?php   echo t('Choose Selected') ?></button>
		</div>
		</div>

		<?php
		die;
	}
	public function submit_testimonial(){
		if($_POST){
			$block = \Block::getByID($_POST['bID']);
			$cnt = $block->getController();

			$res = Testimonial::add($_POST);

			// send email
			if($cnt->notify_on_submission && $cnt->recipient_email){
				if( strlen(\Config::get('concrete.email.default.address'))>1 && strstr(\Config::get('concrete.email.default.address'),'@') ){
					$formFormEmailAddress = \Config::get('concrete.email.default.address');
				}else{
					$adminUserInfo=\UserInfo::getByID(USER_SUPER_ID);
					$formFormEmailAddress = $adminUserInfo->getUserEmail();
				}

				try{
					$mh = Loader::helper('mail');
					$mh->to( $cnt->recipient_email );
					$mh->from( $formFormEmailAddress );
					$mh->load('testimonial_form_submission', 'studio_testimonials_pro');
					$mh->setSubject(t('Testimonial Submission'));
					$mh->sendMail();
				} catch(\Exception $e){

				}
			}

			// reset form
			$_POST = array();

			exit;
		}
	}

}
