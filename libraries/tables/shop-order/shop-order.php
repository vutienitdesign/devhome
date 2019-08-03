<?php
	new POKA_Tables_Shop_Order();
	class POKA_Tables_Shop_Order {
		public function __construct() {
			add_filter("manage_edit-shop_order_columns",        array($this, "manage_columns"));
			add_action("manage_shop_order_posts_custom_column",  array($this, "manage_custom_columns"), 10, 2);
		}
		
		public function manage_columns($columns){
			$new_columns = array();
			foreach($columns as $column_name => $column_info){
				$new_columns[ $column_name ] = $column_info;
				if('order_status' === $column_name){
					$new_columns['column_type'] = 'Họ tên 123';
				}
			}
			
			return $new_columns;
		}
		
		public function manage_custom_columns($column, $post_id){
//			global $post;
			if('column_type' === $column){
				$productID = $post_id;
				echo 'aaaaaaa';
			}
		}
	}