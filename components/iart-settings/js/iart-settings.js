jQuery(document).ready(function($) {
    $("body").on( "click", ".form .cat-mau-toc", function(event) {
        $.ajax({
            type        : 'post', //post, html, json
            dataType    : 'html',
            url         : MyAjax.ajaxurl + "pokamodule_ajax",
            data        : {
                "page"        : "pokamodule-settings",
                "task"        : "ajax-manager-abc",
                "poka-type"   : "ajax",
                "security"    : MyAjax.security_code
            },
            beforeSend: function() {
            },
            success: function (data){
                // console.log(data);
                //$html = JSON.parse(data);
            }
        });
    });
});