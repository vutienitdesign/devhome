<?php
	new Custom_type_product();
	class Custom_type_product{
		private $_post_name    = "Product";
		private $_slug         = "product";
		private $_taxonomy_cat = 'product_cat';
		private $_taxonomy_tag = 'product_tag';
		
		public function __construct(){
			add_action('init', array($this, 'taxonomies_cat'), 0);
			add_action('init', array($this, 'taxonomies_tag'), 0);
			add_action('init', array($this, 'post_type'));
			
			if(!is_admin()){
				add_filter('template_include', array($this,'load_template'), 99);
			}
		}
		
		function post_type(){
			$labels = array(
				'name'               => $this->_post_name,
				'singular_name'      => $this->_post_name,
				'menu_name'          => $this->_post_name . "s",
				'parent_item_colon'  => $this->_post_name,
				'all_items'          => 'All ' . $this->_post_name,
				'view_item'          => 'View ' . $this->_post_name,
				'add_new_item'       => 'Add New',
				'add_new'            => 'Add New',
				'edit_item'          => 'Edit ' . $this->_post_name,
				'update_item'        => 'Update ' . $this->_post_name,
				'search_items'       => 'Search ' . $this->_post_name,
				'not_found'          => 'Not Found',
				'not_found_in_trash' => 'Not found in Trash',
			);
			
			$args = array(
				'label'               => $this->_post_name,
				'description'         => $this->_post_name,
				'labels'              => $labels,
				'supports'            => array('title', 'editor', 'author', 'thumbnail'),
				'taxonomies'          => array($this->_taxonomy_cat),
				'menu_icon'           => "dashicons-location",
				'hierarchical'        => true,
				'public'              => true,
				'public_queryable'    => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => false,
				'show_in_admin_bar'   => true,
				'menu_position'       => 3,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => true,
				'capability_type'     => 'post',
//				'register_meta_box_cb' => 'wpt_add_event_metaboxes'
			);
			register_post_type($this->_slug, $args);
		}
		
		function taxonomies_cat(){
			$labels = array(
				'name'              => 'Category',
				'singular_name'     => 'Category',
				'search_items'      => 'Search category',
				'all_items'         => 'All categories',
				'parent_item'       => 'Parent category',
				'parent_item_colon' => 'Parent category:',
				'edit_item'         => 'Edit category',
				'update_item'       => 'Update category',
				'add_new_item'      => 'Add category',
				'new_item_name'     => 'Add category',
				'menu_name'         => 'Categories',
			);
			register_taxonomy($this->_taxonomy_cat, array($this->_slug), array(
				'hierarchical'      => true,
				'labels'            => $labels,
				'show_ui'           => true,
				'publish'           => false,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array('slug' => $this->_taxonomy_cat),
			));
		}
		
		function taxonomies_tag(){
			$labels = array(
				'name'                       => 'Thẻ tags sản phẩm',
				'singular_name'              => 'Thẻ tags',
				'search_items'               => 'Tìm kiếm thẻ tags',
				'popular_items'              => 'Thẻ',
				'all_items'                  => 'Tất cả thẻ tags',
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'                  => 'Chỉnh sửa thẻ tags',
				'update_item'                => 'Cập nhật thẻ tags',
				'add_new_item'               => 'Thêm mới thẻ ',
				'new_item_name'              => 'Thêm tên thẻ tags',
				'separate_items_with_commas' => 'Phân cách các thẻ tags bằng dấu phẩy',
				'add_or_remove_items'        => 'Thêm hoặc xóa thẻ tags',
				'choose_from_most_used'      => 'Chọn từ các thẻ được sử dụng nhiều nhất',
				'menu_name'                  => 'Thẻ',
			);
			register_taxonomy($this->_taxonomy_tag, $this->_slug, array(
				'hierarchical'          => false,
				'labels'                => $labels,
				'show_ui'               => true,
				'show_admin_column'     => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var'             => true,
				'rewrite'               => array('slug' => $this->_taxonomy_tag),
			));
		}
		
		public function load_template($templates){
			if(is_single()){
				global $post;
				
				if($post->post_type == $this->_slug){
					PMCommon::load_lib_assets('fancybox/', 'custom.fancybox.js', 'js');
					
					return _POKA_PLUGIN_LIB_PATH_ . 'custom_post_type/product/tpl/single-product.php';
				}
			}
			
			/*if(is_archive()){
				if(get_query_var($this->_taxonomy_cat) != ''){
					wp_enqueue_script('poka_posttype_product');
					wp_enqueue_script('poka_posttype_product', _POKA_PLUGIN_LIB_URL_ . 'custom_post_type/product/js/fe-product-cat.js', array('jquery'), '', true);
					return _POKA_PLUGIN_LIB_PATH_ . 'custom_post_type/product/tpl/product-cat.php';
				}
				
				if(get_query_var($this->_taxonomy_tag) != ''){
					die("<h3 style='color: red;font-size: 14px'>=======111=====STOP=========</h3>");
				}
			}*/
			
			return $templates;
		}
	}