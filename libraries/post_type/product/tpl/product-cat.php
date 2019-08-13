<?php
	get_header();
?>

<div id="primary" class="content-area poka-product-cat">
    <main id="main" class="site-main">
        <article>
            <header class="entry-header">
                <h1 class="entry-title"><?php single_cat_title();?></h1>
            </header>
            <div class="entry-content">
				<?php
					if(have_posts()) {
						while (have_posts()){
							the_post();
							$term = get_queried_object();
							
							echo "<pre style='color: red;font-size: 14px'>aaaa: ";
							print_r(get_the_title());
							echo "</pre>";
						}
					}
				?>
            </div>
            <footer class="entry-footer">
                <nav class="navigation">
					<?php
						if($wp_query->max_num_pages > 1){
							?>
                            <div class="nav-article">
								<?php
									$big = 999999999;
									//#038;
									$pagenum_link = str_replace( $big, '%#%', get_pagenum_link( $big ));
									$pagenum_link = str_replace( '#038;','&',  $pagenum_link);
									
									$args = array(
										'base'               => $pagenum_link,
										'format'             => '?page=%#%',
										'total'              => $wp_query->max_num_pages,
										'current'            => max(1,get_query_var('paged')),
										'show_all'           => false,
										'end_size'           => 1,
										'mid_size'           => 2,
										'prev_next'          => true,
										'prev_text'          => '«',
										'next_text'          => '»',
										'type'               => 'plain',
										'add_args'           => false,
										'add_fragment'       => '',
										'before_page_number' => '',
										'after_page_number'  => ''
									);
									
									echo paginate_links($args);
								?>
                            </div>
							<?php
						}
					?>
                </nav>
            </footer>
        </article>
    </main>
</div><!-- #primary -->

<?php
	get_footer();
?>
