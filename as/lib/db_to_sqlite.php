<?php
/**
 * Convert Database to Sqlite.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.5
 */
 defined('VALID_INCLUDE') or die();
	$to_db_name = $_POST["to_db_name"];
	$to_db_left = $_POST["to_db_left"];
	if(DATABASE_TYPE == 'sqlite' && $to_db_name == $db_name && $to_db_left == DB_LEFT){
		alert(DATABASE_CONVERT_SUCCESSFULLY,'./');
	}
	$tablelist = $_POST["tablelist"];
	if($to_db_name&&$to_db_left&&$tablelist){
			$plugin_sql = array();
			$plugin_list = pluginList();
			foreach($plugin_list AS $plugin_config){
				if(file_exists(SITE_HOME."_plugin/".$plugin_config['directory'].'/plugin_config.php') && $plugin_config['installed']){
					if($plugin_config['install_sqlite']){
						$plugin_sql[$plugin_config['name']] = SITE_HOME."_plugin/".$plugin_config['directory']."/".$plugin_config['install_sqlite'];
					}
				}
			}
			$dbname = SITE_HOME.'inc/'.$to_db_name.'.db';
			if(extension_loaded('pdo_sqlite')){
				$sqlite_driver = 'pdo_sqlite';
			}elseif(class_exists('SQLite3')){
				$sqlite_driver = 'sqlite3';
			}elseif(function_exists('sqlite_open')){
				$sqlite_driver = 'sqlite';
			}
			$to_db = sqlite_dbhandle($dbname);
			if(!$to_db){
				$error_db = true;
			}else{
				$sql = file_get_contents('lib/blog_sqlite.sql');
				$sql = str_replace('%--%',$to_db_left,$sql);
				$sql = explode(';',$sql);
				foreach($sql as $key=>$val){
					if(trim($val)){
						$error = sqlite_dbquery($to_db,$val);
						if($error){
							$message .= $error.'<br>';
						}
					}
				}	
				foreach($plugin_sql as $key=>$val){
						$sql = file_get_contents($val);
						$sql = str_replace('%--%',$to_db_left.'_plugin',$sql);
						$sql = explode(';',$sql);
						foreach($sql as $key=>$val){
							if(trim($val)){
								$error = sqlite_dbquery($to_db,$val);
								if($error){
									$message .= $error.'<br>';
								}
							}
						}	
				}
				switch(DATABASE_TYPE){
					case 'sqlite':
						foreach($tablelist as $val){
							$to_val = $to_db_left.substr($val,strlen(DB_LEFT));
							$field_list = array();
							$rows = db_arrays("SELECT * FROM `".$val."`");
							$fields = db_arrays("PRAGMA table_info(".$val.")");
							foreach($fields as $field){
								$field_list[] = $field['name'];
							}
							foreach($rows as $row){
								$comma = "";
								$tabledump = "INSERT INTO \"".$to_val."\" VALUES(";
								foreach($field_list as $fl){
									if(is_string($row[$fl])){
										$str = sqlite_escape_string($row[$fl]);
									}else{
										$str = $row[$fl];
									}	
									$tabledump .= $comma."'".$str."'";
									$comma = ",";
								}
								$tabledump .= ");";
								$error = sqlite_dbquery($to_db,$tabledump);
								if($error){
									$db_error .= $error.'<br>';
								}
							}
						}
						if(!$db_error){
							$do_db = true;
						}else{
							$message .= $db_error;
						}
					break;
					case 'mysql':
						foreach($tablelist as $val){
							$to_val = $to_db_left.substr($val,strlen(DB_LEFT));
							$res = mysql_query("SELECT * FROM `".$val."`");
							$numfields = mysql_num_fields($res);
							while ($row = mysql_fetch_row($res)){
								$comma = "";
								$tabledump = "INSERT INTO \"".$to_val."\" VALUES(";
								for($i = 0; $i < $numfields; $i++){
									if(is_string($row[$i])){
										$str = sqlite_escape_string($row[$i]);
									}else{
										$str = $row[$i];
									}
									$tabledump .= $comma."'".$str."'";
									$comma = ",";
								}
								$tabledump .= ");";
								$error = sqlite_dbquery($to_db,$tabledump);
								if($error){
									$db_error .= $error.'<br>';
								}
							}
						}
						if(!$db_error){
							$do_db = true;
						}else{
							$message .= $db_error;
						}
					break;
					case 'pgsql':
						foreach($tablelist as $val){
							$to_val = $to_db_left.substr($val,strlen(DB_LEFT));
							$res = pg_query("SELECT * FROM \"".$val."\"");
							$numfields = pg_num_fields($res);
							while ($row = pg_fetch_row($res)){
								$comma = "";
								$tabledump = "INSERT INTO \"".$to_val."\" VALUES(";
								for($i = 0; $i < $numfields; $i++){
									if(is_string($row[$i])){
										$str = sqlite_escape_string($row[$i]);
									}else{
										$str = $row[$i];
									}
									$tabledump .= $comma."'".$str."'";
									$comma = ",";
								}
								$tabledump .= ");";
								$error = sqlite_dbquery($to_db,$tabledump);
								if($error){
									$db_error .= $error.'<br>';
								}
							}
						}
						if(!$db_error){
							$do_db = true;
						}else{
							$message .= $db_error;
						}
					break;
				}
		}
		if($do_db){
			$db_str = "<?php \n";
			$db_str .= '$database_type = \'sqlite\';'."\n";
			$db_str .= '$db_left = \''.$to_db_left.'\';'."\n";
			$db_str .= '$db_name = \''.$to_db_name.'\';'."\n";
			$db_str .= '$sqlite_driver = \''.$sqlite_driver.'\';'."\n";
			$db_str .= "?>";		
			file_put_contents(SITE_HOME.'inc/db.php',$db_str);
			if(DATABASE_TYPE=='sqlite'&&$to_db_name!=$db_name){
				$db = null;
				unlink(SITE_HOME.'inc/'.$db_name.'.db');
			}
			alert(DATABASE_CONVERT_SUCCESSFULLY,'./');
		}	
	}else{
		$message = NEED_FORM_DATA;
	}
?>