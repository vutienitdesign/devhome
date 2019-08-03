<?php
	$termID = intval($_POST['term_id']);
	$dataProductCurrent = intval($_POST['data_product_current']);
	$paged = max(1, $_POST['paged']);
	$productExit = $_POST['product_exist'];
	$idProductTemp = $_POST['id-proudct-temp'];
	$typeProduct = ($_POST['type-proudct'] == 'add') ? 'add' : 'edit';
	
	$productExit = ltrim($productExit, ',');
	$aProductExit = array();
	if(!empty($productExit)){
		$aProductExit = explode(',', $productExit);
	}
	
	$args = array(
		'post_status' => array('publish'),
		'post_type' => array('product'),
		'posts_per_page' => 4,
		'paged' => $paged,
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'product_cat',
				'field' => 'id',
				'terms' => array($termID),
			),
		),
	);
	
	$sortOrderBy = 'date-desc';
	$sSort       = 'date';
	$sortOrder   = 'DESC';
	
	if(!empty($_POST['sort'])){
		$sortOrderBy = trim($_POST['sort']);
		switch($sortOrderBy){
			case 'date':
				$args['order'] = 'ASC';
				$args['orderby'] = 'date';
				
				break;
			case 'date-desc':
				$args['order'] = 'DESC';
				$args['orderby'] = 'date';
				break;
			case 'price':
				$args['order'] = 'ASC';
				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = '_price';
				break;
			case 'price-desc':
				$args['order'] = 'DESC';
				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = '_price';
				break;
		}
	}
	
	if(!isset($args['orderby'])){
		$args['order'] = 'DESC';
		$args['orderby'] = 'date';
	}
	
	
	$the_query = new WP_Query($args);
	
	$sHtmlProduct = '';
	if ( $the_query->have_posts() ) :
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$id = get_the_ID();
			$sTitle = get_the_title();
			$sLink = get_permalink();
			$imageUrl = wp_get_attachment_image_src(get_post_thumbnail_id($id))[0];

			$product = wc_get_product($id);
			
			$stock = $product->get_stock_status();
			if($stock == 'instock'){
				$stock = 'Còn hàng';
			}else{
				$stock = 'Hết hàng';
			}
			
			$sClassActive = '';
			if(in_array($id, $aProductExit)){
				$sClassActive = 'activate-choose';
			}
			
			$sHtmlProduct .= '<div class="item '.$sClassActive.'">
						<div class="image">
							<a href="" target="_blank"><img src="'.$imageUrl.'" alt="'.$sTitle.'"></a>
						</div>
						<div class="info">
							<h4 class="title"><a target="_blank" href="'.$sLink.'">'.$sTitle.'</a></h4>
							<ul>
								<li><span class="lbl">Mã sản phẩm:</span>'.$product->get_sku().'</li>
								<li><span class="lbl">Kho hàng:</span>'.$stock.'</li>
								<li>
									<div class="price">'.$product->get_price_html().'</div>
								</li>
							</ul>
						</div>
						<div class="action">
							<button type="button" value="'.$id.'" data-id="'.$dataProductCurrent.'" class="button alt add-config" data-type="'.$typeProduct.'" data-temp="'.$idProductTemp.'" data-term="'.$termID.'">Thêm vào cấu hình</button>
						</div>
						<div class="clear"></div>
					</div>';
		endwhile;
	endif;
	wp_reset_postdata();
	
	$aPagi = paginate_links( array(
		'base'         => str_replace( 999999999, '%#%', esc_url(get_pagenum_link( 999999999))),
		'total'        => $the_query->max_num_pages,
		'current'      => $paged,
		'format'       => '?paged=%#%',
		'show_all'     => false,
		'type'         => 'array',
		'end_size'     => 2,
		'mid_size'     => 1,
		'prev_next'    => true,
//		'prev_text'    => '«',
//		'next_text'    => '»',
		'add_args'     => false,
		'add_fragment' => '',
	));
	
	$sHtmlPagi = '';
	if(!empty($aPagi)){
		$sHtmlPagi = '<ul>';
		foreach($aPagi as $v){
			if(strstr($v, 'class="prev') || strstr($v, 'class="next')){
				continue;
			}
			
			if(strstr($v, 'class=\'page-numbers current\'')){
			
			}else{
				$v = preg_replace('/<a(.*)href=\'?"?([^"]*)\'?"?(.*)>(.*)(<\/a>)/','<a$1 data-pagi=\'$4\' href=\'#\'$3>$4',$v);
				$v .= '</a>';
			}
			
			$sHtmlPagi .= '<li>'.$v.'</li>';
		}
		$sHtmlPagi .= '</ul>';
	}
	
	
	$sHtmlSort = '';
	$aSort = array(
		'date-desc'  => 'Sắp xếp thời gian từ mới đến cũ 1',
		'date'       => 'Sắp xếp theo thời gian từ cũ đến mới 1',
		'price'      => 'Sắp xếp theo giá: thấp đến cao',
		'price-desc' => 'Sắp xếp theo giá: cao đến thấp'
	);
	foreach($aSort as $k => $v){
		if($k == $sortOrderBy){
			$sHtmlSort .= '<option selected="selected" value="'.$k.'">'.$v.'</option>';
		}else{
			$sHtmlSort .= '<option value="'.$k.'">'.$v.'</option>';
		}
	}
	
	
	$sHtml = '<div class="modal-content box-choose-product">
				<div class="modal-header">
					<span class="close">&times;</span>
					<h2 class="title">Chọn sản phẩm</h2>
				</div>
				<div class="modal-body">
					<div class="loader"></div>
					<div class="box-left">
						<div class="group-search">
							<h5 class="title-filter">Hãng sản xuất</h5>
							<ul class="box-filter">
								<li><input type="checkbox">AMD(15)</li>
								<li><input type="checkbox">Intel(15)</li>
								<li><input type="checkbox">Core i5(15)</li>
								<li><input type="checkbox">Core i9(15)</li>
								<li><input type="checkbox">Core i3(15)</li>
							</ul>
							<div class="clear"></div>
						</div>
						<div class="group-search">
							<h5 class="title-filter">Dòng CPU</h5>
							<ul class="box-filter">
								<li><input type="checkbox">AMD(15)</li>
								<li><input type="checkbox">Intel(15)</li>
								<li><input type="checkbox">Core i5(15)</li>
								<li><input type="checkbox">Core i9(15)</li>
								<li><input type="checkbox">Core i3(15)</li>
							</ul>
							<div class="clear"></div>
						</div>
					</div>
					<div class="box-right">
						<div class="box-action">
							<div class="sort-data">
								<span>Sắp xếp: </span>
								<select class="sort-product">
									'.$sHtmlSort.'
								</select>
							</div>
							<div class="pagi">
								'.$sHtmlPagi.'
							</div>
							<div class="clear"></div>
						</div>
						<div class="list-product-select">
							'.$sHtmlProduct.'
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>';
	
	echo json_encode(
		array(
			'data' => $sHtml,
			'msg' => $msg
		)
	);