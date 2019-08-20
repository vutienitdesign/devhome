<?php
	$termID             = intval($_POST['term_id']);
	$dataProductCurrent = intval($_POST['data_product_current']);
	$paged              = max(1, $_POST['paged']);
	$productExit        = $_POST['product_exist'];
	$idProductTemp      = $_POST['id-proudct-temp'];
	$idProductChange    = $_POST['id-proudct-change']; //ID Sản phẩm update (thay đổi)
	$nameProduct        = sanitize_text_field($_POST['name-product']);
	$aAttributes        = isset($_POST['attributes']) ? (array) $_POST['attributes'] : array();
	$typeProduct        = ($_POST['type-proudct'] == 'add') ? 'add' : 'edit';
	$loadmore           = !empty($_POST['loadmore']) ? $_POST['loadmore'] : '';
	
	/*================================SATRT Get Search Attributes================*/
	$aDataSearch = array();
	$terms = get_term_by('id', $termID, 'product_cat');
	
	$sTitleCat = $_POST['title'];
	if(!empty($terms)){
		$sSlug     = $terms->slug;
		
		$args = array('category'  => array($sSlug));
		foreach( wc_get_products($args) as $product ){
			foreach( $product->get_attributes() as $attr_name => $attr ){
				$attrLabel =  wc_attribute_label($attr_name);
				foreach( $attr->get_terms() as $term ){
					$aDataSearch[$attrLabel][$term->slug] = array(
						'slug' => $term->slug,
						'name' => $term->name,
						'taxonomy' => $attr_name,
					);
				}
			}
		}
	}
	/*================================END Get Search Attributes================*/
	
	$productExit = ltrim($productExit, ',');
	$aProductExit = array();
	if(!empty($productExit)){
		$aProductExit = explode(',', $productExit);
	}
	
	$args = array(
		'post_status' => array('publish'),
		'post_type' => array('product'),
		'posts_per_page' => 6,
		'paged' => $paged,
		's' => $nameProduct,
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'product_cat',
				'field' => 'id',
				'terms' => array($termID),
			),
		),
	);
	
	$msg = '';
	$arrAttr = array();
	if(is_array($aAttributes) && !empty($aAttributes)){
		$msg = $aAttributes;
		
		foreach($aAttributes as $kAttr => $vAttr){
			$arrAttr[$vAttr][] = $kAttr;
		}
		
		foreach($arrAttr as $kAttr => $vAttr){
			$args['tax_query'][] = array(
				'taxonomy' => $kAttr,
				'field' => 'slug',
				'terms' => $vAttr,
//				'operator' => 'IN',
				'operator' => 'AND'
			);
		}
	}
	
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
	
	$sHtmlParam = 'data-type="add"';
	if($typeProduct == 'edit'){
		$sHtmlParam = 'data-type="add" data-product-edit=""';
	}
	
	$sHtmlProduct = '';
	if ( $the_query->have_posts() ) :
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$id       = get_the_ID();
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
			
			$sHtmlProduct .= '<div class="item '.$sClassActive.'">
						<div class="image">
							<a href="'.$sLink.'" target="_blank"><img src="'.$imageUrl.'" alt="'.$sTitle.'"></a>
						</div>
						<div class="info">
							<h4 class="title"><a target="_blank" href="'.$sLink.'">'.$sTitle.'</a></h4>
							<ul>
								<li><span class="lbl">Mã sản phẩm:</span>'.$product->get_sku().'</li>
								<li>
									<div class="price">'.$product->get_price_html().'</div>
								</li>
							</ul>
						</div>
						<div class="action">
							'.$sTextActive.'
							<button type="button" value="'.$id.'" data-id="'.$dataProductCurrent.'" class="button add-config" data-product="'.$idProductChange.'" data-type="'.$typeProduct.'" data-temp="'.$idProductTemp.'" data-term="'.$termID.'">Chọn sản phẩm này</button>
						</div>
						<div class="clear"></div>
					</div>';
		endwhile;
	endif;
	wp_reset_postdata();
	
	/*$aPagi = paginate_links( array(
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
	}*/
	
	//=========== START HTML SORT ================
	$sHtmlSort = '<div class="sort-data">
					<span>Sắp xếp: </span>
					<select class="sort-product">';
	$aSort = array(
		'date-desc'  => 'Sắp xếp thời gian từ mới đến cũ',
		'date'       => 'Sắp xếp theo thời gian từ cũ đến mới',
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
	$sHtmlSort = $sHtmlSort . '</select></div>';
	//=========== END HTML SORT ================
	
	$sHtmlAttr = '<div class="box-left">';
	if(!empty($aDataSearch)){
		foreach($aDataSearch as $k => $v){
			$sHtmlAttr .= '
						<div class="group-search">
							<h5 class="title-filter">'.$k.'</h5>
							<ul class="box-filter">';
			
			foreach($v as $vTmp){
				$sChecked = '';
				foreach($aAttributes as $kAttr => $vAttr){
					if($kAttr == $vTmp['slug'] && $vAttr == $vTmp['taxonomy']){
						$sChecked ='checked="checked"';
						break;
					}
				}
				$sHtmlAttr .= '<li><input '.$sChecked.' class="search-attr" id="'.$vTmp['taxonomy'].'-'.$vTmp['slug'].'" type="checkbox" value="'.$vTmp['slug'].'" data-taxonomy="'.$vTmp['taxonomy'].'"><label for="'.$vTmp['taxonomy'].'-'.$vTmp['slug'].'">'.$vTmp['name'].'</label></li>';
			}
								
            $sHtmlAttr .=	'</ul>
							<div class="clear"></div>
						</div>';
		}
	}
	$sHtmlAttr .= '</div>';
	
	$sHtmlData = '';
	if(empty($sHtmlProduct)){
		$sHtmlSort = '';
		$sHtmlAttr = '';
		
		$sHtmlData   = '<p>Không có sản phẩm!</p>';
		$sHtmlSearch = '';
		$sTitle      = 'Thông báo';
	}else{
		$sHtmlData = '<div class="box-right">
						<div class="box-action">
							'.$sHtmlSort.'
							<div class="clear"></div>
						</div>
						<div class="list-product-select">
							'.$sHtmlProduct.'
						</div>
					</div>';
		
		if(empty($sTitleCat)){
			$sTitle = 'Chọn sản phẩm';
		}else{
			$sTitle = 'Chọn ' . $sTitleCat;
		}
		
		$sHtmlSearch = '<div class="search-name">
						<input type="text" class="value-text" placeholder="Nhập tên sản phẩm..." value="'.$nameProduct.'">
						<button type="submit" class="btn btn-search"><i class="ec ec-search"></i></button>
					</div>';
	}
	
	if($loadmore == 'load'){
		$sHtml = $sHtmlProduct;
	}else{
		$sHtml = '<div class="modal-content box-choose-product">
				<div class="loader"></div>
				<div class="modal-header">
					<h2 class="title">'.$sTitle.'</h2>
					<div class="box-close"><span class="close">&times;</span></div>
					'.$sHtmlSearch.'
				</div>
				<div class="modal-body">
					'.$sHtmlAttr.'
					'.$sHtmlData.'
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