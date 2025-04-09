<?php defined('C5_EXECUTE') or die("Access Denied.");
?>
<?php $this->inc('elements/header.php');?>

<section class="home-banner">
<?php $a = new Area('Home Slider'); $a->display(); ?>
<?php $a = new Area('Banner Highlight'); $a->display(); ?>
<style>#publicComputer{display:none}</style>
<!-- <div class="container-fluid" style="display:none;">
<div class="row"><div class="float-end slideshow_login col-xs-3">
    <div class="login_holder">
        <h2>Account Sign In</h2>
        <div id="LoginFormPlaceholderHome">

        </div>
    </div>



</div></div>
<script type="text/javascript">
	$(document).ready(function() {
		$.ajax({
			url: 'https://myaccount.connexusenergy.com/Home/GetLogIn',
			success: function(result) {
				$("#LoginFormPlaceholderHome").html(result);
				onLoginSubmit();
			}
		});
	});
</script>
</div> -->


</section>

<section class="select-box">
  <?php $a = new Area('Home Select'); $a->display(); ?>
</section>

  <?php $a = new Area('Service Highlight'); $a->display(); ?>

  <?php $a = new Area('Home Signup'); $a->display(); ?>
<?php $this->inc('elements/footer.php'); ?>
