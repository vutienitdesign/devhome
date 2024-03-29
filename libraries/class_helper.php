<?php
	global $pokaHelper;
	$pokaHelper = new POKA_Helper();
	class POKA_Helper{
		public function saveImage($base64){
			if(!empty($base64)){
				$image_parts = explode(";base64,", $base64);
				$image_type_aux = explode("image/", $image_parts[0]);
				$image_type = $image_type_aux[1];
				$image_base64 = base64_decode($image_parts[1]);
				
				$folderPath = _POKA_PLUGIN_PATH_ . 'images/builder-product/' . get_current_user_id() . '/';
				
				if(!is_dir($folderPath)){
					mkdir($folderPath);
				}
				$fileName = uniqid() . '.png';
				file_put_contents($folderPath . $fileName, $image_base64);
				
				return $fileName;
			}else{
				return '';
			}
		}
		
		//Lay thong tin san pham tai session
		public function getProductSession($idProduct = '', $dataID = '', $dataTerm = '', $quantity, $option = array()){
			$sHtml = '';
			
			$product = wc_get_product($idProduct);
			
			if(!empty($product)){
				$sTitle   = $product->get_title();
				$imageUrl = wp_get_attachment_image_src(get_post_thumbnail_id($idProduct));
				$imageUrl = $imageUrl[0];
				$idRandom = PMCommon::generateRandomString('5');
				
				if($option['type_product'] == 'set'){
					$sHtml = '<tr data-id="'.$dataID.'" data-product="'.$idProduct.'" class="data-product product-id-'.$idProduct.' product-temp-'.$idRandom.'" data-type="set">
							    <td class="image"><img class="img-product" src="'.$imageUrl.'" alt="'.$sTitle.'"></td>
							    <td>
							        <a href="'.$product->get_permalink().'" target="_blank">'.$sTitle.'</a>
							        <div class="info">
							            <ul>
	                                        <li><span>Mã sản phẩm:</span> '.$product->get_sku().'</li>
	                                    </ul>
							        </div>
							    </td>
							    <td class="unit-price">'.wc_price($product->get_price()).'</td>
							    <td class="amount"><input type="number" class="input-text soluong custom-data-id" value="'.$quantity.'" min="1" data-id="'.$dataID.'" data-product="'.$idProduct.'" data-price="'.$product->get_price().'" data-type="set" data-tag="'.$option['term'].'|'.$option['all_term'].'"></td>
							    <td class="html-price">'.wc_price($product->get_price() * $quantity).'</td>
							    <td class="action">
							        <div class="iart-tooltip">
							            <i class="fa fa-edit edit-product-set custom-data-id" data-id="'.$dataID.'" data-temp="'.$idRandom.'" data-product="'.$idProduct.'" data-small="'.$option['small'].'" data-all="'.$option['all_term'].'" data-term="'.$option['term'].'"></i>
							            <span class="tooltiptext">Sửa</span>
							        </div>
							        <div class="iart-tooltip">
							            <i class="fa fa-trash remove-product custom-data-id" data-id="'.$dataID.'" data-product="'.$idProduct.'"></i>
							            <span class="tooltiptext">Xóa</span>
							        </div>
							    </td>
							</tr>';
				}else{
					$sHtml = '<tr data-id="'.$dataID.'" data-product="'.$idProduct.'" class="data-product product-id-'.$idProduct.' product-temp-'.$idRandom.'">
                            <td class="image"><img class="img-product" src="'.$imageUrl.'" alt="'.$sTitle.'"></td>
                            <td>
                                <a href="'.$product->get_permalink().'" target="_blank">'.$sTitle.'</a>
                                <div class="info">
                                    <ul>
                                        <li><span>Mã sản phẩm:</span> '.$product->get_sku().'</li>
                                    </ul>
                                </div>
                            </td>
                            <td class="unit-price">'.wc_price($product->get_price()).'</td>
                            <td class="amount"><input type="number" class="input-text soluong" value="'.$quantity.'" min="1" data-id="'.$dataID.'" data-product="'.$idProduct.'" data-price="'.$product->get_price().'"></td>
                            <td class="html-price">'.wc_price($product->get_price() * $quantity).'</td>
                            <td class="action">
                               <div class="iart-tooltip">
                                    <i class="fa fa-edit edit-product" data-id="'.$dataID.'" data-term="'.$dataTerm.'" data-temp="'.$idRandom.'" data-product="'.$idProduct.'"></i>
                                   <span class="tooltiptext">Sửa</span>
                               </div>
                               <div class="iart-tooltip">
                                    <i class="fa fa-trash remove-product" data-id="'.$dataID.'" data-product="'.$idProduct.'"></i>
                                   <span class="tooltiptext">Xóa</span>
                               </div>
                            </td>
                        </tr>';
				}
			}
			
			return $sHtml;
		}
		
		//Get html set do
		public function getDataSet3SetDo($idMedium, $termPriority = ''){
			global $wpdb;
			
			$sHtml = '';
			
			$sql    = "SELECT `tag` FROM `{$wpdb->prefix}decorate_medium` WHERE `id` = {$idMedium}";
			$result = $wpdb->get_row($sql, ARRAY_A);
			
			$postChecked = '';
			if(!empty($termPriority)){
				$postChecked = get_option("iart_priority_set_tag_" . $termPriority);
			}
			
			$sAllData   = '';
			$sHtmlFirst = ''; //Uu tien chon SET
			$nTotal = 0;
			if(!empty($result)){
				$aTag = explode(',', $result['tag']);
				if(!empty($aTag)){
					foreach($aTag as $v){
						$sAllData .= ',' . $v;
					}
					$sAllData = ltrim($sAllData, ',');
					
					$nTotal = count($aTag);
					
					$i = 1;
					$flag = false;
					foreach($aTag as $v){
						$postSet = get_option("iart_priority_set_tag_" . $v);
						if(!empty($postSet) && $postChecked == $postSet){
							$flag = true;
						}
						
						$aData = get_option("poka_product_tag_" . $v);
						
						if($i > 3){
							break;
						}
						
						if(!empty($aData['image']['url'])){
							$sImage = $aData['image']['url'];
						}else{
							$sImage = _POKA_PLUGIN_URL_ . 'images/no-thumbnail.jpg';
						}
						
						$terms = get_term_by('id', $v, 'product_tag' );
						$sTitleTag = '';
						if(!empty($terms)){
							$sTitleTag = '<h3 class="title-tag">' . $terms->name . '</h3>';
						}
						
						if($flag == true){
							$flag = false;
							
							$sHtmlFirst = '<div class="item">
										'.$sTitleTag.'
			                            <img src="'.$sImage.'">
			                            <div class="action-set">
			                                <button class="btn view-now" data-all="'.$sAllData.'" value="'.$v.'">Xem Chi Tiết</button>
			                                <button class="btn add-now" data-all="'.$sAllData.'" value="'.$v.'">Chọn Bộ Này</button>
			                            </div>
			                        </div>';
						}else{
							$sHtml .= '<div class="item">
										'.$sTitleTag.'
			                            <img src="'.$sImage.'">
			                            <div class="action-set">
			                                <button class="btn view-now" data-all="'.$sAllData.'" value="'.$v.'">Xem Chi Tiết</button>
			                                <button class="btn add-now" data-all="'.$sAllData.'" value="'.$v.'">Chọn Bộ Này</button>
			                            </div>
			                        </div>';
						}
						
						$i++;
					}
				}
			}
			
			if(empty($sHtml) && empty($sHtmlFirst)){
				$sHtml = '<p>Hiện tại chưa có SET Đồ</p>';
				$status = 'no';
			}else{
				$sHtml = $sHtmlFirst . $sHtml;
				$sHtml .= '<div class="action">
                                    <button class="btn view-all-set" data-all="'.$sAllData.'">Xem tất cả</button>
                                </div>';
				$status = 'yes';
			}
			
			return array(
				'data' => $sHtml,
				'total' => $nTotal,
				'status' => $status
			);
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
	}