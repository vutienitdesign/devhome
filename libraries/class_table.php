<?php
new POKA_Custom_Table();
class POKA_Custom_Table {
	public function __construct() {
		if(is_admin()){
			$arr = array(
				'users'      => false,    //Table User
				'posts'      => false,    //Table posts
				'pages'      => false,    //Table pages
				'shop-order' => false,    //Don hang WooCommerce
				'comment'    => false     //Table Comment
			);
			
			foreach($arr as $key => $value){
				if($value == true){
					if(is_file(_POKA_PLUGIN_LIB_PATH_ . "tables/{$key}/css/" . $key . '.css')){
						wp_register_style("poka_tables_css_{$key}", _POKA_PLUGIN_LIB_URL_ . "tables/{$key}/css/{$key}.css");
					}
					
					if(is_file(_POKA_PLUGIN_LIB_PATH_ . "tables/{$key}/js/" . $key . '.js')){
						wp_register_script("poka_tables_js_{$key}", _POKA_PLUGIN_LIB_URL_ . "tables/{$key}/js/{$key}.js", array('jquery'), '1.0', true);
					}
					require_once _POKA_PLUGIN_LIB_PATH_ . "tables/{$key}/{$key}.php";
				}
			}
			
			/*
			 USING
				wp_enqueue_style('poka_tables_css_product'); //Change product
				wp_enqueue_script('poka_tables_js_product');  //Change product
			 */
		}
    }
}
