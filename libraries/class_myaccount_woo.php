<?php
	//https://wpbeaches.com/change-rename-woocommerce-endpoints-accounts-page/
	new Custom_My_Account_Endpoint();
	class Custom_My_Account_Endpoint{
		public static $manager_builder = 'builder-product';
		public static $remove_product = 'remove-product';
		private $_title_builder_product = 'Xây dựng sản phẩm';
		private $_title_remove_product = 'Xóa bỏ sản phẩm';
		
		public function __construct(){
			add_action('init', array($this, 'add_endpoints'));
			add_filter('query_vars', array($this, 'add_query_vars'), 0);
			add_filter('the_title', array($this, 'endpoint_title'));
			add_filter('woocommerce_account_menu_items', array($this, 'new_menu_items'));
			add_action('woocommerce_account_' . self::$manager_builder . '_endpoint', array($this, 'manager_content'));
			add_action('woocommerce_account_' . self::$remove_product . '_endpoint', array($this, 'content_remove_product'));
		}
		
		public function add_endpoints(){
			add_rewrite_endpoint(self::$manager_builder, EP_ROOT | EP_PAGES);
			add_rewrite_endpoint(self::$remove_product, EP_ROOT | EP_PAGES);
		}
		
		public function add_query_vars($vars){
			$vars[] = self::$manager_builder;
			$vars[] = self::$remove_product;
			return $vars;
		}
		
		public function endpoint_title($title){
			global $wp_query;
			$is_endpoint = isset($wp_query->query_vars[ self::$manager_builder ]);
			if($is_endpoint && !is_admin() && is_main_query() && in_the_loop() && is_account_page()){
				$title = $this->_title_builder_product;
				
				remove_filter('the_title', array($this, 'endpoint_title' ) );
			}
			
			$is_endpoint = isset($wp_query->query_vars[ self::$remove_product ]);
			if($is_endpoint && !is_admin() && is_main_query() && in_the_loop() && is_account_page()){
				$title = $this->_title_remove_product;
				
				remove_filter('the_title', array($this, 'endpoint_title' ) );
			}
			
			return $title;
		}
		
		public function new_menu_items($items){
			$myorder = array(
				'edit-account'    => 'Thông tin tài khoản',
				'dashboard'       => 'Bảng điều khiển',
				'orders'          => 'Đơn đặt hàng',
				'downloads'       => 'Tải xuống',
				'edit-address'    => 'Địa chỉ',
				'payment-methods' => 'Phương thức thanh toán',
				self::$manager_builder   => $this->_title_builder_product,
				self::$remove_product   => $this->_title_remove_product,
				'customer-logout' => 'Thoát',
			);
			return $myorder;
		}
		public function manager_content(){
			wp_enqueue_style('iart-config-product-confirm', _POKA_PLUGIN_ASSET_URL_ . "confirm/jquery-confirm.min.css", '', '', 'all');
			wp_enqueue_script('iart-config-product-confirm', _POKA_PLUGIN_ASSET_URL_ . "confirm/jquery-confirm.min.js", '', '', true);
			
			wp_enqueue_style("poka_myaccount_css_builder_product", _POKA_PLUGIN_LIB_URL_ . "myaccount/css/woocommerce-builder-product.min.css");
			wp_enqueue_script('poka_myaccount_css_builder_product', _POKA_PLUGIN_LIB_URL_ . "myaccount/js/woocommerce-builder-product.js", array('jquery'), '', true);
			
			require _POKA_PLUGIN_LIB_PATH_ . "myaccount/woocommerce-builder-product.php";
		}
		
		public function content_remove_product(){
			wp_enqueue_style('iart-config-product-confirm', _POKA_PLUGIN_ASSET_URL_ . "confirm/jquery-confirm.min.css", '', '', 'all');
			wp_enqueue_script('iart-config-product-confirm', _POKA_PLUGIN_ASSET_URL_ . "confirm/jquery-confirm.min.js", '', '', true);
			
			wp_enqueue_style("poka_myaccount_css_builder_product", _POKA_PLUGIN_LIB_URL_ . "myaccount/css/woocommerce-builder-product.min.css");
			wp_enqueue_script('poka_myaccount_css_builder_product', _POKA_PLUGIN_LIB_URL_ . "myaccount/js/woocommerce-builder-product.js", array('jquery'), '', true);
			
			require _POKA_PLUGIN_LIB_PATH_ . "myaccount/woocommerce-remove-product.php";
		}
	}