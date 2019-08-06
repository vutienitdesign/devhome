<?php
	global $pokaHelper;
	$tabCurrent = $_REQUEST['tab_current'];
	
	if(isset($_POST['option']) && !empty($_POST['option'])){
		//Add data vào custom-info
		$option = array(
			'status' => 'custom-info',
			'data' => $_POST['option']
		);
		$pokaHelper->saveProductSession($tabCurrent, '', '', $option);
	}else{
		$refresh  = $_REQUEST['refresh'];
		if(empty($refresh)){
			//Update Cart
			$productID  = $_REQUEST['product_id'];
			$quantity   = $_REQUEST['quantity'];
			
			$cart = WC()->instance()->cart;
			$cart_id = $cart->generate_cart_id($productID);
			$cart_item_id = $cart->find_product_in_cart($cart_id);
			
			if($cart_item_id){
				$cart->set_quantity($cart_item_id, $quantity);
			}else{
				global $woocommerce;
				$woocommerce->cart->add_to_cart($productID);
			}
			
			$dataID     = $_REQUEST['data_id'];
			
			$option = array(
				'status' => 'update',
				'quantity' => $quantity
			);
			$pokaHelper->saveProductSession($tabCurrent, $dataID, $productID, $option);
		}else{
			//Refresh Session Cart
			if($_POST['refresh'] == 'refresh'){
				if(!session_id()){
					session_start();
				}
				$arrData = $_SESSION['iart_config_product']['step3'][$tabCurrent];
				
				foreach($arrData as $v){
					if(!empty($v)){
						foreach($v as $aProduct){
							$productID = $aProduct['id'];
							
							$cart = WC()->instance()->cart;
							$cart_id = $cart->generate_cart_id($productID);
							$cart_item_id = $cart->find_product_in_cart($cart_id);
							
							if($cart_item_id){
								$cart->set_quantity($cart_item_id, 0);
							}
						}
					}
				}
			}
			
			//Refresh Session Config Product (Cấu hình sản phẩm)
			if($_POST['refresh'] == 'refresh-all'){
				global $woocommerce;
				$woocommerce->cart->empty_cart();
			}
			
			$option = array(
				'status' => $_POST['refresh']
			);
			$pokaHelper->saveProductSession($tabCurrent, '', '', $option);
		}
	}