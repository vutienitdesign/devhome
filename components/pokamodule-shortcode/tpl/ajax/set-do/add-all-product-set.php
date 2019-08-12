<?php
	$aData   = array();
	$termID  = $_POST['term_id'];
	$dataAll = $_POST['data_all'];
	$msgTest = '';
	if($termID > 0){
		$args = array(
			'post_status' => array('publish'),
			'post_type' => array('product'),
			'posts_per_page' => -1,
			'tax_query' => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'product_tag',
					'field' => 'id',
					'terms' => array($termID),
				),
			),
		);
		$the_query = new WP_Query($args);
		if($the_query->have_posts()):
			while($the_query->have_posts() ) : $the_query->the_post();
				$idProduct = get_the_ID();
				$idSmall   = get_post_meta($idProduct, 'decorate_small', true);
				if(!empty($idSmall)){
					$product  = wc_get_product($idProduct);
					$sTitle   = get_the_title();
					$sLink    = get_permalink();
					$dataID   = $idSmall;
					$imageUrl = wp_get_attachment_image_src(get_post_thumbnail_id($idProduct))[0];
					$idRandom = PMCommon::generateRandomString('5');
					
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
			                            <i class="fa fa-edit edit-product-set custom-data-id" data-id="'.$dataID.'" data-temp="'.$idRandom.'" data-product="'.$idProduct.'" data-small="'.$dataID.'" data-all="'.$dataAll.'" data-term="'.$termID.'"></i>
			                           <span class="tooltiptext">Sửa</span>
			                       </div>
			                       <div class="iart-tooltip">
			                            <i class="fa fa-trash remove-product custom-data-id" data-id="'.$dataID.'" data-product="'.$idProduct.'" data-small="'.$dataID.'"></i>
			                           <span class="tooltiptext">Xóa</span>
			                       </div>
			                    </td>
			                </tr>';
					
					$aData[] = array(
						'data'      => $sHtml,
						'dataID'    => $dataID,
						'idproduct' => $idRandom,
						'productID' => $idProduct,
					);
				}
			endwhile;
		endif;
		wp_reset_postdata();
	}
	
	echo json_encode(
		array(
			'data' => $aData,
			'msg' => $msgTest
		)
	);