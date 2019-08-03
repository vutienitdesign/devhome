<?php
	$model = new Model();
	$model->prepare_items();
	$page  = @$_REQUEST['page'];
	
	$msg   = '';
	if(isset($_GET['msg'])){
	    if($_GET['msg'] == 1){
		    $msg = PMCommon::getMsgBE(array('You were removed '. intval(@$_REQUEST['success']) .' items successful.'));
        }
    }
?>
<div class="wrap">
    <h1 class="wp-heading-inline">Redirects 301</h1>
    <a href="?page=pokamodule-redirects&action=create" class="page-title-action">Thêm mới</a>
    <hr class="wp-header-end">
    <br />
	<?php
		echo $msg;
	?>
    <br>
	<?php echo $model->views(); ?>

    <form action="" method="get" name="<?php echo $page;?>" id="<?php echo $page;?>">
        <input type="hidden" name="page" value="<?php echo $page; ?>">
	    <?php $model->search_box('search', 'search_id');?>
    </form>
    
    <form action="" method="post" name="<?php echo $page;?>" id="<?php echo $page;?>">
		<?php $model->display();?>
    </form>
</div>