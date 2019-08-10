<?php
	new POKA_MetaBox_Category();
	class POKA_MetaBox_Category {
		public function __construct() {
			$taxonomy = array(
				'category'    => false,  //Cateogry Post
				'product_cat' => false,   //Category Product Woo
				'product_tag' => true    //Tag Product Woo
			);
			foreach($taxonomy as $key => $value){
				if($value == true){
					if(isset($_GET['taxonomy']) && $_GET['taxonomy'] == $key){
						add_action("{$key}_edit_form_fields", array($this, "edit_{$key}"));
						add_action("{$key}_add_form_fields", array($this, "add_{$key}"));
						
						add_filter( "manage_edit-{$key}_columns",    array($this, "column_position_{$key}"), 99);
						add_action( "manage_{$key}_custom_column",   array($this, "column_content_{$key}"), 99, 3);
					}
					
					if(isset($_POST) && $_POST['taxonomy'] == $key){
						add_action("edited_{$key}", array($this, 'save_' . $key));
						add_action("created_{$key}" , array($this, 'save_' . $key));
					}
				}
			}
		}
		
		/*==================Start Category===================*/
		public function add_category(){
//			wp_enqueue_style('poka_metabox_category');
//			wp_enqueue_script('poka_metabox_category');
			require_once "tpl/category_add.php";
		}
		
		public function edit_category($obj){
			require_once "tpl/category_edit.php";
		}
		
		public function save_category($term_id){
		}
		
		function column_position_category($columns){
			$new_columns                = array();
			return array_merge( $new_columns, $columns );
		}
		
		function column_content_category( $value, $column_name, $tax_id ){
		}
		/*==================End Category===================*/
		
		
		/*==================Start Category Product===================*/
		public function add_product_cat(){
			require_once "tpl/category_product_add.php";
		}
		
		public function edit_product_cat($obj){
			require_once "tpl/category_product_edit.php";
		}
		
		public function save_product_cat($term_id){
			if(isset($_POST['poka-desc-cat'])){
				update_option("poka_desc_cat_" . $term_id, htmlspecialchars($_POST['poka-desc-cat']));
			}
		}
		
		function column_position_product_cat($columns){
			/*unset($columns['date']);
			
			$new = array();
			foreach($columns as $key => $title) {
				if ($key == "categories")
					$new['fullname'] = 'Họ Tên';
				$new[$key] = $title;
			}
			return $new;*/
		}
		
		function column_content_product_cat( $value, $column_name, $tax_id ){
		}
		/*==================End Category===================*/
		
		/*==================Start Category Product Tag===================*/
		public function add_product_tag(){
//			require_once "tpl/category_product_tag_add.php";
		}
		
		public function edit_product_tag($obj){
			require_once "tpl/category_product_tag_edit.php";
		}
		
		public function save_product_tag($term_id){
			$arrData = array();
			if(isset($_POST['image']['id'])){
				$arrData['image'] = array(
					'id' => $_POST['image']['id'],
					'url' => wp_get_attachment_image_src($_POST['image']['id'], 'full')[0]
				);
			}
			
			if(!empty($_POST['medium'])){
				foreach($_POST['medium'] as $v){
					$aMedium    = explode('-', $v);
					update_post_meta($aMedium[0], 'decorate_small', $aMedium[1]);
				}
			}
			
			if(!empty($arrData)){
				update_option("poka_product_tag_" . $term_id, $arrData);
			}
		}
		
		function column_position_product_tag($columns){
			$new = array();
			foreach($columns as $key => $title) {
				if($key == "name"){
					$new['image'] = 'Thumbnail';
				}
				$new[ $key ]     = $title;
			}
			return $new;
		}
		
		function column_content_product_tag( $value, $column_name, $tax_id ){
			if($column_name == 'image' || $column_name == 'priority'){
				$aData = get_option("poka_product_tag_" . $tax_id);
				if(!empty($aData)){
					if($column_name == 'image'){
						return '<img height="48" width="48" src="'.wp_get_attachment_image_src($aData['image']['id'])[0].'" />';
					}
				}
			}
		}
		/*==================End Category Tag===================*/
	}