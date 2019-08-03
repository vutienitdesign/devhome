<?php
class IartAsiaController {
	private $sPath = "";
	private $sTask = "";
	private $sPage = "";
	
	public function __construct() {
		$this->sPath    = realpath(dirname(__FILE__));
		$this->sTask    = isset($_REQUEST["task"]) ? $_REQUEST["task"] : '';
		$this->sPage    = isset($_REQUEST["page"]) ? $_REQUEST["page"] : '';
    }
	
    //======================Dieu Huong======================
	public function doAction() {
		$sTask = $this->sTask;
		if(isset($_REQUEST['poka-type']) && $_REQUEST['poka-type'] == 'ajax'){
			//Ajax
			switch($sTask) {
				default:
					die("NOTHING");
					break;
			}
		}else{
			//Xu Ly
			switch($sTask) {
				default:
					$this->showDisplay();
					break;
			}
		}
	}
	
	/*====================== Xu Ly ======================*/
	private function showDisplay() {
		require_once $this->sPath . '/tpl/template/display.php';
	}
	
	/*====================== Chay Ajax ======================*/
}



