<?php
	global $wpdb;
	
	$sql = "SELECT * FROM `{$wpdb->prefix}decorate_large`";
	$result = $wpdb->get_results($sql);
	
	$sHtml = '';
	if(!empty($result)){
		$sHtml = '<div class="modal-content box-config-product-step1">
					<div class="modal-header">
						<span class="close">&times;</span>
						<h3 class="title">Chọn lựa không gian</h3>
					</div>
					<div class="modal-body">
						<div class="loader"></div>
						
						<h4 class="title">Vui lòng lựa chọn không gian</h4>
						<div class="choose">';
				
		$sHtmlChoose = '';
		foreach($result as $k => $v){
			$sHtmlChoose .= '<div class="item"><input type="radio" class="step1" name="step1" value="'.$v->id.'" id="step1-'.$v->id.'"> <label for="step1-'.$v->id.'">'.$v->name.'</label></div>';
		}
		
		$sHtml .= $sHtmlChoose;
		$sHtml .= '</div>
							<div class="action">
								<button type="button" class="bottom btn-step1">Next <i class="fa fa-angle-right"></i></button>
							</div>
							
							<div class="clear"></div>
						</div>
					</div>';
	}
	
	echo json_encode(
		array(
			'data' => $sHtml
		)
	);
	
	