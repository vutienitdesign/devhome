<?php
	$term_id = $obj->term_id;
	
	$aData = get_option("poka_product_tag_" . $term_id);
	
	wp_enqueue_script( 'single-upload-media-script', _POKA_PLUGIN_URL_ . "js/upload-media/single_upload_media.js" , '', '', true );
	
	wp_enqueue_style('pokamodule-category_product_tag_edit', _POKA_PLUGIN_LIB_URL_ . 'metabox/category/css/category_product_tag_edit.min.css', '', '', 'all');
?>
<tr class="form-field">
    <th>Thumbnail</th>
    <td>
        <?php
            echo PMCommon::getHtmlImageSingle('image[id]', @$aData['image']['id']);
        ?>
    </td>
</tr>

<tr class="form-field">
    <th>Sản phẩm</th>
    <td>
		<?php
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
			$productPriority = isset($aData['priority']) ? $aData['priority'] : '';
			$sHtml = '';
			$sProductHidden = '';
			$the_query = new WP_Query( $args );
			if($the_query->have_posts() ) :
				while ( $the_query->have_posts() ) : $the_query->the_post();
			        $nID = get_the_ID();
			        $sChecked = '';
			        if($productPriority == $nID){
				        $sChecked = 'checked="checked"';
                    }
					
					$sCheckedShow = '';
					$sShowProduct = get_post_meta($nID, 'show_product', 'no');
			        if($sShowProduct == 'no'){
				        $sCheckedShow = 'checked="checked"';
                    }
			        
					$sHtml .= '<tr>
                                    <td>'.get_the_title().'</td>
                                    <td class="center"><input '.$sChecked.' type="radio" name="priority" value="'.$nID.'"></td>
                                    <td class="center"><input '.$sCheckedShow.' type="checkbox" name="show_product[]" value="'.$nID.'"></td>
                                </tr>';
					$sProductHidden .= ',' . $nID;
				endwhile;
			endif;
			
			$sProductHidden = ltrim($sProductHidden, ',');
		?>

        <table class="list-product">
            <tr>
                <th>Tên sản phẩm</th>
                <th class="center">Sản phẩm trung tâm</th>
                <th class="center">Hiện thị Frontend</th>
            </tr>
            <?php echo $sHtml; ?>
        </table>
    </td>
</tr>
<input type="hidden" name="all_product" value="<?php echo $sProductHidden; ?>" />