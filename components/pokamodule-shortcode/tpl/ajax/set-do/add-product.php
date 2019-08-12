<?php
	$idProduct     = $_POST['product_id']; //Sản phẩm muốn thay đổi
	$idSmall       = get_post_meta($idProduct, 'decorate_small', true);
	
	$sHtml = '';
	if(!empty($idSmall)){
		$dataAll = $_POST['data_all'];
		$termID = $_POST['term_id'];
		
		$product   = wc_get_product($idProduct);
		$dataID    = $_POST['data_id'];
		
		$sTitle   = $product->get_title();
		$imageUrl = wp_get_attachment_image_src(get_post_thumbnail_id($idProduct))[0];
		$idRandom = PMCommon::generateRandomString('5');
		
		if(!empty($product)){
			$sHtml = '<tr data-id="'.$dataID.'" data-product="'.$idProduct.'" class="data-product product-id-'.$idProduct.' product-temp-'.$idRandom.'" data-type="set">
                    <td><img class="img-product" src="'.$imageUrl.'" alt="'.$sTitle.'"></td>
                    <td>
                    	<a href="'.$product->get_permalink().'" target="_blank">'.$sTitle.'</a>
                    	<div class="info">
                    		<ul>
                    			<li><span>Mã sản phẩm:</span> '.$product->get_sku().'</li>
							</ul>
						</div>
                    </td>
                    <td>'.wc_price($product->get_price()).'</td>
                    <td><input type="number" class="input-text soluong custom-data-id" value="1" min="1" data-id="'.$dataID.'" data-product="'.$idProduct.'" data-price="'.$product->get_price().'" data-type="set" data-tag="'.$termID.'|'.$dataAll.'"></td>
                    <td class="html-price">'.wc_price($product->get_price()).'</td>
                    <td class="action">
                       <div class="iart-tooltip">
                            <i class="fa fa-edit edit-product-set custom-data-id" data-id="'.$dataID.'" data-temp="'.$idRandom.'" data-product="'.$idProduct.'" data-small="'.$idSmall.'" data-all="'.$dataAll.'" data-term="'.$termID.'"></i>
                           <span class="tooltiptext">Sửa</span>
                       </div>
                       <div class="iart-tooltip">
                            <i class="fa fa-trash remove-product custom-data-id" data-id="'.$dataID.'" data-product="'.$idProduct.'" data-small="'.$idSmall.'"></i>
                           <span class="tooltiptext">Xóa</span>
                       </div>
                    </td>
                </tr>';
		}
	}
	
	echo json_encode(
		array(
			'data'      => $sHtml,
			'dataID'    => $idSmall,
			'idproduct' => $idRandom,
			'msg'       => ''
		)
	);