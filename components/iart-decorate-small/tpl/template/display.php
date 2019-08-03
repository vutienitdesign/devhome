<?php
	global $wpdb, $pokaSession;
	
	$sPage = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
	
	wp_enqueue_style($sPage);
	wp_enqueue_script($sPage);
	
	$vTbl = new Poka_Tables();
	$vTbl->prepare_items();
?>
<div class="wrap">
    <h1 class="wp-heading-title">
        Trang trí không gian nhỏ
	    <?php
		    $vUrlAdd = '?page=' . $sPage . '&action=add';
	    ?>
        <a href="<?php echo $vUrlAdd; ?>" class="page-title-action">Thêm mới</a>
    </h1>
	
    <hr class="wp-header-end">
	
	<?php
		$msg = '';
		$getMsg = $pokaSession->get('msg');
		if(!empty($getMsg)){
			echo PMCommon::getMsgBE(array($getMsg['msg']), $getMsg['type']);
			$pokaSession->delete('msg');
		}
	?>
    
    <form action="" method="get" id="<?php echo $sPage; ?>">
        <div>
            <input type="hidden" name="page" value="<?php echo $sPage; ?>">
        </div>
		
		<?php
			$vTbl->search_box('Search Name', 'name');
			$vTbl->display();
		?>
    </form>
</div>
