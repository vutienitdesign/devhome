<?php
	class poka_widget_filter_product extends WP_Widget {
		public function __construct() {
			$id_base        = 'poka_widget_filter_product';
			$options = array(
				'classname'   => 'poka-widget-filter-product',
				'description' => 'Lọc sản phẩm'
			);
			
			parent::__construct($id_base, '- [POKA] Lọc Sản Phẩm', $options);
			
   
			if(is_active_widget(false, false, $id_base, true)){
				wp_enqueue_style('vutienit_widget_post', _POKA_PLUGIN_LIB_URL_ . 'widgets/css/filter_product.min.css',array());
            }
		}
		
		/*======Back End======*/
		public function form($instance){
			$title        = isset($instance['title']) ? $instance['title'] : '';
			$widget_stype = isset($instance['widget_stype'] ) ? $instance['widget_stype'] : '';
			
			/*Form HTML*/
			?>
			<div class="poka-widget-filter-product">
				<p>
					<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Tiêu đề:'); ?></label>
					<input class="widefat" type="text" value="<?php echo $title; ?>"
					       id="<?php echo $this->get_field_id('title'); ?>"
					       name="<?php echo $this->get_field_name('title'); ?>">
				</p>

                <?php
                    for($i = 0; $i <= 7; $i++){
	                    $label = isset($instance['filter'][$i]['label']) ? $instance['filter'][$i]['label'] : '';
	                    $min   = isset($instance['filter'][$i]['min']) ? $instance['filter'][$i]['min'] : '';
	                    $max   = isset($instance['filter'][$i]['max']) ? $instance['filter'][$i]['max'] : '';
                        ?>
                        <div class="item">
                            <input class="widefat label" placeholder="Nhãn <?php echo $i + 1; ?>" type="text"
                                   value="<?php echo $label; ?>"
                                   name="<?php echo $this->get_field_name('label'); ?>[]">
                            
                            <div class="min">
                                <input class="widefat" type="text" placeholder="Min <?php echo $i + 1; ?>"
                                       value="<?php echo $min; ?>"
                                       name="<?php echo $this->get_field_name('min'); ?>[]">
                            </div>
                            
                            <span>-</span>
                            
                            <div class="max">
                                <input class="widefat" type="text" placeholder="Max <?php echo $i + 1; ?>"
                                       value="<?php echo $max; ?>"
                                       name="<?php echo $this->get_field_name('max'); ?>[]">
                            </div>
                            <div class="clear"></div>
                        </div>
                        <?php
                    }
                ?>
				
				<p>
					<label for="<?php echo $this->get_field_id('widget_stype'); ?>"><?php _e('Style (CSS Class):'); ?></label>
					<input class="widefat" type="text" value="<?php echo $widget_stype; ?>"
					       id="<?php echo $this->get_field_id('widget_stype'); ?>"
					       name="<?php echo $this->get_field_name('widget_stype'); ?>">
					<span class="description">Example: abc, def...</span>
				</p>
			</div>
			<?php
		}
		
		// Updating widget replacing old instances with new
		public function update($new_instance, $old_instance){
			$instance                      = array();
			
			$instance['title']             = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['widget_stype']      = ( ! empty( $new_instance['widget_stype'] ) ) ? strip_tags( $new_instance['widget_stype'] ) : '';
			
			$instance['filter'] = array();
			foreach($new_instance['label'] as $key => $value){
				$instance['filter'][$key]['label'] = sanitize_text_field($value);
				$instance['filter'][$key]['min']   = absint($new_instance['min'][$key]);
				$instance['filter'][$key]['max']   = absint($new_instance['max'][$key]);
            }
            
			return $instance;
		}
		
		/*======Front End======*/
		public function widget($args, $instance){
		    if((is_shop() || is_product_category()) && isset($instance['filter'])){
                $title        = apply_filters('widget_title', $instance['title']);
                $widget_stype = isset($instance['widget_stype'] ) ? $instance['widget_stype'] : '';
            
                $html = $args['before_widget'];
                if(!empty($title)){
                    $html .= "<h2 class='widget-title'>" . htmlspecialchars_decode($title) . "</h2>";
                }
			
                $minPrice = isset($_GET['min_price']) ? $_GET['min_price'] : '';
                $maxPrice = isset($_GET['max_price']) ? $_GET['max_price'] : '';
			    
                $urlCurrent = remove_query_arg(array('min_price', 'max_price'));
			    $html .= '<ul class="poka-filter-product '.$widget_stype.'">';
                $arrFilter = $instance['filter'];
                foreach($arrFilter as $key => $value){
                    if(!empty($value['label'])){
                        $arrQuery = array();
                        
                        if($value['min'] > 0){
	                        $arrQuery['min_price'] = $value['min'];
                        }
	
	                    if($value['max'] > 0){
		                    $arrQuery['max_price'] = $value['max'];
	                    }
	                    
	                    if(count($arrQuery) > 0){
		                    $html .= '<li>';
		                    
		                    if($minPrice == $value['min'] && $maxPrice == $value['max']){
			                    $html .= '<input type="radio" value="male" checked="checked">';
		                    }else{
			                    $html .= '<input type="radio" value="male">';
                            }
		                    
		                    $html .= '<a href="'.esc_url(add_query_arg($arrQuery, $urlCurrent)).'">'.$value['label'].'</a>
                                     </li>';
		                    
                        }
                        
                    }
                }
                $html .= '</ul>';
            
                $html .= $args['after_widget'];
            
                echo $html;
            }
		}
		
	}
