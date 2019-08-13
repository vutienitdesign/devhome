<?php
	new Poka_post_type_product();
	class Poka_post_type_product{
		private $_post_name    = "Sản phẩm";
		private $_slug         = "san-pham";
		private $_taxonomy_cat = 'danh-muc-san-pham';
		private $_taxonomy_tag = 'tu-khoa';
		
		public function __construct(){
			add_action('init', array($this, 'post_type'));
			add_action('init', array($this, 'taxonomies'));
			
			//Custom Query
			add_action('pre_get_posts', array($this, 'set_query'), '', 1);
			
			//Load template
			if(!is_admin()){
				add_filter('template_include', array($this,'load_template'), 99);
			}
		}
		
		public function load_template($templates){
			if(is_single()){
				global $post;
				
				if($post->post_type == $this->_slug){
					return _POKA_PLUGIN_LIB_PATH_ . 'post_type/product/tpl/single-product.php';
				}
			}
			
			if(is_archive()){
				if(get_query_var($this->_taxonomy_cat) != ''){
//					wp_enqueue_script('poka_posttype_product');
//					wp_enqueue_script('poka_posttype_product', _POKA_PLUGIN_LIB_URL_ . 'custom_post_type/product/js/fe-product-cat.js', array('jquery'), '', true);
					return _POKA_PLUGIN_LIB_PATH_ . 'post_type/product/tpl/product-cat.php';
				}
				
				if(get_query_var($this->_taxonomy_tag) != ''){
					die("<h3 style='color: red;font-size: 14px'>=======111=====STOP=========</h3>");
				}
			}
			
			return $templates;
		}
		
		function post_type() {
			$labels = array(
				"name"               => $this->_post_name,
				"singular_name"      => $this->_post_name,
				"menu_name"          => $this->_post_name,
				"all_items"          => "Tất cả " . $this->_post_name,
				"add_new"            => "Thêm mới",
				"add_new_item"       => "Thêm mới" . $this->_post_name,
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
				"public"              => true,
				"show_ui"             => true,
				"has_archive"         => false,
				"show_in_menu"        => true,
				"exclude_from_search" => false,
				"capability_type"     => "post",
				"map_meta_cap"        => true,
				"hierarchical"        => false,
				"rewrite"             => array("slug" => $this->_slug, "with_front" => true),
				"supports"            => array('title', 'editor', 'thumbnail', 'comments', 'custom-fields'),
				"query_var"           => true,
				"menu_position"       => 4,
				"menu_icon"           => "dashicons-cart",
				"taxonomies"          => array($this->_taxonomy_tag, $this->_taxonomy_cat)
			);
			register_post_type($this->_slug, $args );
		}
		
		function taxonomies(){
			//Create Category
			$labels = array(
				"name"                       => "Danh mục " . $this->_post_name,
				"label"                      => "Danh mục " . $this->_post_name,
				"menu_name"                  => "Danh mục",
				"all_items"                  => "Tất cả danh mục",
				"edit_item"                  => "Chỉnh sửa danh mục",
				"view_item"                  => "Hiển thị danh mục",
				"update_item"                => "Cập nhật tên danh mục",
				"add_new_item"               => "Thêm mới danh mục",
				"new_item_name"              => "Tên danh mục mới",
				"parent_item"                => "Danh mục cha",
				"parent_item_colon"          => "Danh mục cha:",
				"search_items"               => "Tìm kiếm danh mục",
				"popular_items"              => "Danh mục phổ biến",
				"separate_items_with_commas" => "Ngăn cách chuyên mục bằng dấu phẩy",
				"add_or_remove_items"        => "Thêm hoặc xóa danh mục",
				"choose_from_most_used"      => "Chọn danh mục được sử dụng nhiều nhất",
				"not_found"                  => "Không tìm thấy danh mục",
			);
			$args = array(
				"labels"            => $labels,
				"hierarchical"      => true,
				"label"             => "Danh mục " . $this->_post_name,
				"show_ui"           => true,
				"query_var"         => true,
				"rewrite"           => array('slug' => $this->_taxonomy_cat, 'with_front' => true),
				"show_admin_column" => true,
			);
			register_taxonomy($this->_taxonomy_cat, array($this->_slug), $args );
			
			//Create Tag
			$labels = array(
				"name"                       => "Từ khóa " . $this->_post_name,
				"label"                      => "Từ khóa " . $this->_post_name,
				"menu_name"                  => "Từ khóa",
				"all_items"                  => "Tất cả Từ khóa",
				"edit_item"                  => "Chỉnh sửa Từ khóa",
				"view_item"                  => "Hiển thị Từ khóa",
				"update_item"                => "Cập nhật tên Từ khóa",
				"add_new_item"               => "Thêm Từ khóa mới",
				"new_item_name"              => "Tên Từ khóa mới",
				"parent_item"                => "Từ khóa cha",
				"parent_item_colon"          => "Từ khóa cha:",
				"search_items"               => "Tìm kiếm Từ khóa",
				"popular_items"              => "Từ khóa phổ biến",
				"separate_items_with_commas" => "Ngăn cách Từ khóa bằng dấu phẩy",
				"add_or_remove_items"        => "Thêm hoặc xóa Từ khóa",
				"choose_from_most_used"      => "Chọn Từ khóa được sử dụng nhiều nhất",
				"not_found"                  => "Không tìm thấy Từ khóa",
			);
			$args = array(
				"labels"            => $labels,
				"hierarchical"      => false,
				"label"             => "Từ khóa " . $this->_post_name,
				"show_ui"           => true,
				"query_var"         => true,
				"rewrite"           => array('slug' => $this->_taxonomy_tag, 'with_front' => true),
				"show_admin_column" => true,
			);
			register_taxonomy( $this->_taxonomy_tag, array($this->_slug), $args );
		}
		
		function set_query($query){
			if (!is_admin() && $query->is_main_query() && $query->query_vars[$this->_taxonomy_cat]) {
				$query->set('posts_per_page', 2);
			}
		}
	}