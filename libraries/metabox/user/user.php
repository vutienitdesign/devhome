<?php
new POKA_MetaBox_User();
class POKA_MetaBox_User {
	public function __construct() {
		add_action('user_new_form', array($this, 'add'));
		add_action('show_user_profile', array($this, 'add'));
		add_action('edit_user_profile', array($this, 'add'));
		
		add_action( 'personal_options_update',  array($this, 'save') );
		add_action( 'edit_user_profile_update', array($this, 'save') );
		add_action( 'user_register',            array($this, 'save') );
	}
	
	public function add($profileuser){
		wp_enqueue_style('poka_metabox_user');
//		wp_enqueue_script('poka_metabox_user');
		
		$phone      = get_user_meta($profileuser->ID, '_user_phone', true);
		$address    = get_user_meta($profileuser->ID, '_user_address', true);
		?>
		<h2>Vui lòng thông tin dưới đây để Admin thuận tiện liên hệ</h2>
		<table class="form-table" style="border: 1px solid red;">
			<tbody>
			<tr>
				<th><label>Số điện thoại</label></th>
				<td><input type="text" name="user_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text"></td>
			</tr>
			<tr>
				<th><label>Địa chỉ liên hệ</label></th>
				<td><input type="text" name="user_address" value="<?php echo esc_attr($address); ?>" class="regular-text"></td>
			</tr>
			</tbody>
		</table>
		<?php
	}
	
	public function save($user_id){
	    if(isset($_POST['user_phone'])){
	       update_user_meta($user_id, '_user_phone', sanitize_text_field($_POST['user_phone']));
        }
		if(isset($_POST['user_address'])){
			update_user_meta($user_id, '_user_address', sanitize_text_field($_POST['user_address']));
		}
	}
}