<?php
	global $wpdb, $pokaSession;
    
    $sPage   = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
	
	//Load css + js
	
//	PMCommon::load_lib_components($sPage . '/', $sPage . '-add-edit.js', 'js');
//	PMCommon::load_lib_components($sPage . '/', $sPage . '-add-edit.min.css', 'css');
	
	$vAction = $_REQUEST['action'];
	
	//Save Data
	if(isset($_POST['poka']['name'])){
		if($vAction == 'add'){
			$actionNone = 'add';
		}else{
			$actionNone = 'edit_id_' . $_GET['article'];
		}
		
		if(!check_admin_referer($actionNone, 'security_code')){
		}else{
		    require_once "save-data.php";
		}
	}
	
	$vName          = '';
	
	$sTitle         = '';
	
	$sUrl              = admin_url() . 'admin.php?page=' . $sPage;
	
    $vAction = $_GET['action'];
    if($vAction == 'edit'){
	    $table = $wpdb->prefix . 'decorate_large';
	    $sql   = 'SELECT * FROM ' . $table . ' WHERE id=' . $_GET['article'];
	    $data  = $wpdb->get_row($sql, ARRAY_A);
	    
	    $vName          = $data['name'];
	
	    if(empty($data)){
		    $url = 'admin.php?page=' . $_REQUEST['page'];
		    wp_redirect($url);
		    exit();
	    }
	    
	    $sTitle = 'Chỉnh sửa không gian lớn';
    }else{
	    $sTitle = 'Thêm mới không gian lớn';
    }
    
?>
<div class="wrap">
    <h1 class="wp-heading-title"><?php echo $sTitle; ?></h1>
    
	<?php
		
		$getMsg = $pokaSession->get('msg');
		if(!empty($getMsg)){
			echo PMCommon::getMsgBE(array($getMsg['msg']), $getMsg['type']);
			$pokaSession->delete('msg');
		}
    ?>
	<form method="post" action="" id="<?php echo $sPage;?>" enctype="multipart/form-data">
		<input name="action" value="<?php echo $vAction;?>" type="hidden">
		<?php
			if($vAction == 'edit'){
				$actionNone = 'edit_id_' . $_REQUEST['article'];
			}else{
				$actionNone = 'add';
            }
		?>
		<?php wp_nonce_field($actionNone,'security_code',true);?>
		
		<table class="form-table poka-form-table">
			<tbody>
                <tr>
                    <th scope="row">Name</th>
                    <td>
                        <input name="poka[name]" type="text" value="<?php echo $vName; ?>" class="regular-text">
                    </td>
                </tr>
			</tbody>
		</table>
		
		<p class="submit">
			<input name="submit" id="submit" class="button button-primary" value="Lưu lại" type="submit">
		</p>
	</form>
</div>
