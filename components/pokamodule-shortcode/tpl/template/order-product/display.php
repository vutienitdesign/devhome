<?php
	PMCommon::load_lib_components('pokamodule-shortcode/', 'order-product.min.css', 'css');
	PMCommon::load_lib_components('pokamodule-shortcode/', 'order-product.js', 'js');
	
	$product = wc_get_product($productID);

	if(!empty($product)) :
	    $urlProduct = get_permalink($product->get_id());
?>
		<div class="page-order-product">
			<div class="info-product">
                <div class="title">Thông tin sản phẩm</div>
				<div class="title-product"><label>- Sản Phẩm:</label> <a href="<?php echo $urlProduct; ?>" target="_blank"><?php echo $product->get_title(); ?></a></div>
                <div class="price"><label>- Giá: </label> <?php echo wc_price($product->get_price()); ?></div>
                <div class="image-product"><label>- Hình Ảnh Sản Phẩm</label></div>
				<?php echo $product->get_image(); ?>
			</div>
			<p class="title">Thông tin đặt hàng</p>
		</div>
		<script type="text/javascript">
			var pokaUrlProduct = '<?php echo $urlProduct; ?>';
		</script>
<?php
	endif;
?>