<?php
	if(!empty($_POST['data'])){
		global $pokaHelper;
		$fileName = $pokaHelper->saveImage($_POST['data']);
		
		if(!empty($fileName)){
			if(!session_id()){
				session_start();
			}
			
			$nTabContent  = $_POST['dataID'];
			if(!empty($_SESSION['iart_config_product']['step3'])){
				foreach($_SESSION['iart_config_product']['step3'] as $v){
					foreach($v as $aImage){
						if($aImage['idtemp'] == $nTabContent){
							if(!empty($aImage['image'])){
								$folderPath = _POKA_PLUGIN_PATH_ . 'images/builder-product/' . get_current_user_id() . '/' . $aImage['image'];
								@unlink($folderPath);
							}
							break;
						}
					}
				}
			}
		}
	}
	
	echo json_encode(
		array(
			'data' => $fileName,
		)
	);