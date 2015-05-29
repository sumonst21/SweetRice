<?php
/**
 * Bugs management template.
 *
 * @package SweetRice
 * @Plugin member
 * @since 1.3.4
 */
	defined('VALID_INCLUDE') or die();
	function _unzip($file_name,$dest){
		if(!is_dir($dest.'temp')){
			mkdir($dest.'temp');
		}
		if(floatval(PHP_VERSION)<5.2)
		{
			$zip = zip_open($file_name);
			if ($zip) {
				while ($zip_entry = zip_read($zip)) {
					if (zip_entry_open($zip, $zip_entry, "r")) {
						$buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
						if(substr(zip_entry_name($zip_entry),-1)=='/')
						{
							//mkdir('./'.zip_entry_name($zip_entry));
						}else
						{
							$handle = fopen(zip_entry_name($dest.'temp/'.$zip_entry),'wb');
							fwrite($handle,$buf);
							fclose($handle);
							$data[] = $zip_entry;
						}
						zip_entry_close($zip_entry);
					}
				}
				zip_close($zip);
			}			
		}else
		{
			$zip = new ZipArchive();
			if ($zip->open($file_name) === TRUE) {
				$zip->extractTo($dest.'temp/');
				$zip->close();	
				$d = dir($dest.'temp/');
				while (false !== ($entry = $d->read())) {
					if($entry!='.'&&$entry!='..')
					{
						$data[] = $entry;
					} 
				}
				$d->close();
			}
		}
		return $data;
	}
	if($_GET['mode'] == 'clean'){
		$_SESSION['imgs'] = array();
	}
	if($_GET['mode'] == 'delete'){
		$img = str_replace(SITE_URL,SITE_HOME,$_POST['img']);
		if($img && is_file($img)){
			unlink($img);
			$tmp = array();
			foreach($_SESSION['imgs'] as $val){
				if($val != $_POST['img']){
					$tmp[] = $val;
				}
			}
			$_SESSION['imgs'] = $tmp;
			output_json(array('status'=>1,'img'=>$img,'data'=>$_POST['img']));
		}else{
			output_json(array('status'=>1,'status_code'=>_t('No image selected')));
		}
	}
	$dest_dir = date('Ymd').'/';
	$tmp_dir = '../'.ATTACHMENT_DIR.$dest_dir;
	if(!is_dir($tmp_dir)){
		mkdir($tmp_dir);
	}

	if(is_array($_FILES['imgs']['name'])){
		foreach($_FILES['imgs']['name'] as $key=>$val){
			$tmp = array(
				'name' => $_FILES['imgs']['name'][$key],
				'type' => $_FILES['imgs']['type'][$key],
				'tmp_name' => $_FILES['imgs']['tmp_name'][$key],
				'error' => $_FILES['imgs']['error'][$key],
				'size' => $_FILES['imgs']['size'][$key]
			);
			
			if(substr($tmp['name'],-4) == '.zip'){
				$data = _unzip($tmp['tmp_name'],$tmp_dir);
				foreach($data as $val){
					$tmp = explode('.',$val);
					$ext = end($tmp);
					$name = md5($tmp_dir.'temp/'.$val.time()).$ext;
					rename($tmp_dir.'temp/'.$val,$tmp_dir.$name);
					$_SESSION['imgs'][] = BASE_URL.ATTACHMENT_DIR.$dest_dir.$name;
				}
				un_($tmp_dir.'temp/');
			}else{
				$upload = upload_($tmp,$tmp_dir,$tmp['name'],null);
				if(file_exists($tmp_dir.$upload)){
					$_SESSION['imgs'][] = BASE_URL.ATTACHMENT_DIR.$dest_dir.$upload;
				}
			}
		}
		_goto('./?type=image');
	}elseif($_FILES['imgs']['name']){
		if(substr($_FILES['imgs']['name'],-4) == '.zip'){
			$data = _unzip($_FILES['imgs']['tmp_name'],$tmp_dir);
			foreach($data as $val){
				$tmp = explode('.',$val);
				$ext = end($tmp);
				$name = md5($tmp_dir.'temp/'.$val.time()).$ext;
				rename($tmp_dir.'temp/'.$val,$tmp_dir.$name);
				$_SESSION['imgs'][] = BASE_URL.ATTACHMENT_DIR.$dest_dir.$name;
			}
			un_($tmp_dir.'temp/');
		}else{
			upload_($_FILES['imgs'],$tmp_dir,$_FILES['imgs']['name'],null);
			$_SESSION['imgs'][] = BASE_URL.ATTACHMENT_DIR.$dest_dir.$upload;
		}
		_goto('./?type=image');
	}
	define('UPLOAD_MAX_FILESIZE',ini_get('upload_max_filesize'));
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php _e('Dashboard');?></title>
<script type="text/javascript" src="<?php echo BASE_URL;?>js/SweetRice.js"></script>
<style>
body{font-family:"Microsoft YaHei";font-size:small;}
.imgs{max-height:450px;}
.imgs ul{margin: 10px 0px; padding: 0px;}
.imgs li{width:18%;float:left;display:inline;list-style-type:none;height:100px;margin-bottom:10px;position:relative;box-shadow: 1px 1px 5px #000;margin:1%;text-align:center;padding: 5px 0px;}
.imgs li img{max-width:98%;max-height:98%;border:1px solid #d8d8d8;}
.imgs li input[type=checkbox]{position:absolute;right:5px;bottom:5px;}
.img_delete{position:absolute;left:5px;bottom:5px;width:16px;height:16px;line-height:16px;border:1px solid #ccc;text-decoration: none;color:#ccc;background-color: #fff;cursor:pointer;display:none;}
.clear{clear:both;}
</style>
</head>
<body>
<form method="post" action="" enctype="multipart/form-data" >
	<input type="file" name="imgs[]" title="<?php echo _t('Max upload file size'),':',UPLOAD_MAX_FILESIZE;?>" multiple> <input type="submit" value="<?php _e('Upload');?>" class="input_submit"/> <?php _e('Supports zip archive');?>
	<?php _e('all');?> <input type="checkbox" class="ck_item"><input type="button" value="<?php _e('Insert images');?>" class="btn_attach"> <input type="button" value="<?php _e('Reset');?>" class="btn_clean">
</form>
<div class="imgs">
<ul>
<?php 
foreach($_SESSION['imgs'] as $img):?>
<li data="<?php echo $img;?>"><img src="<?php echo $img;?>"><input type="checkbox" class="imglist" value="<?php echo $img;?>" /> <a href="javascript:void(0);" class="img_delete">&times;</a></li>
<?php endforeach;?>
<div class="clear"></div>
</ul>
</div>
<script type="text/javascript">
<!--
	_.ready(function(){
		_('.img_delete').click(function(){
			var _this = this;
			_.ajax({
				'type':'post',
				'data':{'img':_(this).parent().attr('data')},
				'url':'./?type=image&mode=delete',
				'success':function(result){
					if (result['status'] == 1)
					{
						_(_this).parent().remove();
					}else{
						_.ajax_untip(result['status_code']);
					}
				}
			});
		});
		_('.imgs li').hover(function(){
				_(this).find('a').show();
			},
			function(){
				_(this).find('a').hide();
			}
		);
		_('.btn_clean').bind('click',function(){
			location.href = './?type=image&mode=clean';
		});
		_('.ck_item').bind('change',function(){
			_('.imglist').prop('checked',_(this).prop('checked'));
		});
		_('.btn_attach').bind('click',function(){
			var str = '';
			_('.imglist').each(function(){
				if (_(this).prop('checked'))
				{
					str += '<p style="text-align:center;"><img src="'+_(this).val()+'" style="max-width:100%;"></p>';
				}
			});
			if (!str)
			{
				_.ajax_untip('<?php _e('No image selected');?>');
				return ;
			}
			var ifr_body = parent.window._('.btn_upload').parent().parent().find('iframe').items();
			ifr_body.contentWindow.document.body.innerHTML = str + ifr_body.contentWindow.document.body.innerHTML;
			parent._('.SweetRice_dialog_close').run('click');
		});
	});
//-->
</script>
</body>
</html>
<?php exit;?>