<?php
	$idProduct = $_POST['product_id'];
	$dataID    = $_POST['data_id'];
	$dataTerm  = $_POST['data-term'];
	$product   = wc_get_product($idProduct);
	
	$sTitle   = $product->get_title();
	$imageUrl = wp_get_attachment_image_src(get_post_thumbnail_id($idProduct))[0];
	$idRandom = PMCommon::generateRandomString('5');
	
	$sHtml = '';
	if(!empty($product)){
		$stock = $product->get_stock_status();
		if($stock == 'instock'){
			$stock = 'Còn hàng';
		}else{
			$stock = 'Hết hàng';
		}
		
		$sHtml = '<tr data-id="'.$dataID.'" data-product="'.$idProduct.'" class="data-product product-id-'.$idProduct.' product-temp-'.$idRandom.'">
                    <td><img class="img-product" src="'.$imageUrl.'" alt="'.$sTitle.'"></td>
                    <td>
                    	<a href="'.$product->get_permalink().'" target="_blank">'.$sTitle.'</a>
                    	<div class="info">
                    		<ul>
                    			<li><span>Mã sản phẩm:</span> '.$product->get_sku().'</li>
                    			<li><span>Kho hàng:</span> '.$stock.'</li>
							</ul>
						</div>
                    </td>
                    <td>'.wc_price($product->get_price()).'</td>
                    <td><input type="number" class="input-text soluong" value="1" min="1" data-id="'.$dataID.'" data-product="'.$idProduct.'" data-price="'.$product->get_price().'"></td>
                    <td class="html-price">'.wc_price($product->get_price()).'</td>
                    <td class="action">
                       <div class="iart-tooltip">
                            <i class="fa fa-edit edit-product" data-id="'.$dataID.'" data-term="'.$dataTerm.'" data-temp="'.$idRandom.'"></i>
                           <span class="tooltiptext">Sửa</span>
                       </div>
                       <div class="iart-tooltip">
                            <i class="fa fa-trash remove-product" data-id="'.$dataID.'" data-product="'.$idProduct.'"></i>
                           <span class="tooltiptext">Xóa</span>
                       </div>
                    </td>
                </tr>';
		
		//Luu san pham vao Session
		global $woocommerce, $pokaHelper;
		$tabCurrent  = $_POST['tabcurrent'];
		$pokaHelper->saveProductSession($tabCurrent, $dataID, $idProduct);
		
		$woocommerce->cart->add_to_cart($idProduct);
	}
	
	echo json_encode(
		array(
			'data' => $sHtml,
			'msg' => ''
		)
	);