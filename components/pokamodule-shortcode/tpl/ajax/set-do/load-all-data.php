<?php
	$dataTerm = $_POST['data_term'];
	$aData = array();
	if(!empty($dataTerm)){
		$aData = explode(',', $dataTerm);
	}
	
	$html = '';
	if(!empty($aData)){
		$typeProduct     = 'add';
		$idProductChange = ''; //ID Sản phẩm update (thay đổi)
		
		$productExit  = $_POST['product_exist']; // Cac san pham da lua chon
		$productExit  = ltrim($productExit, ',');
		$aProductExit = array();
		if(!empty($productExit)){
			$aProductExit = explode(',', $productExit);
		}
		
		$sHtml = '<div class="modal-content box-set-product-vewall">
					<div class="modal-header">
						<span class="close">×</span>
						<h3 class="title">'.count($aData).' Bộ Sưu Tập Phòng Ngủ Đang Có Sẵn</h3>
					</div>
					<div class="modal-body">
						<div class="content-data">';
		
		foreach($aData as $term_id){
			$terms = get_term_by('id', $term_id, 'product_tag' );
			if(!empty($terms)){
				$sHtmlProduct = '';
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
							'terms' => array($term_id),
						),
					),
				);
				
				$the_query = new WP_Query($args);
				if($the_query->have_posts()):
					while($the_query->have_posts() ) : $the_query->the_post();
						$id       = get_the_ID();
						$sTitle   = get_the_title();
						$sLink    = get_permalink();
						$imageUrl = wp_get_attachment_image_src(get_post_thumbnail_id($id))[0];
						
						$product = wc_get_product($id);
						
						$idSmall = get_post_meta($id, 'decorate_small', true);
						if(empty($idSmall)){
							continue;
						}
						
						$sClassActive = '';
						$sTextActive = '';
						if(in_array($id, $aProductExit)){
							$sClassActive = 'activate-choose';
							$sTextActive = '<p class="text-active"><i class="fa fa-check"></i> Sản phẩm đã lựa chọn</p>';
						}
						
						$sHtmlProduct .= ' <div class="items"><div class="item '.$sClassActive.'">
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
					                            	<button type="button" value="'.$id.'" class="button add-config" data-type="'.$typeProduct.'" data-id="'.$idSmall.'" data-all="'.$dataTerm.'" data-term="'.$term_id.'" data-product-edit="'.$idProductChange.'">Chọn sản phẩm này</button>
												</div>
					                        </div></div>';
					endwhile;
				endif;
				wp_reset_postdata();
				
				$sHtml .= '<div class="content-item">
								<div class="box-header">
									<h4 class="title">'.$terms->name.'</h4>
									<a class="button choose-all-set" data-term="'.$term_id.'" data-all="'.$dataTerm.'">Chọn tất cả</a>
									<div class="clear"></div>
								</div>
								<div class="content">
									'.$sHtmlProduct.'
								</div>
							</div>';
			}
		}
		
		$sHtml .= '</div>
					<div class="clear"></div>
				</div>
			</div>';
	}
	
	echo json_encode(
		array(
			'data' => $sHtml,
			'msg' => ''
		)
	);