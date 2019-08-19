<?php
    global $wpdb, $pokaSession;
    $tbl = $wpdb->prefix . 'manager_builder_product';
	
	global $wp_query;
	
	$userID = get_current_user_id();
	
	$sql    = "SELECT * FROM `{$tbl}` WHERE `user_id` = {$userID} AND `status` = 1 ORDER BY `id` DESC";
	
	$nTotal = $wpdb->query($sql);
	
	$itemsPerPage = 6;
	$paged        = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
	$offset       = ($paged - 1) * $itemsPerPage;
	$sql          .= ' LIMIT ' . $itemsPerPage . ' OFFSET ' . $offset;
	
	$result = $wpdb->get_results($sql, ARRAY_A);
	
	if(isset($_GET['remove']) && $_GET['remove'] > 0){
		$urlRedrect = get_permalink() . 'builder-product/';
		if (!isset($_GET['builder_product']) || !wp_verify_nonce($_GET['builder_product'], 'builder_product_none')) {
		}else{
            $sql = "SELECT `id` FROM `{$tbl}` WHERE `user_id` = {$userID} AND `id` = {$_GET['remove']}";
			$resultCheck = $wpdb->get_row($sql);
			if(!empty($resultCheck)){
				$data = array(
					'status' => 2,
				);
				$format =  array('%d');
				
				$where = array('id'=> $_GET['remove']);
				$where_format = array('%d');
				$wpdb->update($tbl, $data, $where,$format,$where_format);
				
				$pokaSession->set('msg', array(
					'type' => 'success',
					'msg'  => 'Xóa bỏ thành công!',
				));
            }
        }
		
		wp_redirect($urlRedrect);
		exit();
    }
?>

<?php
	$getMsg = $pokaSession->get('msg');
	if(!empty($getMsg)){
		$pokaSession->delete('msg');
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $.alert({
                    title: 'Thông báo!',
                    content: '<?php echo $getMsg['msg']; ?>',
                    type: 'orange',
                    typeAnimated: true,
                });
            });
        </script>
        <?php
	}
	
	
	if(!empty($result)){
	    $sHtml = '';
	    
	    $i = 1;
		
		$idConfig = get_option("_iart_page_config_product");
		$sLinkRestore = '';
		$sRestore = '';
		if(!empty($idConfig)){
			$sLinkRestore = get_permalink($idConfig) . '?type=choose-product&restore=';
        }
		
        foreach($result as $v){
            $aData = unserialize($v['data']);
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
	
            if(!empty($sLinkRestore)){
	            $sRestore = '<div class="iart-tooltip">
                            <a href="'.$sLinkRestore.'" class="button restore-remove-builder"><i class="fa fa-eye"></i></a>
                           <span class="tooltiptext">Xem dự án</span>
                        </div>';
            }
	
	        $sLink = get_permalink() . 'builder-product/?remove=' . $v['id'];
	        $sLink = wp_nonce_url($sLink, 'builder_product_none', 'builder_product');
	        $sDelete = '<div class="iart-tooltip">
                            <a href="'.$sLink.'" class="button remove-builder"><i class="fa fa-trash"></i></a>
                           <span class="tooltiptext">Xóa</span>
                        </div>';
            
            $sHtml .= '<tr>
                        <td>'.$i.'</td>
                        <td>'.$v['name'].'</td>
                        <td>'.$sHtmlData.'</td>
                        <td class="date">'.date('d/m/Y', $v['date']).' <br /> '.date('h:i:s', $v['date']).'</td>
                        <td class="date">'.date('d/m/Y', $v['date_update']).' <br />'.date('h:i:s', $v['date_update']).'</td>
                        <td class="action">'.$sRestore.$sDelete.'</td>
                    </tr>';
	        $i++;
        }
        ?>
        <div class="woocommerce-builder-product builder-product">
            <table>
                <tr>
                    <th>STT</th>
                    <th>Tên dự án</th>
                    <th>Không gian</th>
                    <th class="date">Ngày tạo</th>
                    <th class="date">Ngày sửa</th>
                    <th class="action">Thao tác</th>
                </tr>
                <?php echo $sHtml; ?>
            </table>
        </div>
        
        <?php
	        $nTotalPage = ceil($nTotal / $itemsPerPage);
	        if($nTotalPage > 1){
		        ?>
                <div class="pagination">
			        <?php
				        echo paginate_links(array(
					        //'base'         => str_replace( 999999999, '%#%', esc_url(get_pagenum_link( 999999999))),
					        'base'         => add_query_arg('paged', '%#%'),
					        'total'        => $nTotalPage,
					        'current'      => $paged,
					        'format'       => '?paged=%#%',
					        'show_all'     => false,
					        'type'         => 'plain',
					        'end_size'     => 2,
					        'mid_size'     => 1,
					        'prev_next'    => true,
					        'prev_text'    => '«',
					        'next_text'    => '»',
					        'add_args'     => false,
					        'add_fragment' => '',
				        ));
			        ?>
                </div>
		        <?php
	        }
    }else{
        echo '<p>Không có lịch sử xây dựng sản phẩm</p>';
    }
?>