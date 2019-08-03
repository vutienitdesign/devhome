<?php
	$phone = get_comment_meta( $comment->comment_ID, 'phone', true );
	$retingPhone = get_comment_meta( $comment->comment_ID, 'rating_title', true );
	wp_nonce_field( 'poka_comment_update', 'poka_comment_update', false );
?>

<table class="form-table editcomment">
	<tbody>
	<tr>
		<td class="phone"><label for="phone">Phone:</label></td>
		<td><input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" style="width: 100%" /></td>
	</tr>
	<tr>
		<td class="rating_title"><label for="rating_title">Rating Tile:</label></td>
		<td><input type="text" id="rating_title" name="rating_title" value="<?php echo $retingPhone; ?>" style="width: 100%" /></td>
	</tr>
	</tbody>
</table>

