<?php
	global $wpdb, $pokaSession;
	$tbl = $wpdb->prefix . 'decorate_large';
	
	$urlRedirect = $_REQUEST['_wp_http_referer'];
	
	$sName = sanitize_text_field($_POST['poka']['name']);
	if(empty($sName)){
		$pokaSession->set('msg', array(
			'type' => 'error',
			'msg'  => 'Tên không được rỗng!',
		));
		
		wp_redirect($urlRedirect);
		exit();
	}
	
	$data = array(
		'name'       => $sName,
	);
	$format =  array('%s');
	
	if($action == 'add'){
		$wpdb->insert($tbl, $data,$format);
		
		$pokaSession->set('msg', array(
			'type' => 'success',
			'msg'  => 'Thêm mới thành công!',
		));
	}else{
		$nID = isset($_GET['article']) ? $_GET['article'] : '';
		
		if($nID > 0){
			$where = array('id'=> $nID);
			$where_format = array('%d');
			$wpdb->update($tbl, $data, $where,$format,$where_format);
			
			$pokaSession->set('msg', array(
				'type' => 'success',
				'msg'  => 'Chỉnh sửa thành công!',
			));
		}else{
			$pokaSession->set('msg', array(
				'type' => 'error',
				'msg'  => 'An error has occurred please try again later!',
			));
		}
		
	}
	
	wp_redirect($urlRedirect);
	exit();