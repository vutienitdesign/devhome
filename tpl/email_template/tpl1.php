<?php
	require_once ABSPATH . WPINC . '/pluggable.php';
	
	class Poka_SendMail{
		public function __construct(){
			//=======================Using=========================
			//Gui Email
			/*
			$title    = '[abc.com] abc ';
			$email    = 'vutienitdesign@gmail.com;
			
			$vContent['title']        = $title;
			$vContent['content']      = array(
				'Xin chào abc',
				'Chúc mừng bạn! Tài khoản của bạn đã được xác thực!',
			);
			
			require_once _POKA_PLUGIN_TEMPLATE_PATH_ . 'email_template/tpl1.php';
			$sendEmail = new Poka_SendMail();
			$sendEmail->send_mail($email, $title, $vContent, '', true);
			*/
		}
		
		public function send_mail($to, $subject, $content, $emailSend = "", $emailCC = false){
			/*$to      = 'vutienitdesign@gmail.com';*/
			
			$stringData = ' vào ' . date('d/m/Y', (time() + _POKA_TIME_));
			$title      = $content['title'] . $stringData;
			$subject    = $subject . $stringData;
			$body       = $this->email_template($title, $content['content']);
			
			$blogName   = get_option('blogname');
			
			//Email gui di
			if(empty($emailSend)){
				$emailSend = get_option('admin_email');
			}
			
			$headers[] = 'Content-Type: text/html; charset=UTF-8';
			$headers[] = 'From:'.$blogName.' <'.$emailSend.'>';
			
			if($emailCC == true){
				$arrEmailCC = explode(',', get_option("_poka_email_cc"));
				if(!empty($arrEmailCC)){
					foreach($arrEmailCC as $key => $value){
						$headers[] = 'Cc: '.trim($value);
					}
				}
			}
			
			@wp_mail($to, $subject, $body, $headers);
		}
		
		public function email_template($title = '', $body = array()){
			$html = '<div style="background: #F1F1F1; border: 1px solid #ddd; font-family: tahoma; font-size: 14px; color: #555;">
					    <div style="background: #00AEEF;font-size: 25px;text-transform: capitalize;font-weight: bold;padding: 10px 0px 10px 0px;text-align: center;">
					        <a href="' . home_url() . '" style="color: #fff; text-decoration:none;">' . get_bloginfo('title') . '</a>
					    </div>
					    <div style="background: #fff;border: 1px solid #eaeaea;padding: 0px 70px 20px 70px;margin: 15px 20px;">
					    	<h4 style="font-size: 20px">' . $title . '</h4>';
			foreach($body as $b){
				$html .= '<p style="margin: 0px 0px 10px 0px; font-family: tahoma; font-size: 14px; color: #555; line-height: 21px;">' . $b . '</p>';
			}
			$html .= '</div>
					    <div style="text-align: center; margin-bottom: 15px;">
					         <strong>Phone: </strong> <a href="tel:19006418">19006418</a> -
					         <strong>Email: </strong> <a href="mailto:khachhangvip.karofi@gmail.com">khachhangvip.karofi@gmail.com</a> -
					         <strong>Website: </strong> <a href="https://karofi.com/khach-hang-vip/" target="_blank">https://karofi.com/khach-hang-vip/</a>
					    </div>
					</div>
					';
			
			return $html;
		}
	}