<?php
	//https://wpbeaches.com/change-rename-woocommerce-endpoints-accounts-page/
	new Custom_My_Account_Endpoint();
	class Custom_My_Account_Endpoint{
		public static $endpoint = 'builder-product';
		private $_sTitle = 'Xây dựng sản phẩm';
		
		public function __construct(){
			add_action('init', array($this, 'add_endpoints'));
			add_filter('query_vars', array($this, 'add_query_vars'), 0);
			add_filter('the_title', array($this, 'endpoint_title'));
			add_filter('woocommerce_account_menu_items', array($this, 'new_menu_items'));
			add_action('woocommerce_account_' . self::$endpoint . '_endpoint', array($this, 'endpoint_content'));
		}
		
		public function add_endpoints(){
			add_rewrite_endpoint(self::$endpoint, EP_ROOT | EP_PAGES);
		}
		
		public function add_query_vars($vars){
			$vars[] = self::$endpoint;
			return $vars;
		}
		
		public function endpoint_title($title){
			global $wp_query;
			$is_endpoint = isset($wp_query->query_vars[ self::$endpoint ]);
			if($is_endpoint && !is_admin() && is_main_query() && in_the_loop() && is_account_page()){
				$title = $this->_sTitle;
				
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
				self::$endpoint   => $this->_sTitle,
				'customer-logout' => 'Thoát',
			);
			return $myorder;
		}
		public function endpoint_content(){
			wp_enqueue_style("poka_myaccount_css_builder_product", _POKA_PLUGIN_LIB_URL_ . "myaccount/css/woocommerce-builder-product.min.css");
			require _POKA_PLUGIN_LIB_PATH_ . "myaccount/woocommerce-builder-product.php";
		}
	}