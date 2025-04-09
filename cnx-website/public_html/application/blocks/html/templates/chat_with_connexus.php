<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php
//<i class="fas fa-comments"></i>
// https://connexus.iceuc.com/iceMessaging/Login.html?dId=main&lang=en-CA

//$content = 'http:' . end(explode(":",$content));
echo $chat_link = "window.open('" . trim($content) . "','iceIM','width=490, height=760, resizable=yes, scrollbars=yes');"; ?>
