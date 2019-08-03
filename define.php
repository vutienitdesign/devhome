<?php
	defined('ABSPATH') or die('No script kiddies please!');
	
	/*====================URL==========================*/
	define("_POKA_HOME_URI_",               get_home_url());
	define("_POKA_THEME_DIRECTORY_URI_",    get_stylesheet_directory_uri() . "/");
	define("_POKA_PLUGIN_URL_",             plugin_dir_url(__FILE__));
	define("_POKA_PLUGIN_ASSET_URL_",       _POKA_PLUGIN_URL_ . 'assets/');
	
	define("_POKA_PLUGIN_COMPONENT_URL_",  _POKA_PLUGIN_URL_ . "components/");
	define("_POKA_PLUGIN_LIB_URL_",        _POKA_PLUGIN_URL_ . "libraries/");
	
	/*====================PATH==========================*/
	define("_POKA_PLUGIN_PATH_",            plugin_dir_path(__FILE__));
	define("_POKA_PLUGIN_JS_PATH_",         _POKA_PLUGIN_PATH_ . "js/");
	define("_POKA_PLUGIN_CSS_PATH_",        _POKA_PLUGIN_PATH_ . "css/");
	define("_POKA_PLUGIN_TEMPLATE_PATH_",   _POKA_PLUGIN_PATH_ . "tpl/");
	define("_POKA_PLUGIN_LIB_PATH_",        _POKA_PLUGIN_PATH_ . "libraries/");
	define("_POKA_PLUGIN_COMPONENT_PATH_",  _POKA_PLUGIN_PATH_ . "components/");
	define("_POKA_HOME_PATH_",              ABSPATH);
	
	define("_REAL_TIME_",                   (time()+25202));
