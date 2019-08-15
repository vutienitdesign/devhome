<?php
	class POKA_Activation{
		public static function activation_plugin(){
			self::create_table();
//			self::create_page();
		}
		
		public function create_table(){
			global $wpdb;
			
			$sPrefix = $wpdb->prefix;
			
			//Tabel log_click_build
			$tbl = $sPrefix . 'log_click_build';
			if($wpdb->get_var("show tables like '$tbl'") != $tbl){
				$sql = "CREATE TABLE " . $tbl . " (
						`id` bigint(20) NOT NULL AUTO_INCREMENT,
						`id_temp` 	text,
						`total` 	int(11),
						`type` 	varchar(255),
						`date_update` 	int(11),
						PRIMARY KEY (id)
				) DEFAULT CHARACTER SET = 'utf8' DEFAULT COLLATE 'utf8_general_ci';";
				
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}
			
			//Tabel manager_builder_product
			$tbl = $sPrefix . 'manager_builder_product';
			if($wpdb->get_var("show tables like '$tbl'") != $tbl){
				$sql = "CREATE TABLE " . $tbl . " (
						`id` bigint(20) NOT NULL AUTO_INCREMENT,
						`id_temp` 	text,
						`user_id` 	int(11),
						`name` 	varchar(255),
						`data` 	longtext,
						`date` 	int(11),
						`date_update` 	int(11),
						`status` 	int(11),
						PRIMARY KEY (id)
				) DEFAULT CHARACTER SET = 'utf8' DEFAULT COLLATE 'utf8_general_ci';";
				
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}
			
			//Tabel decorate_large
			$tbl = $sPrefix . 'decorate_large';
			if($wpdb->get_var("show tables like '$tbl'") != $tbl){
				$sql = "CREATE TABLE " . $tbl . " (
						`id` bigint(20) NOT NULL AUTO_INCREMENT,
						`name` varchar(255),
						PRIMARY KEY (id)
				) DEFAULT CHARACTER SET = 'utf8' DEFAULT COLLATE 'utf8_general_ci';";
				
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}
			
			//Tabel decorate_large
			$tbl = $sPrefix . 'decorate_medium';
			if($wpdb->get_var("show tables like '$tbl'") != $tbl){
				$sql = "CREATE TABLE " . $tbl . " (
						`id` bigint(20) NOT NULL AUTO_INCREMENT,
						`decorate_large` int(11),
						`name` varchar(255),
						`name_show` varchar(255),
						`max` int(11),
						`tag` text,
						PRIMARY KEY (id)
				) DEFAULT CHARACTER SET = 'utf8' DEFAULT COLLATE 'utf8_general_ci';";
				
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}
			
			//Tabel decorate_small
			$tbl = $sPrefix . 'decorate_small';
			if($wpdb->get_var("show tables like '$tbl'") != $tbl){
				$sql = "CREATE TABLE " . $tbl . " (
						`id` bigint(20) NOT NULL AUTO_INCREMENT,
						`decorate_large` int(11),
						`decorate_medium` int(11),
						`name` varchar(255),
						`cat` int(11),
						`tag` text,
						PRIMARY KEY (id)
				) DEFAULT CHARACTER SET = 'utf8' DEFAULT COLLATE 'utf8_general_ci';";
				
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}
		}
		
		public function create_page(){
			global $wpdb;
			
			$tblPost = $wpdb->prefix . 'posts';
			$userID = wp_get_current_user();
			$userID = $userID->ID;
			
			$arrInstall = array(
				array(
					'title' => 'Đăng nhập',
					'slug' => 'login',
					'content' => '[pokamodule com="pokamodule-shortcode" task="login-user"]'
				),
			);
			
			foreach($arrInstall as $k => $v){
				if(null === $wpdb->get_row("SELECT `post_name` FROM `{$tblPost}` WHERE `post_name` = '{$v['slug']}'",'ARRAY_A')){
					$page = array(
						'post_title'   => $v['title'],
						'post_status'  => 'publish',
						'post_author'  => $userID,
						'post_content' => $v['content'],
						'post_type'    => 'page',
						'post_slug'    => $v['slug']
					);
					wp_insert_post($page);
				}
			}
		}
	}