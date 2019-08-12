<?php
	global $wpdb, $pokaSession;
    
    $sPage   = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
	
	//Load css + js
	
//	PMCommon::load_lib_components($sPage . '/', $sPage . '-add-edit.js', 'js');
	PMCommon::load_lib_components($sPage . '/', $sPage . '-view.min.css', 'css');
	
	$vAction = $_REQUEST['action'];
	
	
    $table = $wpdb->prefix . 'manager_builder_product';
    $sql   = 'SELECT * FROM ' . $table . ' WHERE id=' . $_GET['article'];
    $data  = $wpdb->get_row($sql, ARRAY_A);
    
    $vName          = $data['name'];

    if(empty($data)){
        $url = 'admin.php?page=' . $sPage;
        wp_redirect($url);
        exit();
    }
    $sTitle = 'Thông tin chi tiết cấu hình';
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
		
        <?php
	        $userID   = $data['user_id'];
	        $userData = get_userdata($userID);
	        $urlImage = _POKA_PLUGIN_URL_ . 'images/builder-product/' . $userID . '/';
        ?>
		<table class="form-table poka-form-table">
			<tbody>
                <tr>
                    <th>Thông tin User</th>
                    <td class="box-group info-user">
                        <p><span>Họ Tên: </span> <?php echo $userData->first_name . ' ' . $userData->last_name; ?></p>
                        <p><span>Email: </span> <?php echo $userData->user_email; ?></p>
                    </td>
                </tr>
                <tr>
                    <th>Thời gian</th>
                    <td class="box-group info-date">
                        <p><span>Ngày tạo: </span> <?php echo date('d/m/Y h:i:s', $data['date']); ?></p>
                        <p><span>Ngày sửa: </span> <?php echo date('d/m/Y h:i:s', $data['date_update']); ?></p>
                    </td>
                </tr>
                <tr>
                    <th>Sản phẩm</th>
                    <td class="box-group info-product">
                        <?php
	                        $nameLarge = '';
	                        if(!empty($data['data'])){
		                        $aData = unserialize($data['data']);
		                        $sPrefix = $wpdb->prefix;
		                        
		                        $sql = "SELECT `name` FROM `{$sPrefix}decorate_large` WHERE `id` = {$aData['step1']}";
		                        $nameLarge = $wpdb->get_row($sql)->name;
		
		                        $sHtml = '';
		                        if(!empty($aData['step3'])){
		                            foreach($aData['step3'] as $k => $v){
		                                $sHtml .= '<div class="data-item"><h3 class="title-medium">- '.$aData['step3-config'][$k]['config']['name'].'</h3>
                                                    <table class="table-medium">
                                                        <tr>
                                                            <th class="name-small">Tên Phòng</th>
                                                            <th class="image">Hình ảnh</th>
                                                            <th class="product">Sản phẩm</th>
                                                            <th class="price">Giá</th>
                                                            <th class="amount">Số lượng</th>
                                                        </tr>';
			
			                            $sHtmlTr = '';
			                            $sHtmlCustom = '';
		                                foreach($v as $kPhong => $vPhong){
		                                    if($kPhong != 'custom-info'){
			                                    if(!empty($vPhong)){
				                                    $nTotalProduct = count($vPhong);
				
				                                    $i = 0;
				                                    foreach($vPhong as $vProduct){
					                                    if(!empty($vProduct)){
						                                    $product         = wc_get_product($vProduct['id']);
						                                    if(!empty($product)){
							                                    $imageUrl = wp_get_attachment_image_src(get_post_thumbnail_id($vProduct['id']))[0];
							
							                                    if($i == 0){
								                                    $sHtmlTD = '<td class="name-small" rowspan="'.$nTotalProduct.'">'.$aData['step3-config'][$k]['data'][$kPhong]['name'].'</td>';
							                                    }else{
								                                    $sHtmlTD = '';
							                                    }
							                                    $sHtmlTr .= '<tr>
                                                                        '.$sHtmlTD.'
                                                                        <td><img class="image-product" src="'.$imageUrl.'" /></td>
                                                                        <td class="product">
                                                                            <a href="'.$product->get_permalink().'" target="_blank">'.$product->get_title().'</a>
                                                                        <div class="info">
                                                                                <ul>
                                                                                    <li><span>Mã sản phẩm:</span> '.$product->get_sku().'</li>
                                                                                </ul>
                                                                            </div>
                                                                        </td>
                                                                        <td>'.wc_price($product->get_price()).'</td>
                                                                        <td>'.$vProduct['quantity'].'</td>
                                                                    </tr>';
						                                    }
						
						                                    $i++;
					                                    }
				                                    }
			                                    }
                                            }
			
			                                if($kPhong == 'custom-info'){
			                                    foreach($vPhong as $vCustomInfo){
			                                        if(!empty($vCustomInfo['info']) || !empty($vCustomInfo['url']) || !empty($vCustomInfo['image'])){
				                                        $sHtmlCustom .= '<div class="item-custom">
                                                                            <p><span>Tên sản phẩm:</span> '.$vCustomInfo['info'].'</p>
                                                                            <p><span>Đường dẫn sản phẩm:</span> '.$vCustomInfo['url'].'</p>
                                                                            <p><span>Hình ảnh sản phẩm:</span> <a href="'.$urlImage . $vCustomInfo['image'].'" target="_blank"><img class="image" src="'.$urlImage . $vCustomInfo['image'].'"></a></p>
                                                                        </div>';
                                                    }
                                                }
			                                }
                                        }
		                                
		                                if(!empty($sHtmlCustom)){
			                                $sHtmlCustom = '<tr>
                                                                <td rowspan="2">Sản phẩm mở rộng</td>
                                                                <td colspan="4" class="custom-product">'.$sHtmlCustom.'</td>
                                                            </tr>';
                                        }
		                                
			                           
			                            $sHtml .= $sHtmlTr . $sHtmlCustom . '</table></div>';
                                    }
                                }
		
		
		                        $sHtmlMedium = '';
		                        if(!empty($aData['step3-config'])){
			                        foreach($aData['step3-config'] as $v){
				                        $sHtmlMedium .= ', ' . $v['config']['name'];
			                        }
			                        $sHtmlMedium = ltrim($sHtmlMedium, ', ');
			                        $sHtmlMedium = '<h3 class="title-large"><span>Không gian vừa:</span> '.$sHtmlMedium.'</h3>';
                                }
	                        }
                        ?>
                        <h3 class="title-large"><span>Tên dự án:</span> <?php echo $data['name']; ?></h3>
                        <h3 class="title-large"><span>Không gian lớn:</span> <?php echo $nameLarge; ?></h3>
                        <?php echo $sHtmlMedium; ?>
                        <div class="data">
                            <?php
                                echo $sHtml;
                            ?>
                        </div>
                        
                    </td>
                </tr>
			</tbody>
		</table>
		
		<!--<p class="submit">
			<input name="submit" id="submit" class="button button-primary" value="Lưu lại" type="submit">
		</p>-->
	</form>
</div>
