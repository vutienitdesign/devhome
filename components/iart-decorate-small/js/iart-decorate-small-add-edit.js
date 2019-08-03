jQuery(document).ready(function($) {
    var sPage = getUrlParameter('page');

    $("body").on( "change", ".decorate-large", function(event) {
        event.preventDefault();

        $(".box-decorate-medium .fa-spin").show();

        var loadAjax = true;
        if(loadAjax == true){
            $.ajax({
                type        : 'post',
                dataType    : 'json',
                url         : MyAjax.ajaxurl + "pokamodule_ajax",
                data        : {
                    "page"        : sPage,
                    "task"        : "ajax-search-option",
                    "poka-type"   : "ajax",
                    "decorate_large"      : $(this).val(),
                    "security"    : MyAjax.security_code
                },
                beforeSend: function(){
                    loadAjax = false;
                },
                success: function (data){
                    loadAjax = true;
                    $(".box-decorate-medium .fa-spin").hide();
                    $(".decorate-medium").html(data.data);
                }
            });
        }
    });

    function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
    };
});