<?php
class IartDecorateLargeController{
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
			check_ajax_referer('ajax-security-code', 'security');
			
			switch($sTask) {
				case "ajax-table-editinline":
					$this->ajaxEditTable();
					break;
				case "ajax-table-update":
					$this->ajaxUpdateTable();
					break;
				default:
					die("NOTHING");
					break;
			}
		}else{
			$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
			switch ($action){
				case 'delete':
					$this->deleteData();
					break;
				case 'add':
				case 'edit':
					$this->displayAddEdit($action);
					break;
				default:
					require_once $this->sPath . '/tpl/template/class-tbl.php';
					require_once $this->sPath . '/tpl/template/display.php';
					break;
			}
		}
	}
	
	public function displayAddEdit($action){
		require_once $this->sPath . '/tpl/template/add-edit.php';
	}
	
	//Xoa Phan tu
	public function deleteData(){
		require_once $this->sPath . '/tpl/template/delete-data.php';
	}
	
	/*====================== Chay Ajax ======================*/
	//Edit Now Tabel
	private function ajaxEditTable(){
		require_once $this->sPath . '/tpl/ajax/table/edit-table/display.php';
		die();
	}
	
	private function ajaxUpdateTable(){
		require_once $this->sPath . '/tpl/ajax/table/update-table/display.php';
		die();
	}
}



