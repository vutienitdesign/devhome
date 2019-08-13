<?php
	new POKA_Posttype();
	class POKA_Posttype{
		public function __construct(){
			$arr = array(
				'product'          => false,
				'stype-set-product'          => true,
			);
			
			foreach($arr as $key => $value){
				if($value == true && is_admin()){
					if(is_file(_POKA_PLUGIN_LIB_PATH_ . "post_type/{$key}/css/" . $key . '.css')){
						wp_register_style('poka_posttype_' . $key, _POKA_PLUGIN_LIB_URL_ . "/post_type/{$key}/css/" . $key . '.css');
					}
					
					if(is_file(_POKA_PLUGIN_LIB_PATH_ . "post_type/{$key}/css/" . $key . '.js')){
						wp_register_script('poka_posttype_' . $key, _POKA_PLUGIN_LIB_URL_ . "/post_type/{$key}/js/" . $key . '.js', array('jquery'), '1.0', true);
					}
				}
				
				if($value == true){
					require_once _POKA_PLUGIN_LIB_PATH_ . "post_type/{$key}/" . $key . '.php';
				}
			}
		}
	}

