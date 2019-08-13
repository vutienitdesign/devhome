<?php
new POKA_MetaBox_Post_Page();
class POKA_MetaBox_Post_Page {
	private $_meta_box_id = 'poka_metabox_data';
	
	public function __construct() {
		 add_action('add_meta_boxes', array($this, 'display') );
		 add_action('save_post',      array($this, "save"));
	}
	
	public function display(){
//		wp_enqueue_style('poka_metabox_post_page');
//		wp_enqueue_script('poka_metabox_post_page');
		
		$postID 	= !empty($_REQUEST["post"]) ? $_REQUEST["post"] : 0;
		$postType	= get_post_type($postID);
		
		switch($postType) {
			/*case "post":
				add_meta_box( 'poka_metabox', '- [Poka] Cấu hình', array($this, "post"), 'post' , "normal", "high" );
			case "page":
				add_meta_box( 'poka_metabox', '- [Poka] Cấu hình', array($this, "page"), 'page' , "normal", "high" );
				break;*/
			/*case "style-set":
				add_meta_box( 'poka_metabox', '- [iArt] Cấu hình', array($this, "styleset"), 'style-set' , "normal", "high" );
				break;*/
		}
	}
	
	public function save($postID = 0){
		$data = $_POST;
		
		if(!isset($data[$this->_meta_box_id . '-nonce'])) return $postID;
		if(!wp_verify_nonce($data[$this->_meta_box_id . '-nonce'], $this->_meta_box_id)) return $postID;
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $postID;
		if(!current_user_can('edit_post', $postID)) return $postID;
		
		$postType = get_post_type($postID);
		$data     = $_POST;
		
		if($postType == 'post'){
		}
		
		if($postType == 'page'){
		}
	}
	
	public function styleset(){
//		wp_enqueue_style('single_style_set', _POKA_PLUGIN_LIB_URL_ . 'metabox/post_page/css/single-style-set.min.css');
	}
	
	/*Hien Thi HTML Trang Post*/
	public function post(){
		wp_nonce_field($this->_meta_box_id,$this->_meta_box_id . '-nonce');
		require_once _POKA_PLUGIN_LIB_PATH_ . "/metabox/post_page/tpl/post.php";
	}
	
	/*Hien Thi HTML Trang Page*/
	public function page(){
		wp_nonce_field($this->_meta_box_id,$this->_meta_box_id . '-nonce');
		
	}
}