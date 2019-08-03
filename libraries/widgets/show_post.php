<?php
	class poka_widget_show_post extends WP_Widget {
		public function __construct() {
			$id_base        = 'poka_widget_show_post';
			$options = array(
				'classname'   => 'poka-widget-show-post',
				'description' => 'Hiển thị bài viết theo ID hoặc Category'
			);
			
			parent::__construct($id_base, '- [POKA] Show Post', $options);
			
			/*
			 * Load CSS, JS
			 if(!empty(is_active_widget(false, false, $id_base,true))){
				wp_enqueue_style('vutienit_widget_post', VUTIENIT_SP_CSS_URL . '/vutienit_bk_post.css',array());
			}*/
		}
		
		/*======Back End======*/
		public function form($instance){
			$title                = isset( $instance['title'] ) ? $instance['title'] : '';
			$post_category        = isset( $instance['post_category'] ) ? $instance['post_category'] : '';
			$post_limit           = isset( $instance['post_limit'] ) ? $instance['post_limit'] : 5;
			$show_thumbnail       = isset( $instance['show_thumbnail'] ) ? $instance['show_thumbnail'] : '';
			$size_thumbnail       = isset( $instance['size_thumbnail'] ) ? $instance['size_thumbnail'] : '';
			$show_date            = isset( $instance['show_date'] ) ? $instance['show_date'] : '';
			$show_author          = isset( $instance['show_author'] ) ? $instance['show_author'] : '';
			$show_category        = isset( $instance['show_category'] ) ? $instance['show_category'] : '';
			$show_descrition      = isset( $instance['show_descrition'] ) ? $instance['show_descrition'] : '';
			$number_descrition    = isset( $instance['number_descrition'] ) ? $instance['number_descrition'] : 150;
			$show_dots            = isset( $instance['show_dots'] ) ? $instance['show_dots'] : '';
			$view_more            = isset( $instance['view_more'] ) ? $instance['view_more'] : '';
			$post_ids             = isset( $instance['post_ids'] ) ? $instance['post_ids'] : '';
			$widget_stype         = isset( $instance['widget_stype'] ) ? $instance['widget_stype'] : '';
			
			
			$aCate       = get_categories();
			$sCategories = "<option value='all'>- Tất Cả -</option>";
			
			$sSelected = "";
			if($post_category == "related"){
				$sSelected = "selected='selected'";
			}
			$sCategories .= "<option $sSelected value='related'>Bài Liên Quan</option>";
			
			if(count($aCate)){
				foreach($aCate as $cate){
					if($post_category == $cate->term_id){
						$sCategories .= "<option selected='selected' value='" . $cate->term_id . "'>" . $cate->name . "</option>";
					}else{
						$sCategories .= "<option value='" . $cate->term_id . "'>" . $cate->name . "</option>";
					}
				}
			}
			
			/*=====Thumbnail=====*/
			$aThumbnail = array();
			if($show_thumbnail == 0){
				$aThumbnail[0] = "<option value='0' selected='selected'>Không</option>";
			}else{
				$aThumbnail[0] = "<option value='0'>Không</option>";
			}
			
			if($show_thumbnail == 1){
				$aThumbnail[1] = "<option value='1' selected='selected'>Có</option>";
			}else{
				$aThumbnail[1] = "<option value='1'>Có</option>";
			}
			
			$arrSizeThumbnail = array(
				'thumbnail' => 'Thumbnail',
				'medium'    => 'Medium',
				'large'     => 'Large',
				'full'      => 'Full'
			);
			
			
			/*=====Size Thumb====*/
			$htmlThumbSize = '';
			foreach ($arrSizeThumbnail as $keyThumb => $valueThumb){
				if($keyThumb == $size_thumbnail){
					$htmlThumbSize .= '<option selected="selected" value="'.$keyThumb.'">'.$valueThumb.'</option>';
				}else{
					$htmlThumbSize .= '<option value="'.$keyThumb.'">'.$valueThumb.'</option>';
				}
			}
			
			/*=====Date=====*/
			$aShowDate = array();
			if($show_date == 0){
				$aShowDate[0] = "<option value='0' selected='selected'>Không</option>";
			}else{
				$aShowDate[0] = "<option value='0'>Không</option>";
			}
			
			if($show_date == 1){
				$aShowDate[1] = "<option value='1' selected='selected'>Có</option>";
			}else{
				$aShowDate[1] = "<option value='1'>Có</option>";
			}
			
			/*=====Author=====*/
			$aShowAuthor = array();
			if($show_author == 0){
				$aShowAuthor[0] = "<option value='0' selected='selected'>Không</option>";
			}else{
				$aShowAuthor[0] = "<option value='0'>Không</option>";
			}
			
			if($show_author == 1){
				$aShowAuthor[1] = "<option value='1' selected='selected'>Có</option>";
			}else{
				$aShowAuthor[1] = "<option value='1'>Có</option>";
			}
			
			/*=====Category=====*/
			$aShowCategory = array();
			if($show_category == 0){
				$aShowCategory[0] = "<option value='0' selected='selected'>Không</option>";
			}else{
				$aShowCategory[0] = "<option value='0'>Không</option>";
			}
			
			if($show_category == 1){
				$aShowCategory[1] = "<option value='1' selected='selected'>Có</option>";
			}else{
				$aShowCategory[1] = "<option value='1'>Có</option>";
			}
			
			/*=====Descrition=====*/
			$aShowDescrition = array();
			if($show_descrition == 0){
				$aShowDescrition[0] = "<option value='0' selected='selected'>Không</option>";
			}else{
				$aShowDescrition[0] = "<option value='0'>Không</option>";
			}
			
			if($show_descrition == 1){
				$aShowDescrition[1] = "<option value='1' selected='selected'>Có</option>";
			}else{
				$aShowDescrition[1] = "<option value='1'>Có</option>";
			}
			
			/*===== show descrition dots=====*/
			$aShowDots = array();
			if($show_dots == 0){
				$aShowDots[0] = "<option value='0' selected='selected'>Không</option>";
			}else{
				$aShowDots[0] = "<option value='0'>Không</option>";
			}
			
			if($show_dots == 1){
				$aShowDots[1] = "<option value='1' selected='selected'>Có</option>";
			}else{
				$aShowDots[1] = "<option value='1'>Có</option>";
			}
			
			/*=====View More=====*/
			$aViewMore = array();
			if($view_more == 0){
				$aViewMore[0] = "<option value='0' selected='selected'>Không</option>";
			}else{
				$aViewMore[0] = "<option value='0'>Không</option>";
			}
			
			if($view_more == 1){
				$aViewMore[1] = "<option value='1' selected='selected'>Có</option>";
			}else{
				$aViewMore[1] = "<option value='1'>Có</option>";
			}
			
			/*Form HTML*/
			?>
			<div class="poka-widget-post">
				<p>
					<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Tiêu đề:'); ?></label>
					<input class="widefat" type="text" value="<?php echo $title; ?>"
					       id="<?php echo $this->get_field_id('title'); ?>"
					       name="<?php echo $this->get_field_name('title'); ?>">
				</p>
				<p>
					<label for="<?php echo $this->get_field_id('post_category'); ?>"><?php _e('Danh Mục:'); ?></label>
					<select id="<?php echo $this->get_field_id('post_category'); ?>"
					        name="<?php echo $this->get_field_name('post_category'); ?>">
						<?php echo $sCategories; ?>
					</select>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id('post_ids'); ?>"><?php _e('Chọn bài viết theo ID (cách nhau dấu phẩy):'); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id('post_ids'); ?>"
					       name="<?php echo $this->get_field_name('post_ids'); ?>" value="<?php echo $post_ids; ?>"
					       type="text"/>
					<span class="description">Ví dụ: 12,56,23...</span>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id('post_limit'); ?>"><?php _e('Số bài viết:'); ?></label>
					<input class="tiny-text" id="<?php echo $this->get_field_id('post_limit'); ?>"
					       name="<?php echo $this->get_field_name('post_limit'); ?>" value="<?php echo $post_limit; ?>"
					       step="1" min="1" value="5" size="3" type="number"/>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id('show_thumbnail'); ?>"><?php _e('Hiển thị ảnh Thumbnail:'); ?></label>
					<select id="<?php echo $this->get_field_id('show_thumbnail'); ?>"
					        name="<?php echo $this->get_field_name('show_thumbnail'); ?>">
						<?php echo implode("", $aThumbnail); ?> ;
					</select>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id('size_thumbnail'); ?>"><?php _e('Kích thước hình ảnh:'); ?></label>
					<select id="<?php echo $this->get_field_id('size_thumbnail'); ?>"
					        name="<?php echo $this->get_field_name('size_thumbnail'); ?>">
						<?php echo $htmlThumbSize; ?> ;
					</select><br>
					<span style="font-style: italic;">Mặc định: medium</span>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id('show_date'); ?>"><?php _e('Hiển thị ngày tháng:'); ?></label>
					<select id="<?php echo $this->get_field_id('show_date'); ?>"
					        name="<?php echo $this->get_field_name('show_date'); ?>">
						<?php echo implode("", $aShowDate); ?> ;
					</select>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id('show_author'); ?>"><?php _e('Hiển thị tác giả:'); ?></label>
					<select id="<?php echo $this->get_field_id('show_author'); ?>"
					        name="<?php echo $this->get_field_name('show_author'); ?>">
						<?php echo implode("", $aShowAuthor); ?> ;
					</select>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id('show_category'); ?>"><?php _e('Hiển thị category:'); ?></label>
					<select id="<?php echo $this->get_field_id('show_category'); ?>"
					        name="<?php echo $this->get_field_name('show_category'); ?>">
						<?php echo implode("", $aShowCategory); ?> ;
					</select>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id('show_descrition'); ?>"><?php _e('Hiển thị mô tả:'); ?></label>
					<select id="<?php echo $this->get_field_id('show_descrition'); ?>"
					        name="<?php echo $this->get_field_name('show_descrition'); ?>">
						<?php echo implode("", $aShowDescrition); ?> ;
					</select>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id('number_descrition'); ?>"><?php _e('Số lượng từ mô tả:'); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id('number_descrition'); ?>"
					       name="<?php echo $this->get_field_name('number_descrition'); ?>" value="<?php echo $number_descrition; ?>"
					       type="number"/>
					<span class="description">Ví dụ: 120, 150,... (Mặc định: 150)</span>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id('show_dots'); ?>"><?php _e('Hiển thị dấu ...'); ?></label>
					<select id="<?php echo $this->get_field_id('show_dots'); ?>"
					        name="<?php echo $this->get_field_name('show_dots'); ?>">
						<?php echo implode("", $aShowDots); ?> ;
					</select>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id('view_more'); ?>"><?php _e('Hiện nút xem thêm:'); ?></label>
					<select id="<?php echo $this->get_field_id('view_more'); ?>"
					        name="<?php echo $this->get_field_name('view_more'); ?>">
						<?php echo implode("", $aViewMore); ?> ;
					</select>
				</p>
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
			$instance['post_category']     = ( ! empty( $new_instance['post_category'] ) ) ? strip_tags( $new_instance['post_category'] ) : '';
			$instance['post_limit']        = ( ! empty( $new_instance['post_limit'] ) ) ? intval( strip_tags( $new_instance['post_limit'] ) ) : '';
			$instance['post_ids']          = ( ! empty( $new_instance['post_ids'] ) ) ? ( strip_tags( $new_instance['post_ids'] ) ) : '';
			$instance['show_thumbnail']    = ( ! empty( $new_instance['show_thumbnail'] ) ) ? htmlspecialchars( $new_instance['show_thumbnail'] ) : '';
			$instance['size_thumbnail']    = ( ! empty( $new_instance['size_thumbnail'] ) ) ? htmlspecialchars( $new_instance['size_thumbnail'] ) : '';
			$instance['show_date']         = ( ! empty( $new_instance['show_date'] ) ) ? htmlspecialchars( $new_instance['show_date'] ) : '';
			$instance['show_author']       = ( ! empty( $new_instance['show_author'] ) ) ? htmlspecialchars( $new_instance['show_author'] ) : '';
			$instance['show_category']     = ( ! empty( $new_instance['show_category'] ) ) ? htmlspecialchars( $new_instance['show_category'] ) : '';
			$instance['show_descrition']   = ( ! empty( $new_instance['show_descrition'] ) ) ? htmlspecialchars( $new_instance['show_descrition'] ) : '';
			$instance['number_descrition'] = ( ! empty( $new_instance['number_descrition'] ) ) ? intval($new_instance['number_descrition']) : '';
			$instance['show_dots']         = ( ! empty( $new_instance['show_dots'] ) ) ? htmlspecialchars($new_instance['show_dots']) : '';
			$instance['view_more']         = ( ! empty( $new_instance['view_more'] ) ) ? htmlspecialchars( $new_instance['view_more'] ) : '';
			$instance['widget_stype']      = ( ! empty( $new_instance['widget_stype'] ) ) ? strip_tags( $new_instance['widget_stype'] ) : '';
			
			return $instance;
		}
		
		/*======Front End======*/
		public function widget($args, $instance){
			global $post;
			
			$title = apply_filters('widget_title', $instance['title']);
			
			$post_category     = isset( $instance['post_category'] ) ? $instance['post_category'] : '';
			$post_limit        = isset( $instance['post_limit'] ) ? $instance['post_limit'] : 5;
			$post_ids          = isset( $instance['post_ids'] ) ? $instance['post_ids'] : '';
			$widget_stype      = isset( $instance['widget_stype'] ) ? $instance['widget_stype'] : '';
			$show_thumbnail    = isset( $instance['show_thumbnail'] ) ? $instance['show_thumbnail'] : 0;
			$size_thumbnail    = isset( $instance['size_thumbnail'] ) ? $instance['size_thumbnail'] : 'medium';
			$show_date         = isset( $instance['show_date'] ) ? $instance['show_date'] : 0;
			$show_author       = isset( $instance['show_author'] ) ? $instance['show_author'] : 0;
			$show_category     = isset( $instance['show_category'] ) ? $instance['show_category'] : 0;
			$show_descrition   = isset( $instance['show_descrition'] ) ? $instance['show_descrition'] : 0;
			$number_descrition = isset( $instance['number_descrition'] ) ? $instance['number_descrition'] : 150;
			$show_dots        = isset( $instance['show_dots'] ) ? $instance['show_dots'] : '';
			$view_more         = isset( $instance['view_more'] ) ? $instance['view_more'] : 0;
			
			if(count($post_category)){
				$html = $args['before_widget'];
				if(!empty($title)){
					$html .= "<h2 class='widget-title'>" . htmlspecialchars_decode($title) . "</h2>";
				}
				
				$sThumbnail = $size_thumbnail;
				switch($post_category){
					case "related":
						$orig_post = $post;
						global $post;
						
						$categories = get_the_category($post->ID);
						if ($categories){
							$category_ids = array();
							foreach($categories as $individual_category){
								$category_ids[] = $individual_category->term_id;
							}
							$aArgs = array(
								'category__in'     => $category_ids,
								'post__not_in'     => array($post->ID),
								'posts_per_page'   => $post_limit,
								'ignore_sticky_posts' => true
							);
						}
						break;
					case "all":
						$aArgs = array(
							'posts_per_page'      => $post_limit,
							'no_found_rows'       => true,
							'post_status'         => 'publish',
							'ignore_sticky_posts' => true
						);
						
						if(!empty($post_ids)){
							$aArgs["post__in"] = explode(",", trim($post_ids));
						}
						
						break;
					default:
						$aArgs = array(
							'posts_per_page'      => $post_limit,
							'no_found_rows'       => true,
							'post_status'         => 'publish',
							'ignore_sticky_posts' => true
						);
						
						if(!empty($post_category)){
							$aArgs["category__in"] = $post_category;
						}
						break;
				}
				
				$wpQuery = new WP_Query($aArgs);
				
				if($wpQuery->have_posts()){
					$html .= '<ul class="poka-list-post '.$widget_stype.'">';
					while($wpQuery->have_posts()) :
						$wpQuery->the_post();
						
						$html .= '<li class="item-post">';
						if($show_thumbnail){
							$htmlThumbnail = '<div class="post-image">
                                                <a href="' . get_permalink() . '">'.get_the_post_thumbnail(get_the_ID(), $sThumbnail).'</a>
                                              </div>';
						}else{
							$htmlThumbnail = '';
						}
						
						$html .= $htmlThumbnail . '<div class="post-content">';
      
						$html .=   '<div class="post-title">
                                            <a class="title" href="' . get_permalink() . '">' . get_the_title() . '</a>
                                      </div>';
						
						if($show_date){
							$html .= '<div class="post-date">' . get_the_date() . '</div>';
						}
						
						if($show_author){
							$html .= '<div class="post-author">' . get_the_author() . '</div>';
						}
						
						if($show_category){
							$html .= '<div class="post-category">';
							foreach((get_the_category()) as $category) {
								$html .=  '<a href="'.esc_url(get_category_link( $category->cat_ID)).'">'.esc_html($category->cat_name).'</a> ';
							}
							$html .= '</div>';
						}
						
						if($show_descrition){
							if(empty( $number_descrition)){
								$number_descrition = 150;
							}
							
							$descrition = wp_strip_all_tags(get_the_content());
							$descrition = mb_substr($descrition, 0, $number_descrition);
							
							if($show_dots){
								$descrition .= '...';
							}
							
							$html .= '<div class="post-descrition">' . $descrition . '</div>';
						}
						
						if($view_more){
							$html .= '<div class="view-more"><a href="' . get_permalink() . '">Xem Thêm</a></div>';
						}
						
						$html .= '</li>';
					endwhile;
					
					$html .= '</ul><div class="clear"></div>';
					
					wp_reset_postdata();
				}
				
				wp_reset_query();
				
				$html .= $args['after_widget'];
				
				echo $html;
			}
		}
		
	}
