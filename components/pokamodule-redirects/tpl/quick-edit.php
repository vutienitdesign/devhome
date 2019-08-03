<?php 
	$id          = intval(@$_REQUEST["id"]);
	$request     = trim(@$_REQUEST["request"]);
	$destination = trim(@$_REQUEST["destination"]);

 ?>

<tr id="edit-<?php echo $id ?>">
	<td colspan="5">
		<form id="form-<?php echo $id; ?>" accept-charset="utf-8">
			<label><span style="width: 80px; display: inline-block;">Request:</span> <input type="text" value="<?php echo $request; ?>" size="70"  name="request" /></label>
			<br>
			<span style="width: 85px; display: inline-block;"></span><small>Example: /about.htm</small>
			<br>
			<label><span style="width: 80px; display: inline-block;">Destination:</span> <input type="text" value="<?php echo $destination; ?>" size="70" name="destination" />
			<br>
			<span style="width: 85px; display: inline-block;"></span><small>Example: https://domain.com/about/</small>
			<input type="hidden" name="id" value="<?php echo $id; ?>">
		</form>

		<div class="submit inline-edit-save"></label>
			<span style="width: 80px; display: inline-block;"></span>
			<button type="button" class="button cancel" data-id="<?php echo $id; ?>">Hủy</button>
			<button type="button" class="button button-primary save" data-id="<?php echo $id; ?>">Cập nhật</button>
		</div>
	</td>
</tr>