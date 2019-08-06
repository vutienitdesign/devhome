<?php
	global $wpdb, $pokaSession;
	
	PMCommon::load_lib_assets('semantic-ui/', 'semantic.min.js', 'js');
	PMCommon::load_lib_assets('semantic-ui/', 'semantic.min.css', 'css');
    
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
	$nMax      = 2;
	
	$sTitle         = '';
	
	$sUrl              = admin_url() . 'admin.php?page=' . $sPage;
	
    $vAction = $_GET['action'];
    if($vAction == 'edit'){
	    $table = $wpdb->prefix . 'decorate_medium';
	    $sql   = 'SELECT * FROM ' . $table . ' WHERE id=' . $_GET['article'];
	    $data  = $wpdb->get_row($sql, ARRAY_A);
	
	    $vName     = $data['name'];
	    $vNameShow = $data['name_show'];
	    $nMax      = $data['max'];
	
	    if(empty($data)){
		    $url = 'admin.php?page=' . $_REQUEST['page'];
		    wp_redirect($url);
		    exit();
	    }
	    
	    $sTitle = 'Chỉnh sửa không gian vừa';
    }else{
	    $sTitle = 'Thêm mới không gian vừa';
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
                        <select name="poka[decorate_id]" class="regular-text">
                            <?php echo $sHtml; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>
                        <input name="poka[name]" type="text" value="<?php echo $vName; ?>" class="regular-text">
                        <p class="description">Ví dụ: Phòng ngủ</p>
                    </td>
                </tr>
                <tr>
                    <th>Name Show</th>
                    <td>
                        <input name="poka[name_show]" type="text" value="<?php echo $vNameShow; ?>" class="regular-text">
                        <p class="description">Ví dụ: Có bao nhiêu phòng ngủ?</p>
                    </td>
                </tr>
                <tr>
                    <th>Tối đa</th>
                    <td>
                        <input name="poka[max]" type="number" value="<?php echo $nMax; ?>" class="regular-text">
                        <p class="description">Số lượng tối đa cho không gian vừa này. Mặc đinh: 2</p>
                    </td>
                </tr>
                <tr>
                    <th>Tags Woocommerce</th>
                    <td class="data-tag">
	                    <?php
		                    $sIDDesigned = '';
		                    if(isset($data['tag']) && !empty($data['tag'])){
			                    $sIDDesigned = $data['tag'];
		                    }
		                    
		                    $args = array(
			                    'taxonomy'     => 'product_tag',
			                    'orderby'      => 'name',
			                    'show_count'   => 0,
			                    'pad_counts'   => 0,
			                    'hierarchical' => 1,
			                    'title_li'     => '',
			                    'hide_empty'   => 0
		                    );
		                    $arrCat = get_categories( $args );
		                    $sDesigned = '';
		                    foreach($arrCat as $v){
			                    $sDesigned .= '<div class="item" data-value="'.$v->term_id.'">'.$v->name.'</div>';
		                    }
	                    ?>
                        <div class="ui fluid multiple search selection dropdown dropdown-designed">
                            <input type="hidden" name="tag_woo">
                            <i class="dropdown icon"></i>
                            <div class="default text">Tags Woocommerce</div>
                            <div class="menu">
			                    <?php echo $sDesigned; ?>
                            </div>
                        </div>
                    </td>
                </tr>
			</tbody>
		</table>

        <script type="text/javascript">
            var sIDDesigned = '<?php echo $sIDDesigned; ?>';
        </script>
		<p class="submit">
			<input name="submit" id="submit" class="button button-primary" value="Lưu lại" type="submit">
		</p>
	</form>
</div>
