<?php
	$sHtml   = '';
	$msgTest = '';
	
	$option = isset($_POST['option']) ? $_POST['option'] : array();
	$aOption = array();
	if(!empty($option)){
		foreach($option as $v){
			foreach($v as $kTmp => $vTmp){
				$aOption[$kTmp] = $vTmp;
			}
		}
	}
	
	$typeProduct = (isset($aOption['type-proudct']) && $aOption['type-proudct'] == 'edit') ? 'edit' : 'add';
	$idProductChange = isset($aOption['product-id']) ? $aOption['product-id'] : ''; //ID Sản phẩm update (thay đổi)
	
	$productExit = $_POST['product_exist']; // Cac san pham da lua chon
	$productExit = ltrim($productExit, ',');
	$aProductExit = array();
	if(!empty($productExit)){
		$aProductExit = explode(',', $productExit);
	}
	
	$term_id = intval($_POST['term_id']); //ID Tags Term Active
	if($term_id > 0){
		$allData = $_POST['all_data'];
		
		$aData = $allData;
		if(!empty($aData)){
			$aData = explode(',', $aData);
		}
		
		$sTitleTag       = '';
		$sHtmlTab     = '';
		$sHtmlProduct = '';
		
		$i = 0;
		foreach($aData as $v){
			$terms = get_term_by('id', $v, 'product_tag' );
			if(!empty($terms)){
				if($term_id == $v){
					$sTitleTag = $terms->name;
					$sActive = 'active';
					
					$args = array(
						'post_status' => array('publish'),
						'post_type' => array('product'),
						'posts_per_page' => -1,
						'tax_query' => array(
							'relation' => 'AND',
							array(
								'taxonomy' => 'product_tag',
								'field' => 'id',
								'terms' => array($v),
							),
						),
					);
					
					$the_query = new WP_Query($args);
					if($the_query->have_posts()):
						while($the_query->have_posts() ) : $the_query->the_post();
							$id       = get_the_ID();
							$idSmall = get_post_meta($id, 'decorate_small', true);
							if(empty($idSmall)){
								continue;
							}
							
							$sTitle   = get_the_title();
							$sLink    = get_permalink();
							$imageUrl = wp_get_attachment_image_src(get_post_thumbnail_id($id))[0];
							
							$product = wc_get_product($id);
							$sClassActive = '';
							$sTextActive = '';
							if(in_array($id, $aProductExit)){
								$sClassActive = 'activate-choose';
								$sTextActive = '<p class="text-active"><i class="fa fa-check"></i> Sản phẩm đã lựa chọn</p>';
							}
							
							$sHtmlProduct .= ' <div class="items">
 												<div class="item '.$sClassActive.'">
					                            <div class="image">
													<a href="'.$sLink.'" target="_blank"><img src="'.$imageUrl.'" alt="'.$sTitle.'"></a>
												</div>
												<div class="info">
													<h4 class="title"><a target="_blank" href="'.$sLink.'">'.$sTitle.'</a></h4>
													<ul>
														<li><span class="lbl">Mã sản phẩm: </span>'.$product->get_sku().'</li>
														<li>
															<div class="price">'.$product->get_price_html().'</div>
														</li>
													</ul>
												</div>
					                            <div class="action">
					                            	'.$sTextActive.'
					                            	<button type="button" value="'.$id.'" class="button add-config" data-type="'.$typeProduct.'" data-id="'.$idSmall.'" data-all="'.$allData.'" data-term="'.$v.'" data-product-edit="'.$idProductChange.'">Thêm vào cấu hình</button>
												</div>
					                        </div></div>';
						endwhile;
					endif;
					wp_reset_postdata();
				}else{
					$sActive = '';
				}
				
				$sHtmlTab .= '<li class="acce_tab '.$sActive.'" data-active="'.$sActive.'" data-tab="'.$allData.'" value="'.$v.'"><a>'.$terms->name.'</a></li>';
				
				$i++;
			}
		}
		
		$sHtmlAll = '<li class="acce_tab_all"><a class="button choose-all-set" data-term="'.$term_id.'" data-all="'.$allData.'">Chọn tất cả</a></li>';
		if(empty($sHtmlProduct)){
			$sHtmlProduct = '<p>Không có sản phẩm</p>';
			$sHtmlAll     = '';
		}
		
		$sHtml = '<div class="modal-content box-set-product-vew">
		            <div class="modal-header">
		                <span class="close">×</span>
		                <h3 class="title">'.$sTitleTag.'</h3>
		            </div>
		            <div class="modal-body">';
		
		$sHtml .= '<ul class="ec-tabs">
	                    '.$sHtmlTab.'
	                    '.$sHtmlAll.'
	                </ul>
	                <div class="content">
                        '.$sHtmlProduct.'
	                </div>
	                <div class="clear"></div>
	            </div>
	        </div>';
	}
	
	echo json_encode(
		array(
			'data' => $sHtml,
			'msg' => $msgTest
		)
	);