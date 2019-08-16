<?php
	$msg = '';
	
	require_once "db.php";
	$vDB = new POKA_Pokamodule_Settings();
	
	if(isset($_POST['action']) &&  $_POST['action'] == 'save_change'){
		if(!isset($_POST['name_of_nonce_field']) || !wp_verify_nonce($_POST['name_of_nonce_field'], 'poka-settings')){
		}else{
			$vDB->saveData($_POST);
			$msg = PMCommon::getMsgBE(array('Cập nhật thành công!'));
		}
	}
	
	$vData = $vDB->getData();
?>

<div class="wrap">
	<?php echo $msg; ?>
    
    <form method="post" action="">
        <h3>Settings</h3>
        <table class="form-table poka-form-table">
            <tbody>
            <!--<tr>
                <th scope="row">
                    <label for="phone">Số Điện Thoại</label>
                </th>
                <td>
                    <input name="poka[phone]" type="number" id="phone" value="<?php /*echo $vData['number_call']; */?>" class="regular-text">
                    <p class="description">Hiện thị số điện thoại ngoài FrontEnd</p>
                </td>
            </tr>-->
            <!--<tr>
                <th scope="row">
                    <label for="emailcc">Email CC</label>
                </th>
                <td>
                    <input name="poka[emailcc]" type="text" id="emailcc" value="<?php /*echo $vData['email_cc']; */?>" class="regular-text">
                    <p class="description">Phân cách Email bằng dấu , Ví dụ: abc@gmail.com, ghi@gmail.com</p>
                </td>
            </tr>-->
            <tr>
                <th scope="row">
                    <label for="code-header">Code Header FrontEnd</label>
                </th>
                <td>
                    <textarea rows="10" cols="70" name="poka[code-header]" id="code-header"><?php echo $vData['code_header']; ?></textarea>
                    <p class="description">Insert code into the <strong>header</strong> position</p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="code-footer">Code Footer FrontEnd</label>
                </th>
                <td>
                    <textarea rows="10" cols="70" name="poka[code-footer]" id="code-footer"><?php echo $vData['code_footer']; ?></textarea>
                    <p class="description">Insert code into the <strong>footer</strong> position</p>
                </td>
            </tr>
            <tr>
                <th>ID FORM Cart Yêu cầu tư vấn</th>
                <td>
                    <input name="poka[formcart]" type="number" value="<?php echo $vData['formcart']; ?>" class="regular-text">
                </td>
            </tr>
        </table>

        <p class="submit">
            <input type="submit" name="submit" class="button button-primary" value="Save Change">
			
			<?php wp_nonce_field('poka-settings', 'name_of_nonce_field'); ?>
            <input type="hidden" name="action" value="save_change">
        </p>
    </form>
</div>