<?php
	new POKA_Tables_Comment();
	class POKA_Tables_Comment {
		public function __construct() {
			add_action('load-edit-comments.php', array($this, 'add_custom_fields_to_edit_comment_screen'));
			add_action( 'manage_comments_custom_column', array($this, 'custom_title_column'), 10, 2 );
		}
	
		public function add_custom_fields_to_edit_comment_screen() {
			$screen = get_current_screen();
			add_filter("manage_{$screen->id}_columns", array($this, 'add_custom_comment_columns'));
		}
	
		public function add_custom_comment_columns($columns) {
			$new = array();
			foreach($columns as $key => $title) {
				if($key == "comment"){
					$new['phone'] = 'Phone';
					$new['title_rating'] = 'Title rating';
				}
				$new[$key] = $title;
			}
			return $new;
		}
	
		public function custom_title_column($col, $comment_id) {
			switch($col) {
				case 'phone':
					echo get_comment_meta($comment_id, 'phone', true);
					break;
				case 'title_rating':
					echo get_comment_meta($comment_id, 'rating_title', true);
					break;
			}
		}
	}