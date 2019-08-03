<?php
//	new POKA_Custom_TinyMCE();
	class POKA_Custom_TinyMCE{
		public function __construct() {
			if(is_file(_POKA_PLUGIN_LIB_PATH_ . "/tinymce/css/tinymce.css")){
				wp_register_style('poka_tinymce_css', _POKA_PLUGIN_LIB_URL_ . "tinymce/css/tinymce.min.css", 'all');
				wp_enqueue_style("poka_tinymce_css");
				
				add_action ('after_wp_tiny_mce', array($this, 'custom_tinymce_extra_vars'));
			}
			
			add_action ('init', array($this, 'buttons'));
		}
		
		function buttons() {
			if('true' == get_user_option('rich_editing')){
				add_filter('mce_external_plugins', array($this, 'custom_tinymce_plugin'));
				add_filter('mce_buttons', array($this, 'register_button'));
			}
		}
		
		public function custom_tinymce_plugin($plugin_array ) {
			$plugin_array['pokatinymce'] = _POKA_PLUGIN_LIB_URL_ . '/tinymce/js/tinymce.js';
			return $plugin_array;
		}
		
		public function register_button( $buttons ) {
			array_push( $buttons, 'poka_shortcodes_highlight', 'poka_list_product', 'custom_mce_button1', 'poka_shortcodes_module' );
			return $buttons;
		}
		
		public function custom_tinymce_extra_vars() {
			?>
			<script type="text/javascript">
                var tinyMCE_object = <?php echo json_encode(
						array(
							'image_download' => _POKA_PLUGIN_URL_ . 'libraries/tinymce/images/icon-download.png',
						)
					);
					?>;
			</script>
			<?php
		}
	}