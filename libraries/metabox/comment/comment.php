<?php
	new POKA_MetaBox_Comment();
	class POKA_MetaBox_Comment {
		public function __construct() {
			add_action( 'add_meta_boxes_comment', array($this, 'display'));
			add_action( 'edit_comment', array($this, 'save'));
		}
	
		public function display() {
			add_meta_box( 'title', 'Poka Info', array($this, 'content'), 'comment', 'normal', 'high' );
		}
	
		public function content ($comment){
			require_once "tpl/edit.php";
		}
		
		public function save ($comment_id){
			if(!isset($_POST['poka_comment_update']) || !wp_verify_nonce($_POST['poka_comment_update'], 'poka_comment_update')){
				return;
			}
			
			if((isset($_POST['phone'])) && ($_POST['phone'] != '')){
				$phone = wp_filter_nohtml_kses($_POST['phone']);
				update_comment_meta($comment_id, 'phone', $phone);
			}else{
				delete_comment_meta($comment_id, 'phone');
			}
			
			if((isset($_POST['rating_title'])) && ($_POST['rating_title'] != '')){
				$phone = wp_filter_nohtml_kses($_POST['rating_title']);
				update_comment_meta($comment_id, 'rating_title', $phone);
			}else{
				delete_comment_meta($comment_id, 'rating_title');
			}
		}
	}