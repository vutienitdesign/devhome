<?php
	global $pokaHelper;
	$pokaHelper = new POKA_Helper();
	class POKA_Helper{
		//Lay thong tin san pham tai session
		public function getProductSession($idProduct = '', $dataID = '', $dataTerm = '', $quantity){
			$sHtml = '';
			
			$product = wc_get_product($idProduct);
			
			$sTitle   = $product->get_title();
			$imageUrl = wp_get_attachment_image_src(get_post_thumbnail_id($idProduct))[0];
			$idRandom = PMCommon::generateRandomString('5');
			
			if(!empty($product)){
				$stock = $product->get_stock_status();
				if($stock == 'instock'){
					$stock = 'Còn hàng';
				}else{
					$stock = 'Hết hàng';
				}
				
				$sHtml = '<tr data-id="'.$dataID.'" data-product="'.$idProduct.'" class="data-product product-id-'.$idProduct.' product-temp-'.$idRandom.'">
                            <td><img class="img-product" src="'.$imageUrl.'" alt="'.$sTitle.'"></td>
                            <td>
                                <a href="'.$product->get_permalink().'" target="_blank">'.$sTitle.'</a>
                                <div class="info">
                                    <ul>
                                        <li><span>Mã sản phẩm:</span> '.$product->get_sku().'</li>
                                        <li><span>Kho hàng:</span> '.$stock.'</li>
                                    </ul>
                                </div>
                            </td>
                            <td>'.wc_price($product->get_price()).'</td>
                            <td><input type="number" class="input-text soluong" value="'.$quantity.'" min="1" data-id="'.$dataID.'" data-product="'.$idProduct.'" data-price="'.$product->get_price().'"></td>
                            <td class="html-price">'.wc_price($product->get_price() * $quantity).'</td>
                            <td class="action">
                               <div class="iart-tooltip">
                                    <i class="fa fa-edit edit-product" data-id="'.$dataID.'" data-term="'.$dataTerm.'" data-temp="'.$idRandom.'"></i>
                                   <span class="tooltiptext">Sửa</span>
                               </div>
                               <div class="iart-tooltip">
                                    <i class="fa fa-trash remove-product" data-id="'.$dataID.'" data-product="'.$idProduct.'"></i>
                                   <span class="tooltiptext">Xóa</span>
                               </div>
                            </td>
                        </tr>';
				
			}
			
			return $sHtml;
		}
		
		//Get data Set 3 cau hinh san pham
		public function getDataSet3ConfigProduct(){
			if(!session_id()){
				session_start();
			}
			
			global $wpdb;
			$sPrex = $wpdb->prefix;
			
			$arrData = $_SESSION['iart_config_product'];
			
			$nIDStep1 = $arrData['step1'];
			
			$sql    = "SELECT `name` FROM `{$sPrex}decorate_large` WHERE `id` = {$nIDStep1}";
			$sNameBuild = $wpdb->get_row($sql)->name;
			
			$sWhere = '';
			foreach($arrData['step2'] as $v){
				$sWhere .= ',' . $v['id'];
			}
			$sWhere = ltrim($sWhere, ',');
			
			$sql    = "SELECT `id`, `name`, `max` FROM `{$sPrex}decorate_medium` WHERE `decorate_large` IN({$sWhere})";
			$result = $wpdb->get_results($sql, ARRAY_A);
			
			$aResult = array();
			if(!empty($result)){
				$i = 0;
				foreach($result as $v){
					$aResult[$i] = $v;
					$aResult[$i]['total'] = '';
					
					$nMax = ($v['max'] > 0) ? $v['max'] : 2;
					
					foreach($arrData['step2'] as $vStep2){
						if($vStep2['id'] == $v['id']){
							if($vStep2['value'] > $nMax){
								$aResult[$i]['total'] = $nMax;
							}else{
								$aResult[$i]['total'] = $vStep2['value'];
							}
							break;
						}
					}
					$i++;
				}
			}
			
			$aData = array();
			$i = 0;
			foreach($aResult as $v){
				if($v['total'] > 1){
					for($iTmp = 1; $iTmp <= $v['total']; $iTmp++){
						$vTmp = array(
							'id' => $v['id'],
							'name' => $v['name'] . ' ' . $iTmp,
						);
						$aData[$i] = $vTmp;
						$i++;
					}
				}else{
					unset($v['total']);
					$aData[$i] = $v;
				}
				$i++;
			}
			
			return $aData;
		}
		
		//Luu cac san pham vao Session
		public function saveProductSession($tabCurrent = '', $dataID = '', $idProduct, $option = array()){
			if(!session_id()){
				session_start();
			}
			
			$quantity = '';
			$sStatus = false;
			if(!empty($option)){
				if($option['status'] == 'update'){
					$quantity = $option['quantity'];
					$sStatus = true;
				}
				
				if($option['status'] == 'refresh'){
					unset($_SESSION['iart_config_product']['step3'][$tabCurrent]);
					return false;
				}
				
				if($option['status'] == 'refresh-all'){
					$_SESSION['iart_config_product']['step3'] = array();
					return false;
				}
				
				if($option['status'] == 'custom-info'){
					$_SESSION['iart_config_product']['step3'][$tabCurrent]['custom-info'][$option['data']['type']] = $option['data']['value'];
					return false;
				}
			}
			
			$dataID = 'data-id-' . $dataID;
			if(isset($_SESSION['iart_config_product']['step3'][$tabCurrent][$dataID]) && !empty($_SESSION['iart_config_product']['step3'][$tabCurrent][$dataID])){
				$flag = false;
				foreach($_SESSION['iart_config_product']['step3'][$tabCurrent][$dataID] as $k => $v){
					if($v['id'] == $idProduct){
						$flag = true;
						
						if($sStatus == true){
							$v['quantity'] = $quantity;
						}else{
							$v['quantity'] += 1;
						}
						
						$_SESSION['iart_config_product']['step3'][$tabCurrent][$dataID][$k] = $v;
						break;
					}
				}
				
				if($flag == false){
					$arrSave = array(
						'id' => $idProduct,
						'quantity' => ($sStatus == true) ? $quantity : 1
					);
					
					$_SESSION['iart_config_product']['step3'][$tabCurrent][$dataID][] = $arrSave;
				}
			}else{
				$arrSave = array(
					'id' => $idProduct,
					'quantity' => ($sStatus == true) ? $quantity : 1
				);
				$_SESSION['iart_config_product']['step3'][$tabCurrent][$dataID][] = $arrSave;
			}
		}
		
		public function xuLyCategory($arrCat = array()){
			$result = array();
			
			//Them category
			$catName = $arrCat['category'];
			if(!empty($catName)){
				$oTerm = term_exists($catName, 'product_cat', 0);
				if(!$oTerm){
					$oTerm = wp_insert_term($catName, 'product_cat', array('parent' => 0));
				}
				$result[] = $catName;
				
				
				//Them category child
				if(!empty($arrCat['subcategory'])){
					foreach($arrCat['subcategory'] as $v){
						$catNameChild = trim($v);
						
						$oTermChild = term_exists($catNameChild, 'product_cat', array('parent' => $oTerm['term_id']));
						if(!$oTermChild){
							$oTermChild = wp_insert_term($catNameChild, 'product_cat', array('parent' => $oTerm['term_id']));
						}
						
						$result[] = $catNameChild;
					}
				}
			}
			
			return $result;
		}
		
		public function xuLyImageGoogleCound($url, $userID){
			$aImage = explode('\\\\192.168.9.3\Live Order Data\\', $url);
			$aImage = explode('\\', $aImage[1]);
			$sUrl = '';
			foreach($aImage as $v){
				$sUrl .= '/' . $v;
			}
			
			$fileFullName = basename($sUrl);
			$aFileName    = explode('.', $fileFullName);
			$fileName     = $aFileName[0];
			$fileNameExt  = $aFileName[1];
			
			$arrExt = array(
				'jpg' => 'jpeg',
				'png' => 'png',
				'gif' => 'gif',
			);
			
			$miniType = 'image/' . (!empty($arrExt[$fileNameExt]) ? $arrExt[$fileNameExt] : 'png');
			$arr = array(
				'post_title'     => $fileName,
				'post_author'    => $userID,
				'post_status'    => 'inherit',
				'comment_status' => 'open',
				'ping_status'    => 'closed',
				'post_type'      => 'attachment',
				'post_mime_type' => $miniType,
			);
			$post_id = wp_insert_post($arr);
			if($post_id){
				$aArr = array(
					'provider' => 'gcp',
					'region'   => 'asia-east2',
					'bucket'   => 'mrus',
					'key'      => $sUrl
				);
				update_post_meta( $post_id, 'amazonS3_info', $aArr);
				
				$aArr = array(
					'width'  => 482,
					'height' => 303,
					'file'   => $sUrl,
					'sizes' => array(
						'thumbnail' => array(
							'file'      => $fileFullName,
							'width'     => '150',
							'height'    => '150',
							'mime-type' => $miniType,
						),
						'medium' => array(
							'file'      => $fileFullName,
							'width'     => '300',
							'height'    => '189',
							'mime-type' => $miniType,
						),
					),
					'image_meta' => array(
						'aperture'          => 0,
						'credit'            => '',
						'camera'            => '',
						'caption'           => '',
						'created_timestamp' => 0,
						'copyright'         => '',
						'focal_length'      => 0,
						'iso'               => 0,
						'shutter_speed'     => 0,
						'title'             => '',
						'orientation'       => 0,
						'keywords'          => array()
					)
				);
				update_post_meta( $post_id, '_wp_attachment_metadata', $aArr);
				update_post_meta( $post_id, '_wp_attached_file', $sUrl);
			}
			
			return $post_id;
		}
		
		public function xuLyImages($obj){
			$aImg = array();
			if(!empty($obj)){
				$sImage = ltrim($obj, '[ {');
				$sImage = rtrim($sImage, '} ]');
				$aImage = explode('}, {', $sImage);
				
				if(!empty($aImage)){
					foreach($aImage as $kImg => $vImg){
						$vImg = '{' . $vImg . '}';
						$aImg[$kImg] = json_decode($vImg);
					}
				}
			}
			return $aImg;
		}
	}