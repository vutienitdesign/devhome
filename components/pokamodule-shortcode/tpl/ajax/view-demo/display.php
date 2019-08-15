<?php
	$sHtml        = '';
	
	$obj = $_POST['obj'];
	
	$sHtml = '';
	if(!empty($obj)){
		foreach($obj as $v){
			if(!empty($v['product'])){
				$sHtmlProduct = '';
				$product = ltrim($v['product'], ',');
				$aProduct = explode(',', $product);
				
				$args = array(
					'post_status' => array('publish'),
					'post_type' => array('product'),
					'posts_per_page' => -1,
					'post__in' => $aProduct
				);
				$the_query = new WP_Query($args);
				
				if($the_query->have_posts()):
					while ( $the_query->have_posts() ) : $the_query->the_post();
						$sTitle = get_the_title();
						$sLink = get_permalink();
						$imageUrl = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()))[0];
						$sHtmlProduct .= '<div class="box-item">
			                    <div class="item">
			                        <div class="image"><div class="image"><a href="'.$sLink.'" target="_blank"><img src="'.$imageUrl.'" alt="'.$sTitle.'"></a></div></div>
			                        <h3 class="title"><a href="" target="_blank">'.$sTitle.'</a></h3>
			                    </div>
			                </div>';
					endwhile;
				endif;
				wp_reset_postdata();
				
				if(!empty($sHtmlProduct)){
					$sHtml .= '<h4 class="box-header">'.sanitize_text_field($v['title']).'</h4>
						            <div class="content-data">
						                '.$sHtmlProduct.'
						                <div class="clear"></div>
						            </div>';
				}
			}
		}
	}
	
	if(!empty($sHtml)){
		$sHtml = '<div class="modal-content box-viewdemo">
			        <div class="modal-header">
			            <span class="close">×</span>
			            <h3 class="title">Các sản phẩm đã lựa chọn</h3>
			        </div>
			        <div class="modal-body">
			        	<div class="box-content">
			        		'.$sHtml.'
						</div>
			        </div>
			    </div>';
	}
	
	echo json_encode(
		array(
			'data' => $sHtml,
			'msg' => ''
		)
	);