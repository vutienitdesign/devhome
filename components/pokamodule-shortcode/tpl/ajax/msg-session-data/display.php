<?php
	if(!session_id()){
		session_start();
	}
	
	if($_POST['data-type'] == 'recovery'){
		$nameProject = $_SESSION['iart_config_product']['name_product'];
		$sHtml = '<div class="modal-content box-exits-data">
            <div class="modal-header">
                <span class="close">×</span>
                <h3 class="title">Thông Báo</h3>
            </div>
            <div class="modal-body">
                <p class="msg">Bạn có muốn tiếp tục thiết kế và lựa chọn nội thất cho "'.$nameProject.'" không?</p>
                <div class="action">
                    <button type="button" class="bottom btn-yes">Có, Tôi muốn khôi phục.</button>
                    <button type="button" class="bottom btn-no">Không, Tôi muốn làm mới</button>
                </div>
                <div class="clear"></div>
            </div>
        </div>';
		
		echo json_encode(
			array(
				'data' => $sHtml,
				'msg' => ''
			)
		);
	}else{
		global $woocommerce;
		
		//Set Empty Cart
		$woocommerce->cart->empty_cart();
		
		//Xoa bo image
		if(!empty($_SESSION['iart_config_product']['step3'])){
			$userID = get_current_user_id();
			foreach($_SESSION['iart_config_product']['step3'] as $v){
				foreach($v['custom-info'] as $vInfo){
					if(!empty($vInfo['image'])){
						$folderPath = _POKA_PLUGIN_PATH_ . 'images/builder-product/' . $userID . '/' . $vInfo['image'];
						@unlink($folderPath);
					}
				}
			}
		}
		
		unset($_SESSION['iart_config_product']);
	}
	