<?php  defined('C5_EXECUTE') or die("Access Denied.");
$p = Page::getCurrentPage();
$pageName = $p->getCollectionName();?>

<?php if($p->getAttribute('hide_features') == ''){ ?>
   <?php $a = new Area('Features'); $a->display(); ?>
<?php }?>

  <?php if($pageName == 'Home'){ ?>
  <?php $a = new Area('About'); $a->display(); ?>
 <?php } ?>

  <?php $a = new Area('Faq');?>
 <?php if ($a->getTotalBlocksInArea($p) > 0 || $p->isEditMode()) { ?>
   <?php $a = new Area('Faq'); $a->display(); ?>
 <?php }?>
 <?php if($p->getAttribute('hide_cta') == ''){ ?>
  <?php $a = new GlobalArea('Contact'); $a->display(); ?>
<?php }?>
<?php if($p->getAttribute('hide_news') == ''){ ?>
   <?php $a = new GlobalArea('News'); $a->display(); ?>
<?php }?>
  </main>


    <footer>
      <div class="container">
        <?php
        $site = \Site::getSite();
        $hide_chat = $site->getAttribute('hide_chat');
        if(!$hide_chat){ ?>
      <div class="hours d-none d-md-block" style="display:none"><button id="tryDemoNowEnCa" class="chat" type="button" class="btn btn-danger btn-demo" onclick="OpenChat('en-CA');" title="Chat with Connexus"><i class="fas fa-comments"></i>&nbsp;Chat with Connexus</button></div>
        <div class="hours d-block d-md-none" style="display:none"><button id="tryDemoNowEnCa" class="chat" type="button" class="btn btn-danger btn-demo" onclick="OpenChat('en-CA');" title="Chat with Connexus"><i class="fas fa-comments"></i>&nbsp;Chat</button></div>
<div class="closed"></div>
<?php } ?>

<!--LOGIN START-->

<div id="LoginFormPlaceholderTopp">
<div class="float-end slideshow_login col-xs-3">
    <div class="login_holder"style="position: fixed;bottom: 0;right: 50px;z-index: 99;">
        <div class="account_signin_outer d-none d-md-flex"><i class="fas fa-user-alt"></i>&nbsp;<h2>Account Sign In</h2> <a href="javascript:void(0)" class="toggle_close">OPEN&nbsp;<i class="fas fa-caret-up"></i></a> </div> <div class="account_signin_outer d-flex d-md-none"><i class="fas fa-user-alt"></i>&nbsp;<h2>Sign In</h2> <a href="javascript:void(0)" class="toggle_close">OPEN&nbsp;<i class="fas fa-caret-up"></i></a> </div>
<div id="LoginFormPlaceholderHome" style="display: none;">



  </div>
  </div> </div>

<script type="text/javascript">
	$(document).ready(function() {
		$.ajax({
			url: 'https://myaccount.connexusenergy.com/Home/GetLogIn',
			success: function(result) {
				$("#LoginFormPlaceholderHome").html(result);
        console.log(result);
				onLoginSubmit();
			}
		});
	});
</script>




	</div>


<!--LOGIN END-->
        <div class="row">
          <div class="col-md-2 col-12 logo-footer">
          <?php $a = new GlobalArea('Footer Logo'); $a->display(); ?>
          </div>
          <div class="col-md-2 col-12 contact-footer">
              <?php $a = new GlobalArea('Footer Contact'); $a->display(); ?>
          </div>
          <div class="col-md-2 col-12 services-footer">
            <?php $a = new GlobalArea('Footer Services'); $a->display(); ?>
          </div>
          <div class="col-md-2 col-12 nav-footer">
              <?php $a = new GlobalArea('Footer Nav'); $a->display(); ?>
          </div>
          <div class="col-md-2 col-12 images-footer">
            <?php $a = new GlobalArea('Footer Images'); $a->display(); ?>
          </div>
          <div class="col-md-2 col-12 social-footer">
            <?php $a = new GlobalArea('Footer Social'); $a->display(); ?>
          </div>
        </div>
      </div>
    </footer>
    <script>
    /*DEF_SERVER = "http://iceChat.connexusenergy.com/iceMessaging/iceMessagingWeb/"
    DEFAULT_SIP_ADDRESS = "sip:iceIMWeb@connexusenergy.com";
        var destination = encodeURIComponent(DEFAULT_SIP_ADDRESS);
        function OpenChat(lang) {
           window.open(DEF_SERVER + '/Login.html?destinationURI=' + destination + '&lang=' + lang, 'iceIM', 'width=490, height=760, resizable=yes, scrollbars=yes');
        }*/
    function OpenChat(lang) {
          <?php $stack = Stack::getByName('Chat with Connexus');
        if(is_object($stack)){ $stack->display(); }?>
    }
</script>

<?php $page = \Page::getByID(1);
$chat = $page->getAttribute('chat_box_off');

if ($chat == '1') {
    echo '<style>';
    echo 'button.chat{display:none !important}';
    echo '</style>';
   // echo 'OFF';
} else {
    echo '';
   // echo 'ON';
}

?>
<style>.hours.hidden { display: none !important; }</style>
<script>
   var holidays='<?=str_replace(array("\r","\n"),array('','|'),$site->getAttribute('holidays'))?>' ;
   holidays= holidays.split('|');
var d = new Date();
var offset='-5';
    // create Date object for current location
    var d = new Date();

    // convert to msec
    // subtract local time zone offset
    // get UTC time in msec
    var utc = d.getTime() + (d.getTimezoneOffset() * 60000);

    // create new Date object for different city
    // using supplied offset
    var nd = new Date(utc + (3600000*offset));
var dayOfWeek = nd.getDay();
var hour = nd.getHours();
var mins = nd.getMinutes();
var status = 'open';
var formattedTodayDate=new Intl.DateTimeFormat('en-US').format(new Date());
//THE LINE BELOW SETS THE ACTIVE DAYS AND HOURS
$(document).ready(function(){
if (dayOfWeek !== 6 && dayOfWeek !== 0 && hour >= 9 && hour < 16){

//THE LINE BELOW SETS THE CLOSING TIME
    if (hour=='16' && mins >= '0'){
        status = 'closed';
        console.log('we are closed');
        $('.hours').addClass('hidden');
    }
/** else if (hour=='7' && mins < '30'){
            status = 'closed';
			console.log('we are closed');
        } */
        else if (hour < '9'){
            status = 'closed';
			console.log('we are closed');
        }
	    else{
        status = 'open';
        console.log('we are open!');
        $('.closed').addClass('hidden');
        //echo '<button id="tryDemoNowEnCa" class="chat" type="button" class="btn btn-danger btn-demo" onclick="OpenChat(\'en-CA\');" title="Chat with Connexus">Chat with Connexus <img src="/application/themes/connexus/images/chat-icon.png" style="vertical-align:middle; padding:0 0 0 30px;" /></button>';
    }
}else{
    status = 'closed';
}

if(holidays.length>0 && $.inArray( formattedTodayDate, holidays)>=0){
  status = 'closed';
}
if (status =='open') {
    $('.hours').removeClass('hidden');
    $('.hours').show();
    $('.closed').hide();

   console.log('if status open') ;

}else{
    $('.hours').addClass('hidden');
    $('.closed').show();
    console.log('if status closed') ;
}
});

</script>
<script>$(document).ready(function(){$("form").attr('autocomplete', 'off');});</script>
<script>(function(document, tag) { var script = document.createElement(tag); var element = document.getElementsByTagName('body')[0]; script.src = 'https://acsbap.com/apps/app/assets/js/acsb.js'; script.async = true; script.defer = true; (typeof element === 'undefined' ? document.getElementsByTagName('html')[0] : element).appendChild(script); script.onload = function() { acsbJS.init({ statementLink : '', feedbackLink : '', footerHtml : '', hideMobile : false, hideTrigger : false, language : 'en', position : 'left', leadColor : '#474747', triggerColor : '#d1312f', triggerRadius : '50%', triggerPositionX : 'left', triggerPositionY : 'bottom', triggerIcon : 'default', triggerSize : 'medium', triggerOffsetX : 20, triggerOffsetY : 20, mobile : { triggerSize : 'small', triggerPositionX : 'left', triggerPositionY : 'center', triggerOffsetX : 0, triggerOffsetY : 0, triggerRadius : '50%' } }); };}(document, 'script'));</script>



    <script type="text/javascript">
    $(document).ready(function() {

      $('.account_signin_outer').click(function(){
        if($('#LoginFormPlaceholderHome').is(':visible')){
          $('.toggle_close').html('OPEN&nbsp;<i class="fas fa-caret-up"></i>');
          $('#LoginFormPlaceholderHome').slideUp();
        }else{
          $('.toggle_close').html('CLOSE&nbsp;<i class="fas fa-close"></i>');
          $('#LoginFormPlaceholderHome').slideDown();
        }
      });
var scroll = $(window).scrollTop();
if (scroll >= 100) {
    $("header").addClass("sticky_header");
}else{
  $("header").removeClass("sticky_header");
}
$(window).scroll(function() {
    var scroll = $(window).scrollTop();
    if (scroll >= 100) {
        $("header").addClass("sticky_header");
    }else{
      $("header").removeClass("sticky_header");
    }
});
$(".table-site").wrap("<div class='table-site-outer'></div>");
});
    </script>
    <style>
.loginSub.fs-8.mx-3.row.row-cols-auto > label.mt-3.mt-md-0:first-child {
display:none !important;
}
button.chat {
  position: fixed;
    bottom: 20px;
    left: 60px;
    width: 260px;
    height: 45px;
    line-height: 45px;
    background-color: #E31122;
    text-align: center;
    font-size: 17px;
    color: #fff;
    z-index: 999999;
    border: none;
}

button.chat:hover {
    position: fixed;
    bottom: 20px;
    left: 60px;
    width: 260px;
    height: 45px;
    line-height: 45px;
    background-color: #CE0011;
    text-align: center;
    font-size: 17px;
    color: #fff;
    z-index: 999999;
    border: none;
}
.account_signin_outer {
  padding: 0 30px;
    position: relative;
    margin-bottom: 0px;
    bottom: 0;
    right: 0;
    width: 100%;
    height: 50px;
    line-height: 50px;
    background-color: #CE0011;
    text-align: center;
    font-size: 17px;
    color: #fff;
    z-index: 999999;
    border: none;
    display: -webkit-inline-box;
}
.account_signin_outer * {
  text-transform: uppercase !important;
  font-size: 17px !important;
}
.account_signin_outer h2{
  margin: 0px;
    line-height: unset;
}
.login_holder {
  width: 400px;
}
.toggle_close {
  float: right;
    right: 30px;
    position: absolute;
}
#LoginFormPlaceholderHome{
  background-color:#d9d9d9;
  padding: 15px;
}
    </style>
<?php $this->inc('elements/footer_bottom.php');?>
