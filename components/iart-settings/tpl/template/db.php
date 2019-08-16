<?php
	class POKA_Pokamodule_Settings{
		public function saveData($data){
			$dataPoka = $data['poka'];
			
			/*//Dien Thoai
			if(!empty($dataPoka['phone'])){
				update_option('_poka_number_call', sanitize_text_field($dataPoka['phone']));
			}else{
				delete_option('_poka_number_call');
			}
			
			//Email CC
			if(!empty($dataPoka['emailcc'])){
				update_option('_poka_email_cc', sanitize_text_field($dataPoka['emailcc']));
			}else{
				delete_option('_poka_email_cc');
			}*/
			
			//Code Header
			if(!empty($dataPoka['code-header'])){
				update_option('_poka_code_header', $dataPoka['code-header']);
			}else{
				delete_option('_poka_code_header');
			}
			
			//Code Footer
			if(!empty($dataPoka['code-footer'])){
				update_option('_poka_code_footer', $dataPoka['code-footer']);
			}else{
				delete_option('_poka_code_footer');
			}
			
			//Formcart
			if(!empty($dataPoka['formcart'])){
				update_option('_iart_formcart_tuvan', $dataPoka['formcart']);
			}else{
				delete_option('_iart_formcart_tuvan');
			}
		}
		
		public function getData(){
			return array(
				'number_call' => get_option("_poka_number_call"),
				'email_cc'    => get_option("_poka_email_cc"),
				'code_header' => stripslashes(get_option("_poka_code_header")),
				'code_footer' => stripslashes(get_option("_poka_code_footer")),
				'formcart' => get_option("_iart_formcart_tuvan"),
			);
		}
	}