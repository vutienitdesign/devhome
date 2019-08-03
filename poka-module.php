<?php
/*
Plugin Name: Custom Website
Plugin URI: https://www.google.com/
Description: The easiest way to build your website ...
Author: Custom Website
Version: 1.1
Author URI: https://www.google.com/
*/

require_once 'define.php';

//Plugin Activation
include_once 'activation.php';
register_activation_hook(__FILE__, array('POKA_Activation', 'activation_plugin'));

//Plugin Load
add_action('plugins_loaded', array('POKA_Plugin', 'init'));
class POKA_Plugin{
	public static function init(){
		self::importCoreClass();
		
		if(is_admin()){
			add_action('admin_menu',            array('POKA_Controller', 'loadDashboardMenu'));
			add_action('admin_head',            array('POKA_Controller', 'loadHeader'));
			add_action('admin_enqueue_scripts', array('POKA_Controller', 'registerExtraScript'));
		}else{
			ob_start();
			
			add_shortcode('pokamodule',     array('POKA_Controller', 'doShortCode'));
			add_action('wp_head',               array('POKA_Controller', 'loadHeader'));
			add_action('wp_footer',             array('POKA_Controller', 'loadFooter'));
			add_action('wp_enqueue_scripts',    array('POKA_Controller', 'registerExtraScript'));
		}
		
		add_action("wp_ajax_pokamodule_ajax",           array('POKA_Controller', 'doAction'));
		add_action("wp_ajax_nopriv_pokamodule_ajax",    array('POKA_Controller', 'doAction'));
	}
	
	/*IMPORT CORE CLASS*/
	public function importCoreClass(){
		$aCoreClass = array(
			'PMCommon'            => 'class_common.php',
			'POKA_Session'        => 'class_session.php',
			'POKA_Helper'         => 'class_helper.php',
			'POKA_Controller'     => 'class_controller.php',
			'Poka_Widget'         => 'class_widget.php',
			'POKA_Custom_MetaBox' => 'class_metabox.php',
			'POKA_Custom_Table'   => 'class_table.php',
			'POKA_Extension'      => 'class_extension.php',
			'POKA_TinyMCE'        => 'class_tinymce.php',
		);
		
		foreach($aCoreClass as $key => $value){
			if(!class_exists($key)){
				require_once(_POKA_PLUGIN_PATH_ . "libraries/$value");
			}
		}
	}
}