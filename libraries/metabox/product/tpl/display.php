<?php
	$productID = $obj->ID;
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
	</tbody>
</table>