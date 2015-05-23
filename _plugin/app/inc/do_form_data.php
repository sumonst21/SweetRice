<?php
/**
 * App form data management template.
 *
 * @package SweetRice
 * @Plugin App
 * @since 1.5.0
 */
	defined('VALID_INCLUDE') or die();
	$mode = $_GET['mode'];
	switch($mode){
		case 'delete':
			$id = intval($_GET['id']);
			if($id > 0){
				remove_form_data($id);
			}
			_goto($_SERVER['HTTP_REFERER']);
		break;
		case 'bulk':
			$plist = $_POST['plist'];
			foreach($plist as $val){
				$val = intval($val);
				if($val>0){
					$ids[] = $val;
				}
			}
			if(count($ids)>0){
				$ids = implode(',',$ids);
				remove_form_data($ids);
			}
			_goto($_SERVER['HTTP_REFERER']);
		break;
		default:
			$form_id = intval($_GET['form_id']);
			$where = " 1=1 ";
			if($form_id > 0){
				$where .= " AND afd.`form_id` = '$form_id'";
				$search_url = '&form_id='.$form_id;
			}
			$data = db_fetch(array('table'=>ADB.'_app_form_data as afd LEFT JOIN '.ADB.'_app_form as af ON af.id = afd.form_id',
				'field' => 'afd.*,af.name,af.fields',
				'where' => $where,
				'order' => 'afd.date DESC',
				'pager' => array('p_link'=>pluginDashboardUrl(THIS_APP,array('app_mode'=>'form_data')).$search_url.'&','page_limit'=>intval($_COOKIE['page_limit'])?intval($_COOKIE['page_limit']):10,'pager_function'=>'_pager')
			));
			$forms = db_arrays("SELECT * FROM `".ADB."_app_form`");
			$app_inc = 'form_data_list.php';
	}
?>