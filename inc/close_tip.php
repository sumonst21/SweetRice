<?php
/**
 * Site close tip
 *
 * @package SweetRice
 * @Default template
 * @since 1.3.2
 */
 	defined('VALID_INCLUDE') or die();
?>
<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0" name="viewport" id="viewport"/><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title><?php _e('Welcome to SweetRice - Thank your for install SweetRice as your website management system.');?></title>
<title><?php _e('SweetRice notice');?></title>
<script type="text/javascript" src="<?php echo BASE_URL;?>js/SweetRice.js"></script>
<style>
*{margin:0;}
body{font-family:"Microsoft YaHei",Verdana,Georgia,arial,sans-serif;}
.header{line-height:30px;font-size:20px;background-color:#444;box-shadow:0px 0px 2px 2px #444;color:#fafafa;padding:0px 10px;}
#div_foot{	background-color:#444;height:30px;	line-height:30px;	color:#fff;padding:0px 10px;}
#div_foot a{	color: #66CC00;	text-decoration: none;}
#div_foot a:hover{	color: #66CC00;	text-decoration: underline;}
.content{margin:0px 10px;}
.content h1{
	margin:20px 0px;
	font-size:22px;
}
.content div,.content p{margin-bottom:16px;}
</style>
</head>
<body>
<div class="header"><?php _e('SweetRice notice');?></div>
<div class="content">
<?php echo $global_setting['close_tip']?$global_setting['close_tip']:_t('<p>Welcome to SweetRice - Thank your for install SweetRice as your website management system.</p><h1>This site is building now , please come late.</h1><p>If you are the webmaster,please go to Dashboard -> General -> Website setting </p><p>and uncheck the checkbox "Site close" to open your website.</p><p>More help at <a href="http://www.basic-cms.org/docs/5-things-need-to-be-done-when-SweetRice-installed/">Tip for Basic CMS SweetRice installed</a></p>');?>
</div>
<div id="div_foot">Powered by <a href="http://www.basic-cms.org">Basic-CMS.ORG</a> SweetRice.</div>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.content').css({'margin-top':((_.pageSize().windowHeight-60-_('.content').height())/2)+'px','margin-bottom':((_.pageSize().windowHeight-60-_('.content').height())/2)+'px'});
	});
	_(window).bind('resize',function(){
		_('.content').animate({'margin-top':((_.pageSize().windowHeight-60-_('.content').height())/2)+'px','margin-bottom':((_.pageSize().windowHeight-60-_('.content').height())/2)+'px'});
	});
//-->
</script>
</body>
</html>