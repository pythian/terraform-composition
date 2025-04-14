<?php
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php'); ?>

<section class="inside-banner">
					 <?php   $a = new Area('Banner');
				 	   		 $a->display($c); ?>
				 </section>
         <section class="sec-cont-wrap">
          <div class="container">
            <div class="cont-top">
               <?php $a = new Area('Inside Top Content'); $a->display($c); ?>
            </div>
          </div>
        </section>
                  <section class="inside_content">
                    <div class="container">
                          <div id="job_listing">

													<?php

														$ch = curl_init();

														curl_setopt($ch, CURLOPT_URL,"https://www.southviewcommunities.com/joblist");
														//curl_setopt($ch, CURLOPT_URL,"https://www.southviewcommunities.com/getJobs");
														curl_setopt($ch, CURLOPT_POST, 1);
														curl_setopt($ch, CURLOPT_POSTFIELDS,
														            "parentID=668");
														curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

														$server_output = curl_exec ($ch);
														print_r($server_output);

														curl_close ($ch);
													?>

														<!--script>
														$(document).ready(function(){
															$.ajax({
														type: "POST",
														url: "https://www.southviewcommunities.com/careers?ID=246&location=Arbor Lakes Senior Living",
														data: { select_location: "<?php echo $c->getAttribute('parent_page_id'); ?>"},

														success: function(resultData){
														$("#job_listing").html(resultData);
														}
														});
													});

												</script-->



                          </div>
                        </div>
                </section>
<section class="gallery-highlights">
   <?php $a = new Area('Inside Main');
   $a->enableGridContainer();
   $a->display($c); ?>
 </section>


<?php  $this->inc('elements/footer.php'); ?>
<style type="text/css">
.img_gal {
    margin-top: 18px;
}
</style>
