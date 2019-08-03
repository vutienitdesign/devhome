<?php
	new POKA_Tables_Pages();
	class POKA_Tables_Pages {
		public function __construct() {
			add_filter("manage_pages_columns",        array($this, "manage_columns"));
			add_action("manage_pages_custom_column",  array($this, "manage_custom_columns"), 10, 2);
		}
		
		public function manage_columns($columns){
			unset($columns['name']);
			
			$new = array();
			foreach($columns as $key => $title) {
				if($key == "title"){
					$new['fullnames'] = 'Họ Tên 123';
				}
				$new[$key] = $title;
			}
			return $new;
		}
		
		public function manage_custom_columns($column, $post_id){
			if("fullnames" == $column){
				echo 'aaaa BB';
			}
		}
	}