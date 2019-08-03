<?php
new POKA_MetaBox_Product();
class POKA_MetaBox_Product {
	private $_meta_box_id = 'poka_metabox_data';
	
	public function __construct() {
		add_action('add_meta_boxes', array($this, 'display'));
		add_action('save_post',      array($this, "save"));
	}
	
	public function display($post_type ){
		$post_types = array('product');
		global $post;
		$product = get_product($post->ID);
		
		if(in_array( $post_type, $post_types ) && ($product->product_type == 'simple' )) {
			add_meta_box( 'poka_metabox', '- [iArt] Cấu hình', array($this, "content"), $post_type , "normal", "high" );
		}
	}
	
	public function save($postID = 0){
		$data = $_POST;
		
		if(!isset($data[$this->_meta_box_id . '-nonce'])) return $postID;
		if(!wp_verify_nonce($data[$this->_meta_box_id . '-nonce'], $this->_meta_box_id)) return $postID;
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $postID;
		if(!current_user_can('edit_post', $postID)) return $postID;
		
		$postType = get_post_type($postID);
		
		if($postType == 'product'){
			if(isset($_POST['show_product']) && $_POST['show_product'] == 'no'){
				update_post_meta($postID, 'show_product', 'no');
			}else{
				delete_post_meta($postID, 'show_product');
			}
			
			if(isset($_POST['_featured_products']) && !empty($_POST['_featured_products'])){
				$arr = array(
					'id' => $_POST['_featured_products'],
					'url' => wp_get_attachment_image_src($_POST['_featured_products'], 'full')[0]
				);
				update_post_meta($postID, '_featured_products', $arr);
			}else{
				delete_post_meta($postID, '_featured_products');
			}
		}
	}
	
	/*Hien Thi HTML*/
	public function content($obj){
		wp_nonce_field($this->_meta_box_id,$this->_meta_box_id . '-nonce');
		require_once _POKA_PLUGIN_LIB_PATH_ . "/metabox/product/tpl/display.php";
	}
}