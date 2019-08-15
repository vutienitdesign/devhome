<?php
	class PokaModuleShortcodeController{
		private $sPath = "";
		private $sTask = "";
		private $sPage = "";
		
		public function __construct(){
			/*=================START Demo Load File=======================*/
			//		load lib components
			//		PMCommon::load_lib_components('pokamodule-settings/', 'abc.min.css', 'css');
			//		PMCommon::load_lib_components('pokamodule-settings/', 'abc.js', 'js');
			
			//		load lib assets
			//		PMCommon::load_lib_assets('font-awesome/css/', 'font-awesome.min.css', 'css');
			/*=================END Demo Load File=======================*/
			
			$this->sPath  = realpath(dirname(__FILE__));
			$this->sTask  = isset($_REQUEST["task"]) ? $_REQUEST["task"] : '';
			$this->sPage  = isset($_REQUEST["page"]) ? $_REQUEST["page"] : '';
		}
		
		/*=============Chay Shortode=============*/
		public function doShortCode($aAtts = array()){
			switch($aAtts["task"]){
				//Hien thi bai viet
				case "list-post":
					$this->showListPost($aAtts);
					break;
					
				//Dat Hang Nhanh
				case "order-product":
					$this->showOrderProduct($aAtts);
					break;
				
				case "nothing":
				default:
					echo "NOTHING";
					break;
			}
		}
		
		/*=============Chay Ajax=============*/
		public function doAction(){
			switch($_REQUEST['task']){
				case "manager-image":
					$this->ajaxManagerImage();
					break;
				
				//Product Step1
				case "config-product-step1":
					$this->showConfigProductStep1();
					break;
				
				//Product Step2
				case "config-product-step2":
					$this->showConfigProductStep2();
					break;
				
				//Load Product Popup
				case "ajax-load-product":
					$this->showLoadProduct();
					break;
				
				//Add Product
				case "ajax-add-product":
					$this->showAddProduct();
					break;
				
				//Set Cart
				case "ajax-update-session":
					$this->updateSession();
					break;
				
				//POPUP thông báo tồn tại dữ liệu cũ
				case "msg-session-data":
					$this->msgSessionData();
					break;
				
				//Upload Image
				case "ajax-upload-image":
					$this->ajaxUploadImage();
					break;
				
				//Get SET DO
				case "ajax-get-set-tag":
					$this->ajaxGetSetTag();
					break;
				
				//Get Data Set Do
				case "ajax-load-set-product":
					$this->ajaxGetSetProduct();
					break;
				
				//Add product set do
				case "ajax-add-product-set":
					$this->ajaxAddProductSet();
					break;
				
				//Add product set do
				case "ajax-all-set-product":
					$this->ajaxAddSetProductSet();
					break;
				
				//Get All Data Set Do
				case "ajax-load-all-set-product":
					$this->ajaxGetAllSetProduct();
					break;
					
				//Remove Custom info
				case "ajax-remove-custom-info":
					$this->ajaxRemoveCustomInfo();
					break;
				
				//Log Click Button
				case "ajax-log-click":
					$this->ajaxLogClick();
					break;
				
				//View Demo
				case "ajax-view-demo":
					$this->ajaxViewDemo();
					break;
					
				default:
					die("NOTHING");
					break;
			}
		}
		
		/*====================== DO SHORTCODE ======================*/
		//[pokamodule com="pokamodule-shortcode" task="list-post" category="all" thumbnail="1"]
		/*
		 * show_category         : related, all (Hien thi theo Category)
		 * ids              : 123,456,789 (Hien thi theo id bai viet)
		 * limit            : 10 - Default: 5 (So luong bai viet)
		 * class            : abc def (Dat Class)
		 * thumbnail        : 0 or 1 (Hien thi hinh anh)
		 * size_thumbnail   : thumbnail, medium, large, full, (150,150) - Default: thumbnail
		 * date             : 0 or 1 (Hien thi ngay viet)
		 * author           : 0 or 1 (Hien thi tac gia)
		 * category         : 0 or 1 (Hien thi danh sach chuyen muc)
		 * descrition       : 0 or 1 (Hien thi mo ta)
		 * number_descrition: 200 - Default: 150
		 * dots             : 0 or 1 (Hien thi dau ...)
		 * view_more        : 0 or 1 (Hien thi nut xem them)
		 */
		private function showListPost($aAtts = array()){
			require $this->sPath . '/tpl/template/list-post/display.php';
		}
		
		//[pokamodule com="pokamodule-shortcode" task="order-product"]
		private function showOrderProduct($aAtts = array()){
			$productID = isset($_GET['order-product']) ? $_GET['order-product'] : '';
			if($productID > 0){
				require $this->sPath . '/tpl/template/order-product/display.php';
			}
		}
		
		/*====================== DO ACTION AJAX======================*/
		private function ajaxManagerImage(){
			check_ajax_referer('ajax-security-code', 'security');
			require_once $this->sPath . '/tpl/ajax/manager-image/display.php';
			die();
		}
		
		private function showConfigProductStep1(){
			check_ajax_referer('ajax-security-code', 'security');
			require_once $this->sPath . '/tpl/ajax/config-product/step1/display.php';
			die();
		}
		
		private function showConfigProductStep2(){
			check_ajax_referer('ajax-security-code', 'security');
			require_once $this->sPath . '/tpl/ajax/config-product/step2/display.php';
			die();
		}
		
		private function showLoadProduct(){
			check_ajax_referer('ajax-security-code', 'security');
			require_once $this->sPath . '/tpl/ajax/load-product/display.php';
			die();
		}
		
		private function showAddProduct(){
			check_ajax_referer('ajax-security-code', 'security');
			require_once $this->sPath . '/tpl/ajax/add-product/display.php';
			die();
		}
		
		private function updateSession(){
			check_ajax_referer('ajax-security-code', 'security');
			require_once $this->sPath . '/tpl/ajax/update-session/display.php';
			die();
		}
		
		private function msgSessionData(){
			check_ajax_referer('ajax-security-code', 'security');
			require_once $this->sPath . '/tpl/ajax/msg-session-data/display.php';
			die();
		}
		
		private function ajaxUploadImage(){
			check_ajax_referer('ajax-security-code', 'security');
			require_once $this->sPath . '/tpl/ajax/upload-image/display.php';
			die();
		}
		
		private function ajaxGetSetTag(){
			check_ajax_referer('ajax-security-code', 'security');
			require_once $this->sPath . '/tpl/ajax/set-do/display.php';
			die();
		}
		
		private function ajaxGetSetProduct(){
			check_ajax_referer('ajax-security-code', 'security');
			require_once $this->sPath . '/tpl/ajax/set-do/load-data.php';
			die();
		}
		
		private function ajaxAddProductSet(){
			check_ajax_referer('ajax-security-code', 'security');
			require_once $this->sPath . '/tpl/ajax/set-do/add-product.php';
			die();
		}
		
		private function ajaxAddSetProductSet(){
			check_ajax_referer('ajax-security-code', 'security');
			require_once $this->sPath . '/tpl/ajax/set-do/add-all-product-set.php';
			die();
		}
		
		private function ajaxGetAllSetProduct(){
			check_ajax_referer('ajax-security-code', 'security');
			require_once $this->sPath . '/tpl/ajax/set-do/load-all-data.php';
			die();
		}
		
		private function ajaxRemoveCustomInfo(){
			check_ajax_referer('ajax-security-code', 'security');
			require_once $this->sPath . '/tpl/ajax/update-session/delete.php';
			die();
		}
		
		private function ajaxLogClick(){
			check_ajax_referer('ajax-security-code', 'security');
			require_once $this->sPath . '/tpl/ajax/log-click/display.php';
			die();
		}
		
		private function ajaxViewDemo(){
			check_ajax_referer('ajax-security-code', 'security');
			require_once $this->sPath . '/tpl/ajax/view-demo/display.php';
			die();
		}
	}