<?php
    get_header();
	
    //https://fancyapps.com/fancybox/3/
	PMCommon::load_lib_assets('fancybox/', 'jquery.fancybox.min.css', 'css');
	PMCommon::load_lib_assets('fancybox/', 'jquery.fancybox.min.js', 'js');
	
	PMCommon::load_lib_assets('slick/', 'slick.min.css', 'css');
	PMCommon::load_lib_assets('slick/', 'slick-theme.min.css', 'css');
	PMCommon::load_lib_assets('slick/', 'slick.min.js', 'js');
	
	wp_enqueue_script('single_product', _POKA_PLUGIN_LIB_URL_ . 'post_type/product/js/single-product.js', array('jquery'), '', true);
?>

<div id="content" class="site-content singe-product">
    <div class="apmag-container">
	    <?php
		    accesspress_mag_breadcrumbs();
	    ?>
        <div id="" class="content-area">
            <main id="main" class="site-main">
                <div class="product-data">
                    <div class="gallery-thumbnail">
                        <div class="data">
                            <a data-fancybox="gallery" href="https://techland.com.vn/wp-content/uploads/upload/san_pham/iphone/iphone-xs-xsmax-xr/xs-max/iphone-xs-max-gold.jpg">
                                <img src="https://techland.com.vn/wp-content/uploads/upload/san_pham/iphone/iphone-xs-xsmax-xr/xs-max/iphone-xs-max-gold.jpg" alt="">
                            </a>
                            <a data-fancybox="gallery" href="https://cdn.tgdd.vn/Products/Images/42/78124/iphone-7-plus-gold-400x460.png">
                                <img src="https://cdn.tgdd.vn/Products/Images/42/78124/iphone-7-plus-gold-400x460.png" alt="">
                            </a>
                        </div>
                    </div>
                    <div class="summary">
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                    </div>
                    
                    <div class="entry-content">
					    <?php
						    the_content();
					    ?>
                    </div>
                </div>
            </main><!-- #main -->
        </div>
    </div>
</div>

<?php
  get_footer();
?>
