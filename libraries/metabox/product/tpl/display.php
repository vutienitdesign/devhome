<?php
	$productID = $obj->ID;
	wp_enqueue_script( 'single-upload-media-script', _POKA_PLUGIN_URL_ . "js/upload-media/single_upload_media.js" , '', '', true );
?>
<table class="form-table">
	<tbody>
        <tr>
            <th>Hiện thị sản phẩm ngoài Frontend</th>
            <td>
                <?php
	                $sShow = get_post_meta($productID, 'show_product', true);
	                if(empty($sShow)){
		                $sShow = 'yes';
                    }
	                $arr = array(
                        'yes' => 'Yes',
                        'no' => 'No'
                    );
	                foreach($arr as $k => $v){
	                    if($k == $sShow){
		                    echo ' <input checked="checked" type="radio" name="show_product" value="'.$k.'" id="show_product_'.$k.'"><label for="show_product_'.$k.'">'.$v.'</label> ';
                        }else{
		                    echo ' <input type="radio" name="show_product" value="'.$k.'" id="show_product_'.$k.'"><label for="show_product_'.$k.'">'.$v.'</label> ';
                        }
                    }
                ?>
                <p class="description">Sản phẩm này cho phép hiện thị khi chưa đăng nhập không? Mặc định: Yes</p>
            </td>
        </tr>
		<tr>
			<th>Sản phẩm nổi bật trung tâm</th>
			<td>
                <?php
                    $aImage = get_post_meta($productID, '_featured_products', true);
                    echo PMCommon::getHtmlImageSingle('_featured_products', @$aImage['id']);
                ?>
                <p class="description">Nếu đây là sản phẩm thuộc sản phẩm cấu hình sét đồ và là sản phẩm nổi bật thì hãy Chọn hình ảnh</p>
            </td>
		</tr>
	</tbody>
</table>