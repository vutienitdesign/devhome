<?php
	new POKA_Tables_User();
	class POKA_Tables_User {
		public function __construct() {
			add_filter("manage_users_columns",        array($this, "manage_columns"));
			add_action("manage_users_custom_column",  array($this, "manage_custom_columns"), 10, 3);
		}
		
		public function manage_columns($columns){
			wp_enqueue_style('poka_tables_css_users');
//			wp_enqueue_script('poka_tables_js_users');

//			unset($columns['name']);
			
			$new = array();
			foreach($columns as $key => $title) {
				if($key == "posts"){
					$new['price'] = 'Số tiền';
				}
				$new[$key] = $title;
			}
			return $new;
		}
		
		public function manage_custom_columns($value, $column_name, $user_id){
			if('price' == $column_name){
				$nPrice = get_user_meta($user_id, '_price', true);
				if(is_numeric($nPrice)){
					$sPrice = '<span class="price">'.number_format($nPrice, '0', ',', '.') . ' VNĐ'.'</span>';
				}else{
					$sPrice = '<span class="price">'.$nPrice.'</span>';
				}
				return $sPrice;
			}
			return $value;
		}
	}