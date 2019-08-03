jQuery(document).ready(function($) {
    $('.dropdown-designed').dropdown({
        forceSelection: false,
    });

    if(sIDDesigned != ''){
        sIDDesigned = sIDDesigned.split(',');

        $.each(sIDDesigned, function(index, value){
            $('.dropdown-designed').dropdown('set selected', [value]);
        });
    }

    var sPage = getUrlParameter('page');

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