<?php
	if(!session_id()){
		session_start();
	}
	
	global $wpdb;
	$tbl = $wpdb->prefix . 'log_click_build';
	
	$arr = array(
		'view-demo',
		'contact-support',
		'download-pdf',
		'add-cart',
	);
	
	$dataType = $_POST['data_type'];
	if(in_array($dataType, $arr)){
		$idTemp = $_SESSION['iart_config_product']['id_temp'];
		
		if(!empty($idTemp)){
			//Update Database
			global $wpdb;
			$tbl = $wpdb->prefix . 'log_click_build';
			$nTime = _REAL_TIME_;
			$sql = "UPDATE `{$tbl}` SET `total` = `total` + 1, `date_update` = {$nTime}  WHERE `id_temp` = '{$idTemp}' AND `type` = '{$dataType}'";
			$wpdb->query($sql);
		}
	}