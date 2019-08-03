jQuery(document).ready(function($) {
    $(".poka-manager-image .item").on("click", function () {
        $.ajax({
            url: MyAjax.ajaxurl + "pokamodule_ajax",
            type: 'post',
            data: {
                page        : "pokamodule-shortcode",
                task        : "manager-image",
                security    : MyAjax.security_code
            },
            success: function(data, status, jsXHR) {
                $html = JSON.parse(data);
            }
        });
    });
});

