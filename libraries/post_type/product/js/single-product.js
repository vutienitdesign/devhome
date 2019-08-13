jQuery(document).ready(function($){
    if($('.gallery-thumbnail .data').length){
        $('.gallery-thumbnail .data').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite : true,
            autoplay: true,
            autoplaySpeed: 3000,
            dots: true,
            arrows: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    }

    $('[data-fancybox="gallery"]').fancybox({
        selector : '.slick-slide:not(.slick-cloned)',
        hash     : false
    });
});