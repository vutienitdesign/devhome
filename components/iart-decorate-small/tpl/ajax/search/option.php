<?php
	global $wpdb;
	
	$id     = $_POST['decorate_large'];
	$sql    = "SELECT `id`, `name` FROM `{$wpdb->prefix}decorate_medium` WHERE `decorate_large` = {$id}";
	$reuslt = $wpdb->get_results($sql);
	
	$sHtml = '<option value="">== Chọn không gian vừa ==</option>';
	if(!empty($reuslt)){
		foreach($reuslt as $v){
			$sHtml .= '<option value="'.$v->id.'">'.$v->name.'</option>';
		}
	}
	
	echo json_encode(array(
		'data' => $sHtml
	));