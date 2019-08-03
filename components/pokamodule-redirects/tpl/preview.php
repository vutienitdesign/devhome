<?php 
	global $wpdb;
	$post_id = intval(@$_REQUEST["preview_id"]);

 ?>
<div class="main-content">
<div class="header">
	<h2 style="display: inline-block; margin: 0; ">Bài viết liên quan</h2>
</div>
<div id="wrapper" class="posts-related">

	<table style="width: 100%" class="wp-list-table widefat fixed striped article">
		<tr>
			<th width="65%">Cập nhật Tags</th>
			<th width="10%"></th>
			<th width="25%">Bài viết liên quan</th>
		</tr>

		<tr>
			<th>
				<div class="nojs-tags hide-if-js">
					<label for="tax-input-post_tag">Thêm/Xóa thẻ</label>
					<p><textarea name="post_tag" rows="3" cols="20" class="the-tags" id="tax-input-post_tag" aria-describedby="new-tag-post_tag-desc"></textarea></p>
				</div>
				<div>
					<input type="text" id="new-tag" size="50" class="newtag">
					<input type="button" class="button tagadd" value="Thêm">
				</div>
				<ul class="tagchecklist" role="list"></ul>
			</th>
			
			<th><a href="javascript:void(0)" class="button button-action button-review" ><i class="dashicons dashicons-controls-repeat" style="position: relative; top: 3px;"></i> Review</a></th>
			
			<th><span class="count-posts-related"><?php echo $this->get_count_posts_related($post_id); ?></span> <small>Bài viết</small></th>
		</tr>

		<tr>
			<th colspan="2"><a href="javascript:void(0)" data-post_id="<?php echo $post_id ?>" class="button button-primary submit-button">Save Change</a></th>
		</tr>	

	</table>
</div>

<script>
	<?php 
		$post_tags   = get_the_tags( $post_id );
		if ($post_tags) :
			$tags = array();
			foreach ($post_tags as $key => $tag) {
				$tags[] = $tag->name;
			}
			$tags = json_encode($tags);
	?>
		the_tags = <?php echo $tags; ?>;
		display_term(the_tags);
	<?php endif; ?>

</script>

<script>
	jQuery(document).ready(function($) {
		var cache;
		var last; 
		var tempID = 0;
		var separator =  ',';

    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    jQuery( "#new-tag" )
      // don't navigate away from the field on tab when selecting an item
      .on( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            jQuery( this ).autocomplete( "instance" ).menu.active ) {
          	event.preventDefault();
        }
      })
      .autocomplete({
        source: function( request, response ) {
      		var term;

			if ( last === request.term ) {
				response( cache );
				return;
			}

          	jQuery.ajax({
				url: MyAjax.ajaxurl + "pokamodule_ajax",
				type: 'post',
		        data: {
	           		page : "manager-posts-related",
	           		task : "get-post-tags",
	           		q    : request.term
		        },
				success: function(data) {
					var tagName;
					var tags = [];

					if ( data ) {
						data = data.split( '\n' );
						for ( tagName in data ) {
							var id = ++tempID;

							tags.push(data[tagName]);
						}

						cache = tags;
						response( tags );
					} else {
						response( tags );
					}
					last = request.term;
					jQuery( "#new-tag" ).removeClass( 'ui-autocomplete-loading' );
		   		}
		   	}).progress( function() {
				 jQuery( "#new-tag" ).addClass( 'ui-autocomplete-loading' ); 
			} );

        },
        search: function() {
          // custom minLength
          var term = extractLast( this.value );
          if ( term.length < 2 ) {
            return false;
          }
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( ", " );
          return false;
        }
      });
});
</script>