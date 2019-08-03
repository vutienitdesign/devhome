<?php
	global $pokaSession;
	$pokaSession = new POKA_Session();
	class POKA_Session{
		public $_ssName;
		
		public function __construct(){
			if(!session_id()){
				session_start();
			}
			
			$this->_ssName = 'poka_' . md5('pokamedia_1.0');
			if(!isset($_SESSION[$this->_ssName])){
				$_SESSION[$this->_ssName] = array();
			}
		}
		
		public function set($name = null, $value = null){
			if($name != null || !empty($name)){
				$_SESSION[$this->_ssName][$name] = $value;
			}
		}
		
		public function delete($name = null){
			if($name != null){
				unset($_SESSION[$this->_ssName][$name]);
			}
		}
		
		public function get($name = null, $default = null){
			if($name == null){
				return $_SESSION[$this->_ssName];
			}else{
				return (!isset($_SESSION[$this->_ssName][$name]))?$default:$_SESSION[$this->_ssName][$name];
			}
		}
		
		public function reset(){
			$_SESSION[$this->_ssName] = array();
		}
	}