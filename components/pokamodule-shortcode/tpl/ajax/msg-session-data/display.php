<?php
	if($_POST['data-type'] == 'recovery'){
		$sHtml = '<div class="modal-content box-exits-data">
            <div class="modal-header">
                <span class="close">×</span>
                <h3 class="title">Dữ liệu cũ tồn tại</h3>
            </div>
            <div class="modal-body">
                <p class="msg">Dữ liệu cấu hình sản phẩm trước bạn đã cấu hình trước đó đã tồn tại. Bạn có muốn khôi phục lại hay muốn cấu hình sản phẩm mới?</p>
                <div class="action">
                    <button type="button" class="bottom btn-yes">Có! Tôi muốn khôi phục</button>
                    <button type="button" class="bottom btn-no">Không! Tôi muốn làm mới</button>
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
		
		if(!session_id()){
			session_start();
		}
		unset($_SESSION['iart_config_product']);
		
		//Xoa bo image
	}
	