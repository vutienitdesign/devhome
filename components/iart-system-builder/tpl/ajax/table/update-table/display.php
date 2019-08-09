<?php
	$vID = intval($_REQUEST['row-edit']);
	
	$vFlag = true;
	
	global $wpdb;
	$tbl    = $wpdb->prefix . 'decorate_large';
	
	$vMsg = "";
	$vValue = '';
	$vData = $_REQUEST['row-data'];
	if(!empty($vData)){
		foreach($vData as $key => $value){
			if($key == 'poka_name'){
				$vValue = sanitize_text_field($value);
				
				if(empty($vValue)){
					$vFlag = false;
					$vMsg = 'Tên không được rỗng!';
				}
				
				break;
			}
		}
	}
	
	if($vFlag == true){
		$where = array('id'=> $vID);
		$where_format = array('%d');
		$data = array(
			'name' => $vValue,
		);
		$wpdb->update($tbl, $data, $where,$format,$where_format);
		
		$arr = array(
			'type' => 'success',
			'value' => array(
				'poka_name'	=> $vValue
			)
		);
	}else{
		$arr = array(
			'type' => 'error',
			'value' => $vMsg
		);
	}
	
	echo json_encode($arr);