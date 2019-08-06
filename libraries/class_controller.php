<?php
	class POKA_Controller{
		private $sPath = "";
		private $sPage = "";
		private $sTask = "";
		
		public function __construct(){
			$this->sPath = realpath(dirname(__FILE__));
			$this->sPage = !empty($_REQUEST["page"]) ? trim($_REQUEST["page"]) : "";
			$this->sTask = !empty($_REQUEST["task"]) ? trim($_REQUEST["task"]) : "";
		}
		
		/*PROCESS TASK*/
		public static function doAction($childTask = ""){
			$oTsController = new POKA_Controller;
			//IMPORT CURRENT CONTROLLER
			if(is_file(_POKA_PLUGIN_PATH_ . "components/" . $oTsController->sPage . "/" . $oTsController->sPage . ".php")){
				require_once(_POKA_PLUGIN_PATH_ . "components/" . $oTsController->sPage . "/" . $oTsController->sPage . ".php");
				
				$controllerName = PMCommon::convertCapitalizeString($oTsController->sPage, "-") . "Controller";
				
				$oController = new $controllerName;
				$oController->doAction();
			}else{
				echo "Class '" . $oTsController->sPage . ".php' not found.";
				exit();
			}
		}
		
		/*APPLY SHORTCODE ACTIONS*/
		/*Tham so com = page = ...*/
		public function doShortCode($atts = array(), $content = null, $tag = ''){
			$atts = array_change_key_case((array)$atts, CASE_LOWER);
			
			if(empty($atts['task'])){
				$atts['task'] = 'nothing';
			}
			if(empty($atts['com'])){
				$atts['com'] = '';
			}
			
			ob_start();
			//IMPORT CURRENT CONTROLLER
			if($atts['com'] != "" && is_file(_POKA_PLUGIN_PATH_ . "components/" . $atts["com"] . "/" . $atts["com"] . ".php")){
				require_once(_POKA_PLUGIN_PATH_ . "components/" . $atts["com"] . "/" . $atts["com"] . ".php");
				
				$controllerName = PMCommon::convertCapitalizeString($atts["com"], "-") . "Controller";
				$oController    = new $controllerName;
				$oController->doShortCode($atts);
			}else{
				echo "Attribute 'com' is incorrect or Class '" . $atts["com"] . ".php' not found.";
				exit();
			}
			
			return ob_get_clean();
		}
		
		/*LOAD LEFT MENU FOR ADMIN SITE*/
		public static function loadDashboardMenu(){
			if(function_exists('add_menu_page')){
				ob_start();
				
				add_menu_page('iArt Asia', 'iArt Asia', 'manage_options', 'iart-asia', array(
					'POKA_Controller',
					'doAction'
				), _POKA_PLUGIN_URL_ . 'images/iart-asia.png', 2);
				
				add_submenu_page('iart-asia', 'Không gian lớn', 'Không gian lớn', 'manage_options', 'iart-decorate-large', array(
					'POKA_Controller',
					'doAction'
				));
				
				add_submenu_page('iart-asia', 'Không gian vừa', '- Không gian vừa', 'manage_options', 'iart-decorate-medium', array(
					'POKA_Controller',
					'doAction'
				));
				
				add_submenu_page('iart-asia', 'Không gian nhỏ', '-- Không gian nhỏ', 'manage_options', 'iart-decorate-small', array(
					'POKA_Controller',
					'doAction'
				));
				
				add_submenu_page('iart-asia', 'Settings', 'Settings', 'manage_options', 'iart-settings', array(
					'POKA_Controller',
					'doAction'
				));
			}
		}
		
		public function loadHeader(){
			$ajax_nonce = wp_create_nonce('ajax-security-code');
			
			if(is_user_logged_in()){
				$sAjaxURL = "nopriv_"; // Authenticated actions
			}else{
				$sAjaxURL = ""; // Non-admin actions
			}
			
			$sAjaxURL = admin_url('admin-ajax.php') . '?action=' . $sAjaxURL;
			
			echo "<script type='text/javascript'>
					/* <![CDATA[ */
					var MyAjax = {
						ajaxurl		  : '" . $sAjaxURL . "',
						security_code : '" . $ajax_nonce . "',
						sHomeUrl : '" . get_home_url() . "',
					};
					/* ]]> */
				</script>";
			
			$code   = get_option( "_poka_code_header");
			if(!empty($code)){
				if(!is_admin()){
					echo stripslashes($code);
				}
			}
		}
		
		public function loadFooter(){
			$call   = get_option( "_poka_number_call");
			if(!empty($call)){
				$sNumber = substr($call, 0,4) . '.' . substr($call, 4,3) . '.' . substr($call, 7,3);
				$html = '<div id="text-8">
					        <div class="zoomIn"></div>
					        <div class="pulse"></div>
					        <div class="tada"><a href="tel:'.$call.'"></a></div>
					        <div class="text">'.$sNumber.'</div>
						</div>
						';
				echo $html;
			}
			
			$code   = get_option( "_poka_code_footer");
			if(!empty($code)){
				echo stripslashes($code);
			}
		}
		
		/*REGISTER EXTRA CSS & JS SCRIPTS*/
		public static function registerExtraScript(){
			$components = glob(_POKA_PLUGIN_COMPONENT_PATH_ . '*', GLOB_ONLYDIR);
			if(count($components)){
				foreach($components as $directory){
					$component = basename($directory);
					
					if(!empty($component)){
						if(is_file(_POKA_PLUGIN_PATH_ . "components/$component/js/$component.js")){
							wp_register_script($component, _POKA_PLUGIN_URL_ . "components/$component/js/$component.js", '', '', 'all');
						}
						if(is_file(_POKA_PLUGIN_PATH_ . "components/$component/css/$component.css")){
							wp_register_style($component, _POKA_PLUGIN_URL_ . "components/$component/css/$component.css", 'all');
						}
					}
				}
			}
			//End PLUGIN COMPONENTS
			
			//Start CORE PLUGIN
			if(is_admin()){
//					wp_enqueue_script('pokamodule-admin', _POKA_PLUGIN_URL_ . "js/admin.js", '', '', true);
//					wp_enqueue_style('pokamodule-admin', _POKA_PLUGIN_URL_ . 'css/admin_global.min.css', '', '', 'all');
			}else{
//					wp_enqueue_script('pokamodule-frontend', _POKA_PLUGIN_URL_ . "js/frontend.js", '', '', true);
				wp_enqueue_style('pokamodule-frontend', _POKA_PLUGIN_URL_ . 'css/frontend_global.min.css', '', '', 'all');
			}
			//End CORE PLUGIN
			
			PMCommon::load_lib_assets('font-awesome/css/', 'font-awesome.min.css', 'css');
		}
	}