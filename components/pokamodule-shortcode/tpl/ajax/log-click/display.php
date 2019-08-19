<?php
	if(!session_id()){
		session_start();
	}
	
	global $wpdb;
	$tbl = $wpdb->prefix . 'log_click_build';
	$nTime = _REAL_TIME_;
	
	$dataType = $_POST['data_type'];
	$idTemp   = $_SESSION['iart_config_product']['id_temp'];
	
	if($dataType == 'download-pdf-medium'){
		$tab = sanitize_text_field($_POST['tab']);
		$sName = $_SESSION['iart_config_product']['step3-config'][$tab]['config']['name'];
		if(!empty($sName)){
			$sql = "SELECT `id` FROM `{$tbl}` WHERE `type` = '{$sName}'";
			
			$result = $wpdb->get_row($sql);
			if(empty($result)){
				//Insert Log
				$data = array(
					'id_temp'     => $idTemp,
					'total'       => 1,
					'type'        => $sName,
					'date_update' => $nTime,
				);
				$format =  array('%s','%d','%s','%d');
				$wpdb->insert($tbl, $data,$format);
			}else{
				$dataType = $sName;
				$sql = "UPDATE `{$tbl}` SET `total` = `total` + 1, `date_update` = {$nTime}  WHERE `id_temp` = '{$idTemp}' AND `type` = '{$dataType}'";
				$wpdb->query($sql);
			}
		}
	}else{
		$arr = array(
			'view-demo',
			'contact-support',
			'download-pdf',
			'add-cart',
		);
		
		if(in_array($dataType, $arr)){
			if(!empty($idTemp)){
				//Update Database
				$sql = "UPDATE `{$tbl}` SET `total` = `total` + 1, `date_update` = {$nTime}  WHERE `id_temp` = '{$idTemp}' AND `type` = '{$dataType}'";
				$wpdb->query($sql);
			}
		}
	}
	