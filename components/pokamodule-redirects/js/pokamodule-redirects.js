jQuery(document).ready(function($) {

  jQuery("body").on('click', '.redirect_update.button-primary', function(event) {
    event.preventDefault();
    /* Act on the event */
    var self    = jQuery(this);
    var id      = self.data("id");

    var request     = jQuery("td.request-"+ id +" input").val();
    var destination = jQuery("td.destination-"+ id +" input").val();

    jQuery.ajax({
      url: MyAjax.ajaxurl + "pokamodule_ajax",
      type: 'post',
              data: {
                  page         : "pokamodule-redirects",
                  task         : "update-item",
                  request      : request,
                  destination  : destination,
                  id           : id,
              },
              
        success: function(data) {
          jQuery("td.request-"+ id +" p").text(request);
          jQuery("td.destination-"+ id +" p").text(destination);
          refreshAction();
        }
    });
  });


  // ----------------------
  jQuery("body").on('click', '.import_excel', function(event) {
    event.preventDefault();
    /* Act on the event */
    jQuery(this).removeClass('import_excel').addClass("add_a_redirect").val("Add a Redirect");
    jQuery(".import-data-redirects").show();
    jQuery(".add-a-redirect").hide();
  });

  // ----------------------
  jQuery("body").on('click', '.add_a_redirect', function(event) {
    event.preventDefault();
    /* Act on the event */
    jQuery(this).removeClass('add_a_redirect').addClass("import_excel").val("Import Excel");
    jQuery(".import-data-redirects").hide();
    jQuery(".add-a-redirect").show();
  });

  jQuery("body").on('click', '.open-document', function(event) {
    event.preventDefault();
    /* Act on the event */
    jQuery(".documentation").toggle();
  });

  // ------------------------------------------------------------------------------------
  jQuery("body").on('click', '.quick-edit', function(event) {
      event.preventDefault();
      /* Act on the event */
      var id          = jQuery(this).data("id");
      var request     = jQuery("tr#item-"+id+" .request strong").text();
      var destination = jQuery("tr#item-"+id+" .destination").text();
      jQuery.ajax({
        url: MyAjax.ajaxurl + "pokamodule_ajax",
        type: 'post',
                data: {
                    page        : "pokamodule-redirects",
                    task        : "quick-edit",
                    id          : id,
                    request     : request,
                    destination : destination,
                },
                
          success: function(data) {
            // console.log(id);
            jQuery("tr#item-"+id).hide().after(data);
          }
      });
     
     
  });

  jQuery("body").on('click', '.cancel', function(event) {
    event.preventDefault();
    /* Act on the event */
     var id    = jQuery(this).data("id");
     jQuery("tr#edit-"+id).remove();
     jQuery("tr#item-"+id).show();
  });

  jQuery("body").on('click', '.save', function(event) {
    event.preventDefault();
    /* Act on the event */
    var self  = jQuery(this);
    var id    = jQuery(this).data("id");

    jQuery.ajax({
          url: MyAjax.ajaxurl + "pokamodule_ajax",
          dataType : 'json',
          type: 'post',
              data: {
                page      : "pokamodule-redirects",
                task      : "save-edit",
                fdata     : jQuery("#form-"+id).serialize()
                
            },
          success: function(data){
            console.log(data);
            if (!data.result) {
              alert("An error occurred. Please try again later!");
            }else{
              jQuery("tr#item-"+id+" .request strong").text(data.request);
              jQuery("tr#item-"+id+" .destination").text(data.destination);
              jQuery("tr#item-"+id+" .latest_update").text(data.latest_update);

              jQuery("tr#edit-"+id).remove();
              jQuery("tr#item-"+id).show();
            }
            
          }
      });
  });

});
