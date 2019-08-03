<?php
new Poka_Widget();
class Poka_Widget{
	public function __construct() {
		add_action('widgets_init', array($this, 'poka_load_widget'));
	}
	
	public function poka_load_widget() {
		/*Start Create Widget*/
		$arrWidget = array(
			'show_post'         => true, //Hien thi cac bai viet
			'filter_product'    => true  //Hien thi loc gia custom
		);
		foreach($arrWidget as $key => $value){
			if($value == true){
				require_once _POKA_PLUGIN_LIB_PATH_ . 'widgets/' . $key . '.php';
				register_widget('poka_widget_' . $key);
			}
		}
		/*End Create Widget*/
		
		/*Start Register Sidebar*/
		$arrWidgetSiderbar = array(
			/*'poka-widget-list-user' => array(
				'title' => '- [Poka] Phụ trách kinh doanh',
				'desc'  => 'Hiện thị danh sách các thành viên'
			)*/
		);
		if(count($arrWidgetSiderbar) > 0){
			foreach($arrWidgetSiderbar as $keySiderbar => $valueSiderbar){
				register_sidebar(
					array(
						'name'          => $valueSiderbar['title'],
						'id'            => $keySiderbar,
						'description'   => $valueSiderbar['desc'],
						'class'         => '',
						'before_widget' => '<div id="%1$s" class="widget %2$s clr">',
						'after_widget'  => '</div>',
						'before_title'  => '<span class="label-widget">',
						'after_title'   => '</span>'
					)
				);
			}
		}
		
		/*Show Widget*/
			/*
			if(is_active_sidebar('poka-widget-list-user')){
				dynamic_sidebar('poka-widget-list-user');
			}
		 */
		/*End Register Sidebar*/
	}
}