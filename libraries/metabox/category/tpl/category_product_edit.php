<?php
	$term_id = $obj->term_id;
	
	$vContentDes = get_option("poka_desc_cat_" . $term_id);
?>
<tr class="form-field">
    <th scope="row"><label>Mô tả dài</label></th>
    <td>
        <?php
	        $settings = array(
		        'media_buttons' => false,
		        'textarea_rows' => 10,
	        );
	        wp_editor(htmlspecialchars_decode($vContentDes), 'poka-desc-cat', $settings);
        ?>
        <p class="description">Đây là phần mô tả dài hiện thị ở giao diện chuyên mục</p>
    </td>
</tr>