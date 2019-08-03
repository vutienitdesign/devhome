<?php
	global $wpdb, $pokaSession;
	$sPrefix = $wpdb->prefix;
	$tbl = $sPrefix . 'decorate_medium';
	$tblSmall = $sPrefix . 'decorate_small';
	
	$article_id = @$_REQUEST['article'];
	
	if(!is_array($_REQUEST['article'])){
		$name   = 'security_code';
		$action = 'delete_id_' . $_GET['article'];
		
		if(!isset($_GET[$name]) || empty($_GET[$name]) || !check_admin_referer($action,$name)){
		}else{
			$where 			= array('id' => $article_id);
			$where_format 	= array('%d');
			$wpdb->delete($tbl, $where,$where_format);
			
			//Xoa Table decorate_small
			$where 			= array('decorate_medium' => $article_id);
			$wpdb->delete($tblSmall, $where,$where_format);
		}
	}else{
		$ids = join(',', $_REQUEST['article']);
		$sql = 'DELETE FROM ' . $tbl . ' WHERE id IN ('. $ids . ')';
		$wpdb->query($sql);
		
		//Xoa decorate_small
		$sql = 'DELETE FROM ' . $tblSmall . ' WHERE `decorate_medium` IN ('. $ids . ')';
		$wpdb->query($sql);
	}
	
	$pokaSession->set('msg', array(
		'type' => 'success',
		'msg'  => 'Delete success!',
	));
	
	$url = 'admin.php?page=' . $_REQUEST['page'];
	wp_redirect($url);
	exit();