<?php
	if(!class_exists('WP_List_Table')){
		require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
	}
	
	class Poka_Tables extends WP_List_Table{
		private $_per_page = 20;
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
			
			$tbl        = $wpdb->prefix . 'decorate_medium';
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
			$sql .= ' LIMIT ' . $this->_per_page . ' OFFSET ' . $offset;
			
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
				'name'        => array('name', false),
				'name_show'   => array('name_show', false),
				'max'   => array('max', false),
				'cat'         => array('cat', false),
				'tag'         => array('tag', false),
				'decorate_large' => array('decorate_large', false),
			);
		}
		
		public function get_hidden_columns(){
			return array('content','author_id');
		}
		
		public function get_columns(){
			$arr = array(
				'cb'             => '<input type="checkbox" />',
				'id'             => 'ID',
				'name'           => 'Name',
				'name_show'      => 'Name Show',
				'max'            => 'Max',
				'tag'            => 'Tag Woocommerce (SET Đồ)',
				'decorate_large' => 'Không gian lớn',
				'decorate_small' => 'Không gian nhỏ',
			);
			
			return $arr;
		}
		
		public function get_bulk_actions(){
			$actions = array(
				'delete'   => 'Delete',
			);
			return $actions;
		}
		
		public function column_decorate_small($item){
			global $wpdb;
			$sql = "SELECT `name` FROM `{$wpdb->prefix}decorate_small` WHERE `decorate_medium` = {$item['id']}";
			$result = $wpdb->get_results($sql);
			
			$sHtml = '';
			if(!empty($result)){
				foreach($result as $v){
					$sHtml .= ', ' . $v->name;
				}
				
				$sHtml = ltrim($sHtml, ',');
			}
			
			return $sHtml;
		}
		
		public function column_decorate_large($item){
			global $wpdb;
			
			$sql = "SELECT `name` FROM `{$wpdb->prefix}decorate_large` WHERE `id` = {$item['decorate_large']}";
			return $wpdb->get_row($sql)->name;
		}
		
		public function column_tag($item){
			$sHtml = '';
			if(!empty($item['tag'])){
				$arr = explode(',', $item['tag']);
				
				foreach($arr as $v){
					$terms = get_term_by('id', $v, 'product_tag' );
					if(!empty($terms)){
						$sHtml .= ', <a href="'.get_term_link($terms).'" target="_blank">'.$terms->name.'</a> ';
					}
				}
				
				$sHtml = ltrim($sHtml, ',');
			}
			return $sHtml;
		}
		
		public function column_name($item){
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
			$urlEdit = '?page=' . $this->_page . '&article=' . $item['id'] . '&action=edit';
			
			$actions = array(
				'edit'     => '<a href="'.$urlEdit.'">Sửa</a>',
				'edit_now' => '<a class="editinline" href="#" edit-now="item-' . $item['id'] . '">Sửa ngay</a>',
				'delete'   => '<a onclick="return confirm(\'Bạn có chắc chắn muốn xóa?\');" href="'.$linkDelete.'">Xóa</a>',
			);
			
			$html = '<strong><a class="poka_name" href="'.$urlEdit.'">' . $item['name'] .'</a></strong>'
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
