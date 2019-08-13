<?php
	new Poka_post_type_stype_set_product();
	class Poka_post_type_stype_set_product{
		private $_post_name    = "Style Set Đồ";
		private $_slug         = "iart-style-set";
		
		public function __construct(){
			add_action('init', array($this, 'post_type'));
		}
		
		function post_type() {
			$labels = array(
				"name"               => $this->_post_name,
				"singular_name"      => $this->_post_name,
				"menu_name"          => $this->_post_name,
				"all_items"          => "Tất cả " . $this->_post_name,
				"add_new"            => "Thêm mới",
				"add_new_item"       => "Thêm mới " . $this->_post_name,
				"edit"               => "Sửa",
				"edit_item"          => "Sửa "  . $this->_post_name,
				"new_item"           =>  $this->_post_name . " mới",
				"view"               => "Hiển thị",
				"view_item"          => "Hiển thị " . $this->_post_name,
				"search_items"       => "Tìm kiếm ",
				"not_found"          => "Không tìm thấy "  . $this->_post_name,
				"not_found_in_trash" => "Không tìm thấy ". $this->_post_name . " trong thùng rác",
				"parent"             =>  $this->_post_name . " cha",
			);
			$args = array(
				"labels"              => $labels,
				"description"         => $this->_post_name,
				"public"              => false,
				"show_ui"             => true,
				"has_archive"         => false,
				"show_in_menu"        => true,
				"exclude_from_search" => false,
				"capability_type"     => "post",
				"map_meta_cap"        => true,
				"hierarchical"        => false,
				"rewrite"             => array("slug" => $this->_slug, "with_front" => true),
				"supports"            => array('title'),
				"query_var"           => true,
				"menu_position"       => 4,
				"menu_icon"           => "dashicons-cart",
			);
			register_post_type($this->_slug, $args );
		}
	}