<?php
	class PokamoduleRedirectsController {
		private $sPath 				= "";
		
		public function __construct() {
			$this->sPath     = realpath(dirname(__FILE__));
			$this->sSelfURL  = PMCommon::getSelfURL("component");
			$this->create_table();
		}
		
		public function doAction() {
			$sTask	= PMCommon::trim_all(!empty($_REQUEST["task"]) ? trim($_REQUEST["task"]) : "");
			
			switch($sTask) {
				case 'quick-edit':
					$this->quick_edit();
					break;
				
				case 'save-edit':
					$this->save_edit();
					break;
				
				default:
					$this->showDashboard();
					break;
			}
		}
		
		private function showDashboard() {
			wp_enqueue_style('pokamodule-redirects');
			wp_enqueue_script('pokamodule-redirects');
			
			$action = @$_REQUEST['action'];
			
			switch ($action){
				case 'delete':
					$this->delete_data();
					break;
				
				case 'create':
					$this->create();
					break;
				default:
					require_once $this->sPath . '/tpl/model.php';
					require_once $this->sPath . '/tpl/display.php';
					break;
			}
		}
		
		public function create_table(){
			if(is_admin() && $_GET['page'] == 'pokamodule-redirects'){
				global $wpdb;
				$table_name      = $wpdb->prefix."redirects";
				$charset_collate = $wpdb->get_charset_collate();
				$sql = 'CREATE TABLE IF NOT EXISTS '.$table_name.'(
		        id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		        request VARCHAR(255) NOT NULL,
		        destination VARCHAR(255) NOT NULL,
		        latest_update INT(11)
		        ) '.$charset_collate;
				
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				
				dbDelta( $sql );
			}
		}
		
		public function create()
		{
			$submit = @$_POST["submit"];
			switch($submit) {
				case "Save Redirect":
					$msg = $this->create_item($_POST);
					break;
				
				case "Import Data":
					$msg= $this->import_excel(@$_FILES["file-redirect"]);
					break;
				
				case "Export Current POKA Redirect\'s to Excel":
					$msg= $this->export_excel();
					break;
				
				case "Clear Redirect List (DO SO WITH CAUTION!)":
					$msg= $this->clear_redirect();
					break;
				
				default:
					break;
			}
			
			require_once $this->sPath . '/tpl/create.php';
		}
		
		public function create_item($data){
			global $wpdb;
			$table  = $wpdb->prefix."redirects";
			$result = $wpdb->insert(
				$table,
				array(
					'request'       => trim(@$data["request"]),
					'destination'   => trim(@$data["destination"]),
					'latest_update' => _REAL_TIME_
				),
				array(
					'%s',
					'%s',
					'%d'
				)
			);
			
			if ($result) {
				return $msg = PMCommon::getMsgBE(array('Created!', '<a href="'. $this->create_url() .'">← Back to Table list</a>'));
			}else{
				return $msg = PMCommon::getMsgBE(array('An error occurred. Please try again later!'), "error");
			}
		}
		
		//Xoa Phan tu
		public function delete_data(){
			global $wpdb;
			$article_id = @$_REQUEST['article'];
			$table      = $wpdb->prefix."redirects";
			if(!is_array($_REQUEST['article'])){
				$name   = 'security_code';
				$action = 'delete_id_' . $_GET['article'];
				
				if(!isset($_GET[$name]) || empty($_GET[$name]) || !check_admin_referer($action,$name)){
				}else{
					$wpdb->delete( $table, array("id"=>$article_id), array("%d") );
				}
			}else{
				foreach ( $_REQUEST['article'] as $key => $article_id) {
					$wpdb->delete( $table, array("id"=>$article_id), array("%d") );
				}
			}
			
			$url = $this->create_url( array( "msg"=>1, "success"=>count((array)$_REQUEST['article']) ) );
			
			wp_redirect($url);
			exit();
		}
		
		public function quick_edit()
		{
			require_once $this->sPath . '/tpl/quick-edit.php';
			wp_die();
		}
		
		public function save_edit()
		{
			global $wpdb;
			$fdata  = !empty($_REQUEST["fdata"]) ? trim($_REQUEST["fdata"]) : "";
			parse_str($fdata, $data);
			
			$table  = $wpdb->prefix."redirects";
			$result = $wpdb->update(
				$table,
				array(
					'request'       => trim(@$data["request"]),
					'destination'   => trim(@$data["destination"]),
					'latest_update' => _REAL_TIME_
				),
				array('id' => intval(@$data["id"])),
				array(
					'%s',
					'%s',
					'%d'
				),
				array('%d')
			);
			
			$data["result"]        = $result ? 1 : 0;
			$data["latest_update"] = date("Y-m-d h:i:s a", _REAL_TIME_);
			echo json_encode($data);
			wp_die();
		}
		
		private function import_excel($file)
		{
			global $wpdb;
			set_time_limit(0);
			require_once _POKA_PLUGIN_PATH_.'libraries/PHPExcel/PHPExcel/IOFactory.php';
			$param = date("Ymdihs");
			
			// Kiểm tra đuôi file
			$type_file = pathinfo($file['name'], PATHINFO_EXTENSION);
			$type_fileAllow = array('xls', 'xlsx');
			
			$target_dir  = $this->sPath.'/temp/';
			$target_file = $target_dir."data_$param.".$type_file;
			
			if (!in_array(strtolower($type_file), $type_fileAllow)) {
				return $msg = PMCommon::getMsgBE(array('Filetype you are attempting to upload is not allowed. Please choose: .xls .xlsx'), "error");
			}
			
			if (move_uploaded_file($file["tmp_name"], $target_file)) {
				
				echo $message = '<div class="notice notice-success is-dismissible">
            <p>Import data success !</p>
            <p><a href="'. get_home_url() .'/wp-admin/admin.php?page=pokamodule-redirects">Back to List Redirects</a></p>
            </div>';
				
				if ($target_file) {
					$objPHPExcel = PHPExcel_IOFactory::load($target_file);
					$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					global $wpdb;
					if (!empty($sheetData)) {
						foreach($sheetData as $k => $row):
							
							if($k<=1) continue;
							// Xử lý chuẩn hóa dữ liệu đầu vào
							$request       = trim(@$row['A']);
							$destination   = trim(@$row['B']);
							$latest_update = !empty($row['C']) ? strtotime($row['C']) : _REAL_TIME_;
							
							$wpdb->insert(
								$wpdb->prefix.'redirects',
								array(
									'request'        => $request,
									'destination'    => $destination,
									'latest_update'  => $latest_update
								),
								array(
									'%s',
									'%s',
									'%d'
								)
							);
						endforeach;
					}
				}
			} else {
				return $msg = PMCommon::getMsgBE(array('There has been an error'), "error");
			}
		}
		
		private function export_excel()
		{
			require_once $this->sPath . '/tpl/model.php';
			$model = new Model();
			$model->prepare_items();
			$data  = $model->all_data();
			
			if (empty($data)) return;
			
			require_once _POKA_PLUGIN_PATH_.'libraries/PHPExcel/PHPExcel.php';
			$objPHPExcel = new PHPExcel();
			
			$objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue('A1', 'Request')
			            ->setCellValue('B1', 'Destination')
			            ->setCellValue('C1', 'Latest update');
			
			//set gia tri cho cac cot du lieu
			foreach ($data as $row_index => $row)
			{
				$objPHPExcel->setActiveSheetIndex(0)
				            ->setCellValue('A'.($row_index+2), $row['request'])
				            ->setCellValue('B'.($row_index+2), $row['destination'])
				            ->setCellValue('C'.($row_index+2), date("Y-m-d h:i:s a", $row['latest_update']));
			}
			
			//ghi du lieu vao file,định dạng file excel 2007
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$filename  = 'export_data_redirects.xlsx';
			$full_path = $this->sPath.'/temp/'.$filename;//duong dan file
			$objWriter->save($full_path);
			
			$filename  = basename($filename);
			$filepath  = $this->sPath.'/temp/'.$filename;
			if(!empty($filename) && file_exists($filepath)){
				$fileurl = _POKA_PLUGIN_COMPONENT_URL_."pokamodule-redirects/temp/export_data_redirects.xlsx";
				
				header('Location: '.$fileurl);
			}else{
				return 'The file does not exist.';
			}
		}
		
		public function clear_redirect()
		{
			global $wpdb;
			$result = $wpdb->query( "TRUNCATE TABLE ".$wpdb->prefix."redirects" );
			
			if ($result) {
				return $msg = PMCommon::getMsgBE(array('Redirects Empty!', '<a href="'. $this->create_url() .'">← Back to Table list</a>'));
			}else{
				return $msg = PMCommon::getMsgBE(array('An error occurred. Please try again later!'), "error");
			}
		}
		
		public function create_url($args = array())
		{
			$default = array(
				'orderby'        => @$_REQUEST['orderby'],
				'order'          => @$_REQUEST['order'],
				's'              => @$_REQUEST['s'],
				'paged'          => (intval(@$_REQUEST['paged']) ? intval(@$_REQUEST['paged']) : false),
			);
			
			return $url = add_query_arg(
				wp_parse_args($args, $default),
				get_admin_url() . 'admin.php?page=' . $_REQUEST["page"]
			);
		}
		
		
		// ========================================================================================
		/**
		 * redirect function
		 * Read the list of redirects and if the current page
		 * is found in the list, send the visitor on her way
		 * @access public
		 * @return void
		 */
		public function redirect() {
			global $wpdb;
			if (is_admin()) {
				return;
			}
			// this is what the user asked for (strip out home portion, case insensitive)
			$obj         = new PokamoduleRedirectsController();
			$userrequest = str_ireplace(get_option('home'),'',$obj->get_address());
			$userrequest = rtrim($userrequest,'/');
			
			$sql       = "SELECT `request`,`destination` FROM ".$wpdb->prefix."redirects WHERE `request`='".$userrequest."' OR `request`='".$userrequest."/'";
			$redirects = $wpdb->get_row($sql, ARRAY_A);
			
			if (!empty($redirects)) {
				
				$do_redirect = '';
				// compare user request to each 301 stored in the db
				$storedrequest = $redirects["request"];
				$destination   = $redirects["destination"];
				// check if we should use regex search
				if ( strpos($storedrequest,'*') !== false) {
					// wildcard redirect
					
					// don't allow people to accidentally lock themselves out of admin
					if ( strpos($userrequest, '/wp-login') !== 0 && strpos($userrequest, '/wp-admin') !== 0 ) {
						// Make sure it gets all the proper decoding and rtrim action
						$storedrequest = str_replace('*','(.*)',$storedrequest);
						$pattern = '/^' . str_replace( '/', '\/', rtrim( $storedrequest, '/' ) ) . '/';
						$destination = str_replace('*','$1',$destination);
						$output = preg_replace($pattern, $destination, $userrequest);
						if ($output !== $userrequest) {
							// pattern matched, perform redirect
							$do_redirect = $output;
						}
					}
				}
				elseif(urldecode($userrequest) == rtrim($storedrequest,'/')) {
					// simple comparison redirect
					$do_redirect = $destination;
				}
				
				// redirect. the second condition here prevents redirect loops as a result of wildcards.
				if ($do_redirect !== '' && trim($do_redirect,'/') !== trim($userrequest,'/')) {
					// check if destination needs the domain prepended
					if (strpos($do_redirect,'/') === 0){
						$do_redirect = home_url().$do_redirect;
					}
					header ('HTTP/1.1 301 Moved Permanently');
					header ('Location: ' . $do_redirect);
					exit();
				}
				else { unset($redirects); }
				
			}
		} // end funcion redirect
		
		/**
		 * getAddress function
		 * utility function to get the full address of the current request
		 * credit: http://www.phpro.org/examples/Get-Full-URL.html
		 * @access public
		 * @return void
		 */
		public function get_address() {
			// return the full address
			return $this->get_protocol().'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		} // end function get_address
		
		public function get_protocol() {
			// Set the base protocol to http
			$protocol = 'http';
			
			// check for https
			// if ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" ) {
			//     $protocol .= "s";
			// }
			
			if (get_option('home')[4] == "s") {
				$protocol .= "s";
			}
			
			return $protocol;
		} // end function get_protocol
		
		public function str_ireplace($search,$replace,$subject){
			$token = chr(1);
			$haystack = strtolower($subject);
			$needle = strtolower($search);
			while (($pos=strpos($haystack,$needle))!==FALSE){
				$subject  = substr_replace($subject,$token,$pos,strlen($search));
				$haystack = substr_replace($haystack,$token,$pos,strlen($search));
			}
			$subject = str_replace($token,$replace,$subject);
			return $subject;
		}
	}
