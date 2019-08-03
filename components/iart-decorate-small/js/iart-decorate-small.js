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

    $('#' + sPage + ' #_wpnonce').remove();
    $('#' + sPage + ' > input:hidden').remove();
    $(".tablenav.bottom .alignleft.actions.bulkactions").remove();

    //====================Start Edit Now=================
    //Edit 1 tr
    $("body").on( "click", ".editinline", function(event){
        event.preventDefault();

        $(".quick-edit").remove();
        $("#the-list > tr" ).each(function(i) {
            $(this).show();
        });

        var vID  = $(this).attr("edit-now");
        var vEditID = vID.split('-');
        vEditID = vEditID[1];

        $("#" + vID).hide();
        $.ajax({
            type        : 'post',
            dataType    : 'html',
            url         : MyAjax.ajaxurl + "pokamodule_ajax",
            beforeSend: function(){
                var nColspan = 6;
                $("#" + vID).after('<tr id="edit-'+vEditID+'"><td colspan="'+nColspan+'" class="colspanchange"><div class="tbl-loadding"><span></span>Please wait...</div></td></tr>');
            },
            data        : {
                "page"        : sPage,
                "task"        : "ajax-table-editinline",
                "poka-type"   : "ajax",
                "row-edit"    : vEditID,
                "security"    : MyAjax.security_code
            },
            success: function (data){
                $("#edit-" + vEditID).remove();
                $("#" + vID).after(data);
            }
        });
    });

    //Clone 1 tr
    $("body").on( "click", ".btn-clone", function(event){
        $("tr.quick-edit").fadeOut( "slow", function() {
            $(this).remove();

            $("#the-list > tr" ).each(function(i) {
                $(this).show();
            });
        });
    });

    //Update 1 tr
    $("body").on( "click", ".btn-update", function(event){
        vEditID = $(this).attr("update-now");

        var vData = {};
        $(".poka-row .item input").each(function(i){
            var vKey = $(this).attr('name');
            var vValue = $(this).val();
            vData[vKey] = vValue;
        });

        $.ajax({
            type        : 'post',
            dataType    : 'json',
            url         : MyAjax.ajaxurl + "pokamodule_ajax",
            beforeSend: function(){
            },
            data        : {
                "page"        : sPage,
                "task"        : "ajax-table-update",
                "poka-type"   : "ajax",
                "row-edit"    : vEditID,
                "row-data"    : vData,
                "security"    : MyAjax.security_code
            },
            success: function (data){
                if(data.type == 'error'){
                    $('.quick-edit .msg').html('<p>'+data.value+'</p>');
                }else{
                    $('#item-' + vEditID + ' .poka_name').html(data.value.poka_name);

                    $("tr.quick-edit").fadeOut( "slow", function() {
                        $(this).remove();

                        $("#the-list > tr" ).each(function(i) {
                            $(this).show();
                        });
                    });
                }
            }
        });
    });
    //========================End Edit Now=================

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