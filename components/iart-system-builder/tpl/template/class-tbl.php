<?php
	if(!class_exists('WP_List_Table')){
		require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
	}
	
	class Poka_Tables extends WP_List_Table{
		private $_per_page = 10;
		private $_sql;
		private $_total_item;
		private $_page;
		
		public function __construct(){
			$this->_page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
			
			parent::__construct(array(
				'plural' => 'article',
				'singular' => 'article',
				'ajax' => false,
				'screen' => null,
			) );
		}
		
		public function prepare_items(){
			$columns 	= $this->get_columns();
			$hidden 	= $this->get_hidden_columns();
			$sortable 	= $this->get_sortable_columns();
			
			$this->_column_headers = array($columns,$hidden,$sortable);
			$this->items = $this->table_data();
			
			$total_items 	= $this->total_items();
			$per_page 		= $this->_per_page;
			$total_pages 	= ceil($total_items/$per_page);
			
			$this->set_pagination_args(array(
				'total_items' 	=> $total_items,
				'per_page' 		=> $per_page,
				'total_pages' 	=> $total_pages
			));
		}
		
		private function table_data(){
			global $wpdb;
			
			
			//&orderby=title&order=asc
			$orderby 	= (@$_REQUEST['orderby'] == '')?'id':$_REQUEST['orderby'];
			$order		= (@$_REQUEST['order'] == '')?'DESC':$_REQUEST['order'];
			
			$tbl        = $wpdb->prefix . 'manager_builder_product';
			$sql = "SELECT *
				    FROM `{$tbl}`";
			
			//===========Start Where + Search=========
			$whereArr = array();
			
			if(isset($_REQUEST['s']) && !empty($_REQUEST['s'])){
				$s = sanitize_text_field($_REQUEST['s']);
				$whereArr[] = " (`name` LIKE '%{$s}%') ";
			}
			//===========End Where + Search=========
			
			if(count($whereArr)>0){
				$sql .= " WHERE " . join(" AND ", $whereArr);
			}
			//===========End Where + Search=========
			
			$sql .= " ORDER BY `{$orderby}` {$order}";
			
			
			$this->_sql  = $sql;
			
			$paged 		= max(1,@$_REQUEST['paged']);
			$offset 	= ($paged - 1) * $this->_per_page;
			$sql        .= ' LIMIT ' . $this->_per_page . ' OFFSET ' . $offset;
			
			$data = $wpdb->get_results($sql,ARRAY_A);
			
			return $data;
		}
		
		private function total_items(){
			global $wpdb;
			return $wpdb->query($this->_sql);
		}
		
		public function get_sortable_columns(){
			return array(
				'id'          => array('id', false),
				'user_id'     => array('user_id', false),
				'date'        => array('date', false),
				'date_update' => array('date_update', false),
			);
		}
		
		public function get_hidden_columns(){
			return array('content','author_id');
		}
		
		public function get_columns(){
			$arr = array(
				'cb'          => '<input type="checkbox" />',
				'id'          => 'ID',
				'user_id'     => 'User',
				'data'        => 'Thông tin',
				'date'        => 'Ngày tạo',
				'date_update' => 'Ngày sửa',
			);
			
			return $arr;
		}
		
		public function get_bulk_actions(){
			$actions = array(
				'delete'   => 'Delete',
			);
			return $actions;
		}
		
		public function column_data($item){
			if(!empty($item['data'])){
				$aData = unserialize($item['data']);
				$sHtmlData = '';
				if(!empty($aData)){
					$aData = isset($aData['step2']) && !empty($aData['step2']) ? $aData['step2'] : '';
					if(!empty($aData)){
						foreach($aData as $vData){
							$sHtmlData .= ', ' . $vData['name'];
						}
					}
					
					$sHtmlData = ltrim($sHtmlData, ',');
				}
				return $sHtmlData;
			}
		}
		
		public function column_date($item){
			return date('d/m/Y h:i:s', $item['date']);
		}
		
		public function column_date_update($item){
			return date('d/m/Y h:i:s', $item['date_update']);
		}
		
		public function column_user_id($item){
			$sLink = get_admin_url() . 'admin.php?page=' . $this->_page;
			
			$linkDelete =   add_query_arg(
				array(
					'action'  => 'delete',
					'article' => $item['id']
				),
				$sLink
			);
			$action = 'delete_id_' . $item['id'];
			$linkDelete = wp_nonce_url($linkDelete, $action, 'security_code');
			$urlView = '?page=' . $this->_page . '&article=' . $item['id'] . '&action=view';
			
			$actions = array(
				'edit'     => '<a href="'.$urlView.'">Xem cấu hình</a>',
				'delete'   => '<a onclick="return confirm(\'Bạn có chắc chắn muốn xóa?\');" href="'.$linkDelete.'">Xóa</a>',
			);
			
			$userData = get_userdata($item['user_id']);
			
			$html = '<strong><a class="poka_name" href="'.$urlView.'">' . $userData->user_email .'</a></strong>'
			        . $this->row_actions($actions);
			return $html;
		}
		
		protected function extra_tablenav($which){
			if($which == 'top'){
			
			}
		}
		
		public function column_default($item, $column_name){
			return $item[$column_name];
		}
		
		public function column_cb($item){
			$singular = $this->_args['singular'];
			$html = '<input type="checkbox" name="' . $singular .'[]" value="' . $item['id'] .'" />';
			return $html;
		}
		
		public function single_row($item) {
			echo "<tr id='item-". $item["id"] ."'>";
			echo $this->single_row_columns( $item );
			echo "</tr>\n";
		}
	}
