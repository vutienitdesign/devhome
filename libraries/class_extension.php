<?php
	new POKA_Extension();
	class POKA_Extension{
		public function __construct(){
			if(is_admin()){
				$this->isAdmin();
			}else{
				$this->isFontEnd();
			}
		}
		
		private function isAdmin(){
		}
		
		private function isFontEnd(){
		}
	}