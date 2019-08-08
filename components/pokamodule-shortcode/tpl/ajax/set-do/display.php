<?php
	$sHtml = '';
	if($_POST['term_id'] > 0){
		global $pokaHelper;
		$sHtml = $pokaHelper->getDataSet3SetDo($_POST['term_id']);
	}
	
	echo json_encode(
		array(
			'data' => $sHtml,
			'msg' => ''
		)
	);
 