<?php
	global $wpdb, $pokaSession;
    
    $sPage   = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
	
	//Load css + js
	
	PMCommon::load_lib_components($sPage . '/', $sPage . '-add-edit.js', 'js');
	PMCommon::load_lib_components($sPage . '/', $sPage . '-add-edit.min.css', 'css');
	
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
	
	$vName     = '';
	$vNameShow = '';
	
	$sTitle         = '';
	
	$sUrl              = admin_url() . 'admin.php?page=' . $sPage;
	
    if($vAction == 'edit'){
	    $table = $wpdb->prefix . 'decorate_small';
	    $sql   = 'SELECT * FROM ' . $table . ' WHERE id=' . $_GET['article'];
	    $data  = $wpdb->get_row($sql, ARRAY_A);
	
	    $vName     = $data['name'];
	    $vNameShow = $data['name_show'];
	
	    if(empty($data)){
		    $url = 'admin.php?page=' . $_REQUEST['page'];
		    wp_redirect($url);
		    exit();
	    }
	    
	    $sTitle = 'Chỉnh sửa không gian nhỏ';
    }else{
	    $sTitle = 'Thêm mới không gian nhỏ';
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
		
			wp_nonce_field($actionNone,'security_code',true);
        ?>
		
		<table class="form-table poka-form-table">
			<tbody>
                <tr>
                    <th>Name</th>
                    <td>
                        <input name="poka[name]" type="text" value="<?php echo $vName; ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th>Category Woocommerce</th>
                    <td>
                        <?php
	                        $args = array(
		                        'show_option_all' => '',
		                        'taxonomy'        => 'product_cat',
		                        'selected'        => $data['cat'],
		                        'hide_empty'      => 0,
		                        'hierarchical'    => 1,
                                'name' => 'poka[cat]',
                                'class' => 'regular-text'
                            );
	                        wp_dropdown_categories($args);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Không gian lớn</th>
                    <td>
		                <?php
			                $sql = "SELECT `id`, `name` FROM `{$wpdb->prefix}decorate_large`";
			                $arrCat = $wpdb->get_results($sql);
			                $sHtml = '';
			                foreach($arrCat as $v){
				                if($v->id == $data['decorate_large']){
					                $sHtml .= '<option selected="selected" value="'.$v->id.'">'.$v->name.'</option>';
				                }else{
					                $sHtml .= '<option value="'.$v->id.'">'.$v->name.'</option>';
				                }
			                }
		                ?>
                        <select name="poka[decorate_large]" class="decorate-large regular-text">
                            <option value="">== Chọn không gian lớn ==</option>
			                <?php echo $sHtml; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Không gian vừa</th>
                    <td class="box-decorate-medium">
		                <?php
			                $sHtml = '';
			                if($vAction == 'edit'){
				                $sql = "SELECT `id`, `name` FROM `{$wpdb->prefix}decorate_medium`";
				                $arrCat = $wpdb->get_results($sql);
				                foreach($arrCat as $v){
					                if($v->id == $data['decorate_medium']){
						                $sHtml .= '<option selected="selected" value="'.$v->id.'">'.$v->name.'</option>';
					                }else{
						                $sHtml .= '<option value="'.$v->id.'">'.$v->name.'</option>';
					                }
				                }
			                }
			                
		                ?>
                        <i class="fa fa-spinner fa-spin" style="display: none"></i>
                        <select name="poka[decorate_medium]" class="decorate-medium regular-text">
                            <option value="">== Chọn không gian vừa ==</option>
			                <?php echo $sHtml; ?>
                        </select>
                    </td>
                </tr>
			</tbody>
		</table>
		
		<p class="submit">
			<input name="submit" id="submit" class="button button-primary" value="Lưu lại" type="submit">
		</p>
	</form>
</div>
