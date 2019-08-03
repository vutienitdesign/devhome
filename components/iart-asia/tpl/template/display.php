<?php
    $sPage = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
	wp_enqueue_style($sPage);
//	wp_enqueue_script($sPage);
?>
<div class="wrap">
    <div class="logo"><img src="<?php echo _POKA_PLUGIN_COMPONENT_URL_ . $sPage . '/images/bg.png' ?>"></div>
</div>