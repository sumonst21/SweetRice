<?php
/**
 * Template Name:Home page template.
 *
 * @package SweetRice
 * @Wblog template
 * @since 0.5.4
 */
	defined('VALID_INCLUDE') or die();
	if(file_exists(THEME_DIR.$page_theme['head'])){
		include(THEME_DIR.$page_theme['head']);		
	}
?>
<script type="text/javascript" src="js/init.js"></script>
<div id="content">
	<h1 align="center"><?php echo vsprintf(HOME_H1,array($global_setting['name']));?></h1>
	<div id="posts">
<?php	
	if(count($rows)):
		foreach($rows as $row):
			echo _posts($row,$post_output);
		endforeach;		
	endif;
?>
</div>
		 <div id="pins_loader"></div>
<script type="text/javascript">
<!--
	var query = new Object();
	query.action = '';
	query.p = <?php echo $pager['page'];?>;
	var bodyId = 'posts';
//-->
</script>
<script type="text/javascript" src="js/pins.js"></script>
</div>
<?php
	if(file_exists(THEME_DIR.$page_theme['foot'])){
		include(THEME_DIR.$page_theme['foot']);	
	}
?>