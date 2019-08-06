<?php
	global $wpdb;
	$sType = $_POST['send-type'];
	if($sType == 'getdata'){
		$id = intval($_POST['id']);
		$sql = "SELECT `id` FROM `{$wpdb->prefix}decorate_large` WHERE `id` = {$id}";
		$result = $wpdb->get_row($sql);
		
		if(!empty($result)){
			if(!session_id()){
				session_start();
			}
			
			$_SESSION['iart_config_product'] = array(
				'step1' => $id
			);
			
			$sql = "SELECT `id`, `name_show`, `max` FROM `{$wpdb->prefix}decorate_medium` WHERE `decorate_large` = {$id}";
			$result = $wpdb->get_results($sql);
			
			if(!empty($result)){
				$sHtml = '<div class="modal-content box-config-product-step2">
						<div class="modal-header">
							<span class="close">&times;</span>
							<h2 class="title">Thông tin cấu hình</h2>
						</div>
						<div class="modal-body">
							<div class="loader"></div>
							<h3 class="title">Cấu hình chi tiết</h3>
							<div class="choose">
							';
				
				$arrStep = array();
				$i = 0;
				foreach($result as $v){
					$arrStep[$i] = array(
						'id' => $v->id,
						'value' => 1
					);
					$sHtml .= '<div class="item"><label class="lbl">'.$v->name_show.'</label><input type="number" class="input-text config-amount" value="1" data-id="'.$v->id.'" min="1" max="'.$v->max.'"></div>';
					
					$i++;
				}
				$_SESSION['iart_config_product']['step2'] = $arrStep;
				$_SESSION['iart_config_product']['step3'] = array();
				
				$sHtml .= '</div>
							<div class="action">
								<button type="button" class="bottom btn-step2">Next <i class="fa fa-angle-right"></i></button>
							</div>
						</div>
					</div>';
				
				$arrResult = array(
					'data' => $sHtml,
					'log' => 'success'
				);
			}else{
				$arrResult = array(
					'data' => 'Không gian không có sẵn',
					'log' => 'empty'
				);
			}
		}else{
			$arrResult = array(
				'data' => 'Có lỗi xảy ra! Xin vui lòng thử lại sau!',
				'log' => 'error'
			);
		}
	}
	
	if($sType == 'submit'){
		if(!session_id()){
			session_start();
		}
		
		if(!empty($_POST['data']) && is_array($_POST['data'])){
			$arr = $_SESSION['iart_config_product']['step2'];
			$arrNew = $_SESSION['iart_config_product']['step2'];
			
			foreach($arr as $k => $v){
				if(array_key_exists($v['id'], $_POST['data'])){
					$arrNew[$k]['value'] = ($_POST['data'][$v['id']] > 0) ? $_POST['data'][$v['id']] : 1;
				}
			}
			
			$_SESSION['iart_config_product']['step2'] = $arrNew;
		}
		
		$arrResult = array(
			'data' => '',
			'log' => 'success'
		);
	}
	
	echo json_encode($arrResult);