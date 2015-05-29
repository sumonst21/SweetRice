<?php
/**
 * Entry management template.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
 defined('VALID_INCLUDE') or die();
?>
<form enctype="multipart/form-data" method="post" action="./?type=post&mode=insert">
<input type="hidden" name="returnUrl" value="<?php echo $returnUrl;?>"/>
<input type="hidden" name="createTime" value="<?php echo $row['date'];?>"/>
<input type="hidden" name="views" value="<?php echo $row['views'];?>" >
<input type="hidden" name="id" value="<?php echo $row['id'];?>" >
<fieldset><legend><?php _e('Name');?>:</legend>
<input type="text" name="name" class="input_text" value="<?php echo $row['name'];?>"> * 
<?php
	if($row['sys_name']){
?>
<a href="<?php echo SITE_URL.show_link_page($categories[$row['category']]['link'],$row['sys_name']);?>" target="_blank"><?php _e('Prevew');?></a>
<a title="<?php _e('Delete');?>" href="javascript:void(0);" class="btn_one" url="./?type=post&mode=delete&id=<?php echo $row['id'];?>&one=1"><?php _e('Delete');?></a> 
<?php echo date('m/d/Y H:i:s',$row['date']);?> <input type="checkbox" name="republish" value="1"/> <?php _e('Update');?>?
<?php
	}
?>
</fieldset>
<fieldset><legend><?php _e('Slug');?>:</legend>
<input type="text" name="sys_name" class="input_text slug" value="<?php echo $row['sys_name'];?>"> * <span class="tip"><?php _e('Only a-z,A-Z,0-9,-,_ ,system will create one if empty');?></span>
<?php
	if($row['sys_name']){
echo '<div id="permalinks"><p>'.SITE_URL.show_link_page($categories[$row['category']]['link'],$row['sys_name']).'</p></div>';
	}
?>
</fieldset>
<fieldset><legend><?php _e('Title');?>:</legend>
<input type="text" name="title" class="input_text" value="<?php echo $row['title'];?>"> * <span class="tip"><?php _e('Title of Page');?></span>
</fieldset>
<fieldset><legend><?php _e('Meta Setting');?></legend>
<ul>
<li><input type="text" name="keyword" class="input_text meta" value="<?php echo $row['keyword']?$row['keyword']:_t('Keywords');?>" data="<?php _e('Keywords');?>"> * <span class="tip"><?php _e('Keywords of Page');?></span></li>
<li><input type="text" name="description" class="input_text meta" value="<?php echo $row['description']?$row['description']:_t('Description');?>" data="<?php _e('Description');?>"> * <span class="tip"><?php _e('Description of Page');?></span>
</li>
</ul>
</fieldset>
<fieldset><legend><?php _e('Tag');?>:</legend><input type="text" name="tags" class="input_text" value="<?php echo htmlspecialchars($row['tags'],ENT_QUOTES);?>"> * <span class="tip"><?php _e('Split by commas');?></span>
</fieldset>
<fieldset><legend><?php _e('Body');?>: </legend> 
<div class="mg5"><label class="editor_toggle" tid="info" data="visual"><?php _e('Visual');?></label>
<label class="editor_toggle current_label" data="html" tid="info"><?php _e('HTML');?></label><label class="editor_toggle btn_upload"><?php _e('Insert images');?></label></div>
<?php include('lib/tinymce.php');?>
<textarea id="info" name="info">
<?php echo htmlspecialchars($row['body']);?>
</textarea>
</fieldset>
<fieldset><legend><?php _e('Category');?>:</legend>
<select name="category">
<option value="0"> -- <?php _e('Uncategory');?> -- </option>
<?php
$s_category[$row['category']] = 'selected';
	foreach($subCategory as $val){
		$_prefix = '';
		for($i=0; $i<$val['level']; $i++){
			$_prefix .= '-- ';
		}
		echo '<option value="'.$val['id'].'" '.$s_category[$val['id']].'>'.$_prefix.$categories[$val['id']]['name'].'</option>';
	}
?>
</select>
</fieldset>
<fieldset><legend><?php _e('Template');?>:<?php echo $row['template'];?></legend>
<select name="template">
<?php
	foreach($template as $key=>$val){
		$s = '';
		if($key == $row['template']){
			$s = 'selected';
		}
		echo '<option value="'.$key.'" '.$s.' title="'.$key.'">'.$val.'</option>';
	}
?>
</select>
</fieldset>
<fieldset><legend><?php _e('Option');?>:</legend>
<?php _e('Publish');?> <input type="checkbox" name="in_blog" value="1" <?php echo $row['in_blog']?'checked':'';?> >
<?php _e('Allow Comment');?> <input type="checkbox" name="allow_comment" value="1" <?php echo $row['allow_comment']?'checked':'';?> >
</fieldset>
<fieldset><legend class="toggle" data=".attbody"><?php _e('Attachment');?>:</legend>
<div class="attbody">
<?php
$no = 0;
if(count($att_rows)){
	foreach($att_rows AS $att_row){
		$att_row['file_name'] = getAttachmentUrl($att_row['file_name']);
		$no +=1;
		$is_local = false;
		if(substr($att_row['file_name'],0,strlen(SITE_URL))==SITE_URL){
			$is_local = true;
		}
?>
<div class="att_list">
<li id="f_<?php echo $no;?>">
<input type="hidden" name="attid_<?php echo $no;?>" value="<?php echo $att_row['id'];?>"/>
<input type="hidden" name="atttimes_<?php echo $no;?>" value="<?php echo $att_row['downloads'];?>"/>
<input type="hidden" name="attdate_<?php echo $no;?>" value="<?php echo $att_row['date'];?>"/>
No.<?php echo $no;?> <?php _e('Filename');?>:<input type="text" id="att_<?php echo $no;?>" name="att_<?php echo $no;?>" value="<?php echo $att_row['file_name'];?>" class="input_text"/> <input type="button" value="<?php _e('Replace');?>" class="replaceAtt" data="<?php echo $no;?>">
 <input type="button" value="<?php _e('Remove File');?>" class="delfile" data="<?php echo $no;?>"> <?php _e('Upload Time');?>:<?php echo date('m/d/y H:i:s',$att_row['date']);?> <?php _e('Download Times');?>:<?php echo $att_row['downloads'];?> <?php echo $is_local?(file_exists(str_replace(SITE_URL,ROOT_DIR,$att_row['file_name']))?'<span class="file_exists">'._t('File exists').'</span>':'<span class="file_noexists">'._t('Does not exists').'</span'):_t('Remote File');?> 
</li>
</div>
<?php
	}
}
?>
<input type="hidden" id="no" name="no" value="<?php echo $no;?>">
<div id="muti_files"></div>
<input type="button" value="<?php _e('Add File');?>"  class="att_add">
</div>
</fieldset>
<script type="text/javascript">
	<!--
	var attNo = <?php echo $no;?>;
	var attach_media;
	_().ready(function(){
	_('.btn_upload').bind('click',function(){
		_(this).prev().prev().click();
		_.dialog({'title':'<?php _e('Image List');?>','content':'<iframe src="./?type=image" style="border:none;width:100%;height:'+(_.pageSize().windowHeight-200)+'px;"></iframe>','width':_.pageSize().windowWidth,'height':_.pageSize().windowHeight-150,'layer':true});
	});
		_('.att_add').bind('click',function(event){
			attNo += 1;
			_('#no').val(attNo);
			var new_file = document.createElement('div');
			_(new_file).attr('id','f_'+attNo).html('<div class="att_list"><?php _e('New');?> <input type="text" name="att_'+attNo+'" id="att_'+attNo+'" class="input_text"/><span id="attname_'+attNo+'"></span> <input type="button" value="<?php _e('Remove');?>" class="delfile" data="'+attNo+'"> <input type="button" value="<?php _e('Replace');?>" class="replaceAtt" data="'+attNo+'"></div>');
			_('#muti_files').append(new_file);
			_.dialog({'content':'<iframe id="media_body" src="./?type=media&referrer=attachment"></iframe>','name':'media','width':800,'height':500,'layer':true});
			attach_media = _('#att_'+attNo); 
			_('.delfile').unbind('click').bind('click',function(event){
				_('#f_'+_(this).attr('data')).remove();
			});
			_('.replaceAtt').unbind('click').bind('click',function(event){
				attach_media = _('#att_'+_(this).attr('data'));
				_.dialog({'content':'<iframe id="media_body" src="./?type=media&referrer=attachment"></iframe>','title':'Choose file','name':'media','width':800,'height':500,'layer':true});
			});
		});
		_('.delfile').bind('click',function(event){
			_('#f_'+_(this).attr('data')).remove();
		});	
		_('.replaceAtt').bind('click',function(event){
			attach_media = _('#att_'+_(this).attr('data'));
			_.dialog({'content':'<iframe id="media_body" src="./?type=media&referrer=attachment"></iframe>','title':'Choose file','name':'media','width':800,'height':500,'layer':true});
		});
	});
	//-->
	</script>
<?php 
	$cfdata = getOption('custom_post_field');
	include('lib/custom_field.php');
?>
<p><input type="submit" class="input_submit" name="done" value="<?php _e('Done');?>">
<?php
	if($row['sys_name']){
?><input type="submit" value="<?php _e('Update');?>" name="update" class="input_submit">
<?php
	}
?><input type="button" value="<?php _e('Back');?>" url="./?type=post" class="input_submit back"></p>
</form>
</div>
<div class="div_clear"></div>
</div>
<script type="text/javascript">
<!--
	_().ready(function(){
		_('.btn_one').bind('click',function(){
			if(confirm('<?php _e('Are you sure delete it?');?>')) location.href = _(this).attr('url');
		});
		_('.slug').bind('change',function(){
			_(this).val(_(this).val().replace(/([^a-z0-9A-Z\-_])/g,'-').replace(/(^-*)|(-*$)/g,''));
		});
		_('.meta').bind('blur',function(){
			if (!_(this).val()) {
				_(this).val(_(this).attr('data'));
			}
		}).bind('focus',function(){
			if (_(this).val() == _(this).attr('data')) {
				_(this).val('');
			}
		});
	});
//-->
</script>
<?php
	include('lib/foot.php');	
	exit();
?>