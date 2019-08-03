<?php
	$post_category     = isset($aAtts['show_category'])          ? $aAtts['show_category'] : '';
	$post_limit        = isset($aAtts['limit'])             ? $aAtts['limit'] : 5;
	$post_ids          = isset($aAtts['ids'])               ? $aAtts['ids'] : '';
	$class             = isset($aAtts['class'])             ? trim($aAtts['class']) : '';
	$show_thumbnail    = isset($aAtts['thumbnail'])         ? intval($aAtts['thumbnail']) : 0;
	$size_thumbnail    = isset($aAtts['size_thumbnail'])    ? $aAtts['size_thumbnail'] : 'medium';
	$show_date         = isset($aAtts['date'])              ? intval($aAtts['date']) : 0;
	$show_author       = isset($aAtts['author'])            ? intval($aAtts['author']) : 0;
	$show_category     = isset($aAtts['category'])          ? intval($aAtts['category']) : 0;
	$show_descrition   = isset($aAtts['descrition'])        ? intval($aAtts['descrition']) : 0;
	$number_descrition = isset($aAtts['number_descrition']) ? intval($aAtts['number_descrition']) : 150;
	$show_dots         = isset($aAtts['dots'])              ? intval($aAtts['dots']) : '';
	$view_more         = isset($aAtts['view_more'] )        ? intval($aAtts['view_more']) : 0;
	
    $html = '';
	$sThumbnail = $size_thumbnail;
	
	$arrThum = array('thumbnail', 'medium', 'large', 'full');
	
	if(!in_array($sThumbnail, $arrThum)){
		$sThumbnail = array($size_thumbnail);
    }
    
	$aArgs      = array();
	switch($post_category){
		//Bai Lien Quan
		case "related":
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
			
		//Tat Ca
		case "all":
			$aArgs = array(
				'posts_per_page'      => $post_limit,
				'no_found_rows'       => true,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true
			);
			
			//Theo id
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
		$htmlThumbnail = '';
		$htmlTitle     = '';
		$htmlDate      = '';
		$htmlAuthor    = '';
		$htmlCat       = '';
		$htmlDesc      = '';
		$htmlViewMore  = '';
		
		$html = '<div class="list-post">';
		
            while($wpQuery->have_posts()) :
                $wpQuery->the_post();
                
                //Thumb
                if($show_thumbnail){
                    $htmlThumbnail = '<div class="post-image"><a href="' . get_permalink() . '">'.get_the_post_thumbnail(get_the_ID(), $sThumbnail).'</a></div>';
                }else{
                    $htmlThumbnail = '';
                }
            
                //Title
                $htmlTitle = '<div class="post-title"><a class="title" href="' . get_permalink() . '">' . get_the_title() . '</a></div>';
            
                //Date
                if($show_date){
                    $htmlDate = '<div class="post-date">' . get_the_date() . '</div>';
                }
                
                //Author
                if($show_author){
                    $htmlAuthor = '<div class="post-author">' . get_the_author() . '</div>';
                }
            
                //Category
                if($show_category){
                    $htmlCat = '<div class="post-category">';
                    foreach((get_the_category()) as $category) {
                        $htmlCat .=  '<a href="'.esc_url(get_category_link($category->cat_ID)).'">'.$category->cat_name.'</a> ';
                    }
                    $htmlCat .= '</div>';
                }
            
                //Descrition
                if($show_descrition){
                    if(empty( $number_descrition)){
                        $number_descrition = 150;
                    }
                    
                    $descrition = wp_strip_all_tags(get_the_content());
                    $descrition = mb_substr($descrition, 0, $number_descrition);
                    
                    if($show_dots){
                        $descrition .= '...';
                    }
                    
                    $htmlDesc = '<div class="post-descrition">' . $descrition . '</div>';
                }
            
                //View More
                if($view_more){
                    $htmlViewMore = '<div class="view-more"><a href="' . get_permalink() . '">Xem Thêm</a></div>';
                }
	
	            $html .= '<div class="item-post">';
             
	            $html .= $htmlThumbnail . $htmlTitle . $htmlDate . $htmlAuthor . $htmlCat . $htmlDesc . $htmlViewMore;
                
                $html .= '</div>';
            endwhile;
		
		$html .= '</div>';
		
		wp_reset_postdata();
	}
	
	wp_reset_query();
?>

<div class='poka-list-post <?php echo $class; ?>'>
	<?php
		echo $html;
	?>
</div>
