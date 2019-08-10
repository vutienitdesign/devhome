<?php
	if(!session_id()){
		session_start();
	}
	
	global $woocommerce;
	
	$setCart = (isset($_POST['cart']) && $_POST['cart'] == 'cart') ? 'cart' : '';
	//Add to cart
	if($setCart == 'cart'){
		//Update To Cart
		$arrSave = array();
		
		//Set Empty Cart
		$woocommerce->cart->empty_cart();
		
		foreach($_POST['data'] as $k => $v){
			if(isset($v['product'])){
				$aTmp = explode(' -- ', $v['product']);
				if($aTmp[3] > 0){
					//Update Cart
					$productID   = $aTmp[2];
					$quantity    = $aTmp[3];
					
					$woocommerce->cart->add_to_cart($productID, $quantity);
					/*$cart       = WC()->instance()->cart;
					$cartID     = $cart->generate_cart_id($productID);
					$cartItemID = $cart->find_product_in_cart($cartID);
					
					if($cartItemID){
						$cart->set_quantity($cartItemID, $quantity);
					}else{
						$woocommerce->cart->add_to_cart($productID);
					}*/
				}
			}
		}
	}else{
		if($_POST['refresh'] == 'all'){
			if(!empty($_SESSION['iart_config_product']['step3'])){
				foreach($_SESSION['iart_config_product']['step3'] as $v){
					$imageRemove = $v['custom-info']['image'];
					if(!empty($imageRemove)){
						$folderPath = _POKA_PLUGIN_PATH_ . 'images/builder-product/' . get_current_user_id() . '/' . $imageRemove;
						@unlink($folderPath);
					}
				}
			}
			
			//Set Empty Cart
			$woocommerce->cart->empty_cart();
			
			$_SESSION['iart_config_product']['step3'] = array();
		}else{
			$arrSave = array();
			foreach($_POST['data'] as $k => $v){
				if(isset($v['product'])){
					$aTmp = explode(' -- ', $v['product']);
					if($aTmp[3] > 0){
						//Update Cart
						$productID   = $aTmp[2];
						$quantity    = $aTmp[3];
						$typeProduct = $aTmp[4];
						$tags = ($aTmp[5] != 'no') ? $aTmp[5] : '';
						
						$arrSave[$aTmp[0]][$aTmp[1]][] = array(
							'id'        => $productID,
							'quantity'  => $quantity,
							'type'      => $typeProduct,
							'data_tag'      => $tags,
						);
					}
				}
				
				if(isset($v['custom-info'])){
					$aTmp  = explode(' ::: ', $v['custom-info']);
					$aTmp1 = explode(' -- ', $aTmp[1]);
					
					if(!empty($aTmp1)){
						$arrCustom = array(
							'info'   => explode(' :: ', $aTmp1[0])[1],
							'url'    => explode(' :: ', $aTmp1[1])[1],
							'image'  => explode(' :: ', $aTmp1[2])[1],
							'idtemp' => explode(' :: ', $aTmp1[3])[1],
						);
						$arrSave[$aTmp[0]][] = $arrCustom;
						
						echo "<pre style='color: red;font-size: 14px'>";
							print_r($aTmp1);
						echo "</pre>";
					}
				}
			}
			
			//Neu refresh One
			if($_POST['refresh'] == 'one'){
				//Xóa bỏ hình ảnh
				$tabcrrent = $_POST['tabcrrent'];
				
				$imageRemove = $_SESSION['iart_config_product']['step3'][$tabcrrent]['custom-info']['image'];
				if(!empty($imageRemove)){
					$folderPath = _POKA_PLUGIN_PATH_ . 'images/builder-product/' . get_current_user_id() . '/' . $imageRemove;
					@unlink($folderPath);
				}
				
				foreach($arrSave as $k => $v){
					if($k == $tabcrrent){
						$arrSave[$k]['custom-info']['image'] = '';
					}else{
						$sImage = $_SESSION['iart_config_product']['step3'][$k]['custom-info']['image'];
						if(!empty($sImage)){
							$arrSave[$k]['custom-info']['image'] = $sImage;
						}
					}
				}
				
				foreach($_SESSION['iart_config_product']['step3'][$tabcrrent] as $k => $v){
					if($k == 'custom-info'){
						continue;
					}
					
					$_SESSION['iart_config_product']['step3'][$tabcrrent][$k] = array();
				}
			}else{
				if(!empty($arrSave)){
					foreach($arrSave as $k => $v){
						$sImage = $_SESSION['iart_config_product']['step3'][$k]['custom-info']['image'];
						if(!empty($sImage)){
							$arrSave[$k]['custom-info']['image'] = $sImage;
						}
					}
				}
				
				$_SESSION['iart_config_product']['step3'] = $arrSave;
			}
		}
		
		//Update Database
		global $wpdb;
		$tbl = $wpdb->prefix . 'manager_builder_product';
		
		$arrSave = $_SESSION['iart_config_product'];
		$idTemp  = $arrSave['id_temp'];
		unset($arrSave['id_temp']);
		
		$data = array(
			'data'          => serialize($arrSave),
			'date_update'   => _REAL_TIME_,
		);
		$format =  array('%s','%d');
		
		$where = array('id_temp'=> $idTemp);
		$where_format = array('%s');
		$wpdb->update($tbl, $data, $where,$format,$where_format);
	}