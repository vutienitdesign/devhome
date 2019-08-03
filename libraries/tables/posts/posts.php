<?php
	new POKA_Tables_Posts();
	class POKA_Tables_Posts {
		public function __construct() {
			add_filter("manage_posts_columns",        array($this, "manage_columns"));
			add_action("manage_posts_custom_column",  array($this, "manage_custom_columns"), 10, 2);
			
			/*add_action("manage_posts_extra_tablenav",  array($this, "extra_tablenav"));
			add_filter( 'posts_clauses', array($this, array($this, "modify_query_table"), 10, 2));*/
		}
		
		public function extra_tablenav($which){
			if ($which == "top" && get_query_var( "post_type" ) == "posts" ) {
				echo '<h3 style="color: red; font-size: 16px">' . __FILE__ . '</h3>';
			}
		}
		
		public function modify_query_table( $clauses, $query ) {
			global $wpdb;
			$tbl_ocoin    = $wpdb->prefix."ocoin";
			$tbl_posts    = $wpdb->prefix."posts";
			$tbl_usermeta = $wpdb->prefix."usermeta";
			
			if ($query->is_main_query() && is_admin() && get_query_var( "post_type" ) == "posts" ) {
				$clauses["join"]    .= " LEFT JOIN {$tbl_ocoin} ON ({$tbl_ocoin}.post_id = {$tbl_posts}.ID AND {$tbl_ocoin}.via='web' )
                                         LEFT JOIN {$tbl_usermeta} ON ({$tbl_usermeta}.user_id = {$tbl_posts}.post_author AND {$tbl_usermeta}.meta_key = '_total_ocoin_using' ) ";
				
				$clauses["groupby"] .= " {$tbl_posts}.ID ";
				
				$clauses["fields"]  .= " , SUM({$tbl_ocoin}.ocoin) AS ocoin,
									       SUM(CASE WHEN {$tbl_ocoin}.type = 'like' THEN 1 ELSE 0 END) as count_like,
									       SUM(CASE WHEN {$tbl_ocoin}.type = 'share' THEN 1 ELSE 0 END) as count_share,
									       {$tbl_usermeta}.meta_value AS ocoin_used";
				$clauses["where"]   .= "";
				
				if(!empty($_GET["auth"])){
					$clauses["where"]   .= " AND {$tbl_posts}.post_author=".intval($_GET["auth"]);
				}
				
				if(!empty($_GET["export"])){
					$clauses["limits"]  = "";
				}
			}
			
			// echo "<pre>";
			// print_r($clauses);
			// print_r($query);
			// die;
			
			return $clauses;
		}
		
		public function manage_columns($columns){
//			unset($columns['name']);
			
			$new = array();
			foreach($columns as $key => $title) {
				if($key == "title"){
					$new['fullnames'] = 'Họ Tên 123';
				}
				$new[$key] = $title;
			}
			return $new;
		}
		
		public function manage_custom_columns($column, $post_id){
			if("fullnames" == $column){
				echo 'aaaa BB';
			}
		}
	}