<?php
	if(!empty($_POST['data'])){
		global $pokaHelper;
		$fileName = $pokaHelper->saveImage($_POST['data']);
		
		if(!empty($fileName)){
			if(!session_id()){
				session_start();
			}
			
			$nTabContent  = $_POST['tab-content'];
			
			//Xoa bo anh cu
			$imageRemove = $_SESSION['iart_config_product']['step3']['tab-content-' . $nTabContent]['custom-info']['image'];
			if(!empty($imageRemove)){
				$folderPath = _POKA_PLUGIN_PATH_ . 'images/builder-product/' . get_current_user_id() . '/' . $imageRemove;
				@unlink($folderPath);
			}
			
			//Cap nhat anh moi
			$_SESSION['iart_config_product']['step3']['tab-content-' . $nTabContent]['custom-info']['image'] = $fileName;
		}
	}