<?php
	if(!session_id()){
		session_start();
	}
	
	$idTemp = $_POST['dataid'];
	foreach($_SESSION['iart_config_product']['step3'] as $k => $v){
		if(!empty($v['custom-info'])){
			foreach($v['custom-info'] as $kCustom => $vCustom){
				if($vCustom['idtemp'] == $idTemp){
					if(!empty($vCustom['image'])){
						$folderPath = _POKA_PLUGIN_PATH_ . 'images/builder-product/' . get_current_user_id() . '/' . $vCustom['image'];
						@unlink($folderPath);
					}
					unset($_SESSION['iart_config_product']['step3'][$k]['custom-info'][$kCustom]);
				}
			}
		}
	}