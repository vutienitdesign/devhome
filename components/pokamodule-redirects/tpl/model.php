<?php
	if(!class_exists('WP_List_Table')){
		require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
	}
	
	class Model extends WP_List_Table{
		private $_per_page = 30;
		private $_sql;
		
		public function __construct(){
			//echo '<br/>' . __METHOD__;
			parent::__construct(array(
				'plural'   => 'article',
				'singular' => 'article',
				'ajax'     => false,
				'screen'   => null,
			) );
		}
		
		public function prepare_items(){
			$columns 	 = $this->get_columns();
			$hidden 	 = $this->get_hidden_columns();
			$sortable 	 = $this->get_sortable_columns();
			
			$this->_column_headers = array($columns,$hidden,$sortable);
			$this->items = $this->table_data();
			
			$total_items = $this->total_items();
			$per_page    = $this->_per_page;
			$total_pages = ceil($total_items/$per_page);
			
			$this->set_pagination_args(array(
				'total_items' 	=> $total_items,
				'per_page' 		=> $per_page,
				'total_pages' 	=> $total_pages
			));
			
		}
		
		private function table_data(){
			$data = array();
			
			global $wpdb;
			//&orderby=title&order=asc
			$orderby 	= (@$_REQUEST['orderby'] == '')? 'ID' : $_REQUEST['orderby'];
			$order		= (@$_REQUEST['order'] == '')? 'DESC' : $_REQUEST['order'];
			
			$sql = "SELECT * FROM ". $wpdb->prefix ."redirects";
			
			//===========Start Where + Search=========
			$whereArr    = array();
			$havingArr   = array();
			
			if(isset($_REQUEST['s']) && strlen($_REQUEST['s'])>0){
				$s = trim($_REQUEST['s']);
				$whereArr[] = " ( request LIKE N'%$s%' OR destination LIKE N'%$s%' ) ";
			}

			if(count($whereArr)>0){
				$sql .= " AND ". join(" AND ", $whereArr);
			}
			
			//===========End Where + Search=========

			$sql .= " ORDER BY {$orderby} {$order}";

			$this->_sql  = $sql;
			
			$paged 		= max(1,@$_REQUEST['paged']);
			$offset 	= ($paged - 1) * $this->_per_page;
			$sql .= ' LIMIT ' . $this->_per_page . ' OFFSET ' . $offset;
			
			$data = $wpdb->get_results($sql,ARRAY_A);
			
			return $data;
		}

		public function all_data()
		{
			global $wpdb;
			return $wpdb->get_results($this->_sql,ARRAY_A);
		}
		
		private function total_items(){
			global $wpdb;
			return $wpdb->query($this->_sql);
		}
		
		public function get_sortable_columns(){
			return array(
				'id'            => array('ID', false),
				'request'       => array('request', false),
				'destination'   => array('destination', true),
				'latest_update' => array('latest_update', true)
			);
		}
		
		public function get_hidden_columns(){
			return array('content','author_id');
		}
		
		public function get_columns(){
			$arr = array(
				'cb'            => '<input type="checkbox" />',
				'id'            => '#',
				'request'       => 'Request',
				'destination'   => 'Destination',
				'latest_update' => 'Latest update',
			);
			return $arr;
		}
		
		public function get_bulk_actions(){
			$actions = array(
				'delete'   => 'Delete',
			);
			return $actions;
		}
		
		protected function extra_tablenav($which){
			if($which == 'top'){

			}
		}

		public function get_views() {
			$views = array();
			return apply_filters( 'terms_table_views', array_filter($views) );
		}

		public function views() {
		    $views = $this->get_views();
			if (empty( $views )) return;

		    $views = apply_filters( "views_{$this->screen->id}", $views );
		 	
		    if ( empty( $views ) )
		        return;
		 
		    $this->screen->render_screen_reader_content( 'heading_views' );
		 
		    echo "<ul class='subsubsub'>\n";
		    foreach ( $views as $class => $view ) {
		        $views[ $class ] = "\t<li class='$class'>$view";
		    }
		    echo implode( " |</li>\n", $views ) . "</li>\n";
		    echo "</ul>";
		}
		
		public function column_default($item, $column_name){
			return $item[$column_name];
		}
		
		public function column_latest_update($item){
			$data =  date('Y-m-d h:i:s a', $item['latest_update']);
			
			return  $data;
		}
		
		
		public function column_request($item){
			$page       = $_REQUEST['page'];
			$confirm    = "onclick='return confirm(\"Are you sure you want to Remove?\");'";
			$name       = 'security_code';

			$linkDelete = $this->create_url(array("action"=>"delete","article"=>$item['id']));

			$linkEdit   =   add_query_arg(
								array(
									'taxonomy'  => 'post_tag',
									'tag_id'    => $item['id'],
									'post_type' => "post"
								),
								get_admin_url() . 'term.php'
							);

			$action 	= 'delete_id_' . $item['id'];
			$linkDelete = wp_nonce_url($linkDelete,$action,$name);
			
			$actions = array(
				'edit' 		=> '<a href="javascript:void(0)" class="quick-edit" data-id="'. $item['id'] .'">Quick edit</a>',
				'delete' 	=> '<a '. $confirm .' href="' . $linkDelete . '">Delete</a>'
			);
			
			$html = '<strong>'.$item['request'].'</strong>'
			        . $this->row_actions($actions);
			return $html;
		}
		
		public function column_cb($item){
			$singular = $this->_args['singular'];
			$html = '<input type="checkbox" name="' . $singular .'[]" value="' . $item['id'] .'" />';
			return $html;
		}

		public function create_url($args)
		{
			$default = array(
					'orderby'        => @$_REQUEST['orderby'],
					'order'          => @$_REQUEST['order'],
					's'              => @$_REQUEST['s'],
					'paged'          => (intval(@$_REQUEST['paged']) ? intval(@$_REQUEST['paged']) : false),
				);
	
			return $url = add_query_arg(
				wp_parse_args($args, $default),
				get_admin_url() . 'admin.php?page=' . $_REQUEST["page"]
			);
		}

		public function single_row( $item ) {
            echo "<tr id='item-". $item["id"] ."'>";
            echo $this->single_row_columns( $item );
            echo "</tr>\n";
        }
	}
