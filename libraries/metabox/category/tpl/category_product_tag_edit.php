<?php
	$term_id = $obj->term_id;
	
	$aData = get_option("poka_product_tag_" . $term_id);
	
	wp_enqueue_script( 'single-upload-media-script', _POKA_PLUGIN_URL_ . "js/upload-media/single_upload_media.js" , '', '', true );
?>
<tr class="form-field">
    <th>Thumbnail</th>
    <td>
        <?php
            echo PMCommon::getHtmlImageSingle('image[id]', @$aData['image']['id']);
        ?>
    </td>
</tr>