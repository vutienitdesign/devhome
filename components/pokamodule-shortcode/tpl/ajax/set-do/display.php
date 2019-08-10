<?php
	$sHtml = '';
	
	$term_id = $_POST['term_id'];
	if($term_id > 0){
		global $pokaHelper;
		$sHtml = $pokaHelper->getDataSet3SetDo($term_id);
	}
	
	global $wpdb;
	$sql    = "SELECT `name` FROM `{$wpdb->prefix}decorate_medium` WHERE `id` = {$term_id}";
	$sNameSet = $wpdb->get_row($sql, ARRAY_A);
	if(!empty($sNameSet)){
		$sNameSet = $sNameSet['name'];
	}else{
		$sNameSet = 'sét đồ';
	}
	
	echo json_encode(
		array(
			'data' => $sHtml,
			'title' => 'Mẫu ' . $sNameSet,
			'msg' => ''
		)
	);
 