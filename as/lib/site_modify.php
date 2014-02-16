<?php
/**
 * Sites management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 1.3.2
 */
 defined('VALID_INCLUDE') or die();
 $lang_types = getLangTypes();
 $s_lang[$global_setting['theme_lang']] = 'selected';
?>
<style>
.row2 dl{clear:both;}
.row2 dl dt{float:left;width:15%;margin:5px 0px;display:inline;}
.row2 dl dd{float:left;width:84%;margin:5px 0px;display:inline;}
</style>
<form method="post" action="./?type=sites&mode=save" enctype="multipart/form-data">
<fieldset><legend><?php echo HOST;?></legend>
<input type="text" name="host" onchange="$('host_body').innerHTML = this.value + '/';"/>
</fieldset>
<fieldset><legend><?php echo DATABASE.' '.SETTING;?></legend>
<div class="row2">
<dl><dt>
<?php echo DATABASE;?>:</dt><dd><select name="site_config[db_type]" class="database_type">
	<option value="mysql" <?php echo $s_dtype['mysql'];?>>Mysql</option>
	<option value="sqlite" <?php echo $s_dtype['sqlite'];?>>Sqlite</option>
	<option value="pgsql" <?php echo $s_dtype['pgsql'];?>>Postgresql</option>
</select>
</dd></dl></div>
<div id="database_setting" class="row2" style="display:<?php echo $_POST["database_type"]=='sqlite'?'none':'block';?>">
<dl><dt><?php echo DATABASE_HOST;?> : </dt><dd><input type="text" name="site_config[db_url]" value="<?php echo $_POST["db_url"]?$_POST['db_url']:'localhost';?>"> *<?php echo DATABASE_HOST_TIP;?></dd></dl>
<dl><dt><?php echo DATA_PORT;?> : </dt><dd><input type="text" name="site_config[db_port]" id="db_port" value="<?php echo $_POST["db_port"]?$_POST["db_port"]:3306;?>"></dd></dl>
<dl><dt><?php echo DATA_ACCOUNT;?> : </dt><dd><input type="text" name="site_config[db_username]" value="<?php echo $_POST["db_username"];?>"></dd></dl>
<dl><dt><?php echo DATA_PASSWORD;?> : </dt><dd><input type="password" name="site_config[db_passwd]" value="<?php echo $_POST["db_passwd"];?>"></dd></dl>
</div>
</fieldset>
<fieldset><legend><?php echo DATA_NAME;?></legend>
<input type="text" name="site_config[db_name]" value="<?php echo $_POST["db_name"];?>"></fieldset>
<fieldset><legend><?php echo DATA_PREFIX;?></legend>
<input type="text" name="site_config[db_left]" value="<?php echo $_POST["db_left"]?$_POST['db_left']:'v';?>"></fieldset>
<fieldset><legend><?php echo ADMIN_ACCOUNT;?></legend>
<input type="text" name="admin" value="<?php echo $_POST["admin"];?>"></fieldset>
<fieldset><legend><?php echo ADMIN_PASSWORD;?></legend>
<input type="password" name="passwd"></fieldset>
<fieldset><legend><?php echo SITE_ATTACHMENT_DIR;?></legend>
<input type="radio" name="attachment_type" value="1" checked/>_sites/<span id="host_body"></span><input type="text" name="attachment_dir" value="attachment">
<input type="radio" name="attachment_type" value="2"/><?php echo ATTACHMENT_DIR;?>
</fieldset>
<fieldset><legend><?php echo THEME;?></legend>
<?php
	foreach($themes as $val){
?>
<input type="checkbox" name="themes[]" value="<?php echo $val;?>" <?php echo $val=='default'?'checked onclick="return false;" ':'';?>/> <?php echo $val;?> 
<?php
	}	
?>
</fieldset>
<fieldset><legend><?php echo PLUGIN;?></legend>
<?php
	foreach(pluginList() as $val){
?>
<input type="checkbox" name="plugins[]" value="<?php echo $val['directory'];?>"/> <?php echo $val['name'];?> 
<?php
	}	
?>
</fieldset>
<input type="submit" class="input_submit" value="<?php echo DONE;?>"> <input type="button" value="<?php echo BACK;?>" onclick='location.href="./?type=sites"' class="input_submit">
</form>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.database_type').bind('change',function(){
			var t = _(this).val();
			if(t == 'sqlite'){
				_('#database_setting').hide();
			}else{
				_('#database_setting').show();
				if(t == 'mysql'){
					_('#db_port').val(3306);
				}
				if(t == 'pgsql'){
					_('#db_port').val(5432);
				}
			}
		});
		_('#meta_setting').bind('click',function(){
			_('#meta').toggle();
		});
	});
//-->
</script>