<?php
new POKA_Custom_MetaBox();
class POKA_Custom_MetaBox {
	public function __construct() {
		if(is_admin()){
			$arr = array(
				'post_page' => false,        //Meta boxes POST, Page
				'category'  => true,        //taxonomy: category, product_cat, attributes product...
				'user'      => false,         //Meta boxes User Profile
				'product'   => false,         //Meta boxes Product Woocommerce
				'comment'   => false,        //Meta boxes Comment
			);
			
			foreach($arr as $key => $value){
				if($value == true){
					if(is_file(_POKA_PLUGIN_LIB_PATH_ . "metabox/{$key}/css/" . $key . '.css')){
						wp_register_style("poka_metabox_css_{$key}", _POKA_PLUGIN_LIB_URL_ . "metabox/{$key}/css/{$key}.css");
					}
					
					if(is_file(_POKA_PLUGIN_LIB_PATH_ . "metabox/{$key}/js/" . $key . '.js')){
						wp_register_script("poka_metabox_js_{$key}", _POKA_PLUGIN_LIB_URL_ . "metabox/{$key}/js/{$key}.js", array('jquery'), '1.0', true);
					}
					require_once _POKA_PLUGIN_LIB_PATH_ . "metabox/{$key}/{$key}.php";
				}
			}
			
			/*
			 USING
				wp_enqueue_style('poka_metabox_css_product'); //Change product
				wp_enqueue_script('poka_metabox_js_product');  //Change product
			 */
		}
    }
}
