<?php
	global $wpdb, $pokaSession;
	$tbl = $wpdb->prefix . 'decorate_medium';
	
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
	
	$sNameShow = sanitize_text_field($_POST['poka']['name_show']);
	if(empty($sNameShow)){
		$pokaSession->set('msg', array(
			'type' => 'error',
			'msg'  => 'Tên hiện thị không được rỗng!',
		));
		
		wp_redirect($urlRedirect);
		exit();
	}
	
	if(empty($_POST['tag_woo'])){
		$pokaSession->set('msg', array(
			'type' => 'error',
			'msg'  => 'Vui lòng lựa chọn Tags Woocommerce!',
		));
		
		wp_redirect($urlRedirect);
		exit();
	}
	
	$data = array(
		'decorate_large' => intval($_POST['poka']['decorate_id']),
		'name'        => $sName,
		'name_show'   => $sNameShow,
		'tag'   => $_POST['tag_woo'],
	);
	$format =  array('%d','%s','%s','%s');
	
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