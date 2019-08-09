<?php
    global $wpdb;
    $tbl = $wpdb->prefix . 'manager_builder_product';
	
	global $wp_query;
	
	$userID = get_current_user_id();
	
	$sql    = "SELECT * FROM `{$tbl}` WHERE `user_id` = {$userID} ORDER BY `id` DESC";
	
	$nTotal = $wpdb->query($sql);
	
	$itemsPerPage = 6;
	$paged        = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
	$offset       = ($paged - 1) * $itemsPerPage;
	$sql          .= ' LIMIT ' . $itemsPerPage . ' OFFSET ' . $offset;
	
	$result = $wpdb->get_results($sql, ARRAY_A);
?>

<?php
    if(!empty($result)){
	    $sHtml = '';
	    
	    $i = 1;
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
            
            $sLink = get_permalink(5196) . '?type=choose-product&restore=' . $v['id'];
            $sRestore = '<a href="'.$sLink.'"><i class="fa fa-undo"></i> Xây dựng tiếp</a>';
	        $sHtml .= '<tr>
                        <td>'.$i.'</td>
                        <td>'.$v['id'].'</td>
                        <td>'.$sHtmlData.'</td>
                        <td>'.date('d/m/Y h:i:s', $v['date']).'</td>
                        <td>'.date('d/m/Y h:i:s', $v['date_update']).'</td>
                        <td>'.$sRestore.'</td>
                    </tr>';
	        $i++;
        }
        ?>
        <div class="woocommerce-builder-product">
            <table>
                <tr>
                    <th>STT</th>
                    <th>ID</th>
                    <th>Không gian</th>
                    <th>Ngày tạo</th>
                    <th>Ngày sửa</th>
                    <th>Thao tác</th>
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