<?php 
	global $wpdb;
	$limit  = 15;

	$task   = $_REQUEST["task"];
	$paged  = intval(@$_REQUEST["paged"]);
	$tag_id = intval(@$_REQUEST["tag_id"]);
	$s      = trim(@$_REQUEST["s"]);

	$sql    = "SELECT  p.ID, p.post_title, p.`post_modified` FROM ". $wpdb->prefix ."posts p
			LEFT JOIN ". $wpdb->prefix ."term_relationships r
			ON (p.ID = r.object_id)
			LEFT JOIN ". $wpdb->prefix ."term_taxonomy t
			ON (r.term_taxonomy_id = t.term_taxonomy_id)
			WHERE t.term_id = ".$tag_id;

	if( $task == "loadmove-posts" ){
		$sql .= " LIMIT ". (($paged - 1)*$limit) .",". $limit;
	}

	if( $task == "search-posts" ){
		$sql .= " AND p.post_title LIKE N'%". $s ."%'";
		$sql .= " LIMIT ".$limit;
	}
	echo $sql;
	$posts = $wpdb->get_results($sql);


	if (!empty($posts)) {
		?>
		<tr>
			<th width="8%">#</th>
			<th width="65%">Tên bài viết</th>
			<th width="15%">Ngày cập nhật</th>
			<th width="25%">Thao tác</th>
		</tr>
		<?php	
		foreach ($posts as $i => $post) {
			$link_edit   =  add_query_arg(
								array(
									'post'   => $post->ID,
									'action' => 'edit'
								),
								get_admin_url() . 'post.php'
							);
			?>
			<tr>
				<td><?php echo $post->ID;  ?></td>
				<td>
					<a href="<?php echo get_permalink($post->ID); ?>" target="_blank">
						<strong><?php echo $post->post_title; ?></strong>
					</a>
				</td>

				<td>
					<?php echo date("d/m/Y",strtotime($post->post_modified)); ?>	
				</td>
				<td>
					<a href="javascript:void(0)" class="button button-primary">Remove tag</a>
					<a href="<?php echo $link_edit; ?>" class="button button-action" target="_blank">Edit post</a>
				</td>
			</tr>
			<?php
		}
	}
