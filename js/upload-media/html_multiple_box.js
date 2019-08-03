jQuery(document).ready(function($) {
    jQuery('body').on('click', '.poka_upload_image_button', function(e){
        e.preventDefault();
        var gallery_name = jQuery(this).data("gallery-name");

        var button = jQuery(this),
            custom_uploader = wp.media({
                title: 'Upload/Add image',
                library : {
                    type : 'image'
                },
                button: {
                    text: 'Use this images'
                },
                multiple: true
            }).on('select', function() {
                var attachments = custom_uploader.state().get('selection');
                var list_attachment = "";
                var attachment_ids = "";
                var i = button.prev().find(".product_images .group-item").length;

                attachments.each(function(attachment) {
                    if (attachment.attributes.sizes.thumbnail !== undefined) {
                        attachment_url = attachment.attributes.sizes.thumbnail.url;
                    }else{
                        attachment_url = attachment.attributes.sizes.full.url;
                    }

                    list_attachment += '<li class="group-item ui-sortable-handle" data-attachment_id="'+ attachment.id +'">' +
                                            '<div class="item"><img src="'+ attachment_url +'" /></div>' +
                                            '<input type="hidden" value="'+attachment.id+'" name="'+ gallery_name +'['+i+'][id]">' +
                                            '<div class="item"><input type="text" name="'+ gallery_name +'['+i+'][url]" class="link_redirect" placeholder="Redirect ..."></div>' +
                                            '<span class="poka-btn-remove-image">x</span>' +
                                        '</li>';

                    i++;
                });
                var attachment_ids_before = button.prev().find("ul").next().val();
                button.prev().find("ul").append(list_attachment).next().val(attachment_ids_before+attachment_ids);
            })
                .open();
    });

    jQuery('body').on('click', '.poka-btn-remove-image', function(){
        jQuery(this).parent().remove();
    });

    if ( jQuery(".poka_images_container .sortable").length ) {
        jQuery( ".poka_images_container .sortable" ).sortable({
            placeholder: "ui-sortable-placeholder",
            update: function( event, ui ) {
                var attachment_ids_sort = "";

                jQuery(this).find(".ui-sortable-handle").each(function function_name(argument) {
                    attachment_ids_sort += jQuery(this).data("attachment_id")+",";
                });

                jQuery(this).next().val(attachment_ids_sort);
            }
        });
    }
});