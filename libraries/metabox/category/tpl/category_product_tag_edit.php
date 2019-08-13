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
            $idLarge = '';
			global $wpdb;
			$sql = "SELECT `id`,`decorate_large`,`tag` FROM `{$wpdb->prefix}decorate_medium`";
			$result = $wpdb->get_results($sql, ARRAY_A);
			if(!empty($result)){
			    foreach($result as $v){
				    $aTag    = explode(',', $v['tag']);
				    if(in_array($term_id, $aTag)){
					    $idMedium = $v['id'];
					    $idLarge = $v['decorate_large'];
				        break;
                    }
                }
            }
			
			$resultMedium = array();
			if(!empty($idMedium)){
				$sql = "SELECT `id`,`name` FROM `{$wpdb->prefix}decorate_small` WHERE `decorate_medium` = {$idMedium} AND `decorate_large` = {$idLarge}";
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
			$sHtml = '';
			$sProductHidden = '';
			$the_query = new WP_Query( $args );
			$i = 0;
			if($the_query->have_posts() ) :
				while ( $the_query->have_posts() ) : $the_query->the_post();
			        $nID = get_the_ID();
			        
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
                    <th class="center">Không gian nhỏ</th>
                </tr>
		        <?php echo $sHtml; ?>
            </table>
        </div>
    </td>
</tr>
<input type="hidden" name="all_product" value="<?php echo $sProductHidden; ?>" />

<tr class="form-field">
    <th>Độ ưu tiên SET Đồ</th>
    <td>
        <?php
	        $posts = get_posts(array(
		        'post_type' => 'iart-style-set',
		        'post_status' => 'publish',
		        'numberposts' => -1
            ));
	
	        $postChecked = get_option("iart_priority_set_tag_" . $term_id);
	        $sHtml = '';
	        if(!empty($posts)){
	            foreach($posts as $v){
	                if($postChecked == $v->ID){
		                $sHtml .= '<option selected="selected" value="'.$v->ID.'">'.$v->post_title.'</option>';
                    }else{
		                $sHtml .= '<option value="'.$v->ID.'">'.$v->post_title.'</option>';
                    }
                }
            }
        ?>
        <select name="priority_set" class="regular-text">
            <?php echo $sHtml ?>
        </select>
    </td>
</tr>