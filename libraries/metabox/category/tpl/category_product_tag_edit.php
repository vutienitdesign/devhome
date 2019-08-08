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
            $idMedium = '';
			global $wpdb;
			$sql = "SELECT `id`,`tag` FROM `{$wpdb->prefix}decorate_medium`";
			$result = $wpdb->get_results($sql, ARRAY_A);
			if(!empty($result)){
			    foreach($result as $v){
				    $aTag    = explode(',', $v['tag']);
				    if(in_array($term_id, $aTag)){
					    $idMedium = $v['id'];
				        break;
                    }
                }
            }
			
			$resultMedium = array();
			if(!empty($idMedium)){
				$sql = "SELECT `id`,`name` FROM `{$wpdb->prefix}decorate_small` WHERE `decorate_medium` = {$idMedium}";
				$resultMedium = $wpdb->get_results($sql, ARRAY_A);
            }
			
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
			
			$i = 0;
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
					
					$medium = get_post_meta($nID, 'decorate_small', true);
			        $sHtmlMedium = '';
			        if(!empty($resultMedium)){
			            $iTmp = 0;
			            foreach($resultMedium as $v){
			                $sID = $nID . '-' . $v['id'];
			                
			                $sCheckedMedium = '';
			                if($medium == $v['id']){
				                $sCheckedMedium = 'checked="checked"';
                            }
			                
				            $sHtmlMedium .= '<p>
                                                <input '.$sCheckedMedium.' name="medium[medium-'.$i.']" type="radio" value="'.$sID.'" id="'.$sID.'-'.$iTmp.'">
                                                <label for="'.$sID.'-'.$iTmp.'">'.$v['name'].'</label>
                                             </p>';
				            $iTmp++;
                        }
                    }
			        
					$sHtml .= '<tr>
                                    <td class="center">'.($i + 1).'</td>
                                    <td>'.get_the_title().'</td>
                                    <td class="center"><input '.$sCheckedShow.' type="checkbox" name="show_product[]" value="'.$nID.'"></td>
                                    <td class="">
                                        '.$sHtmlMedium.'
                                    </td>
                                </tr>';
					$sProductHidden .= ',' . $nID;
					
					$i++;
				endwhile;
			endif;
			
			$sProductHidden = ltrim($sProductHidden, ',');
		?>

        <div class="data-list-product">
            <table class="list-product">
                <tr>
                    <th class="stt center">STT</th>
                    <th>Tên sản phẩm</th>
                    <th class="fronend center">Hiện thị Frontend</th>
                    <th class="center">Không gian nhỏ</th>
                </tr>
		        <?php echo $sHtml; ?>
            </table>
        </div>
    </td>
</tr>
<input type="hidden" name="all_product" value="<?php echo $sProductHidden; ?>" />