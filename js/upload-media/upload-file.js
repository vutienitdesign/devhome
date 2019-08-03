jQuery(document).ready(function($) {
    /*
     * Select/Upload image(s) event
     */
    jQuery('body').on('click', '.misha_upload_image_button', function(e){
        e.preventDefault();
            var type_file = jQuery(this).data("type-file");

            var button = jQuery(this),
                custom_uploader = wp.media({
            title: 'Insert image',
            library : {
                // uncomment the next line if you want to attach image to the current post
                // uploadedTo : wp.media.view.settings.post.id, 
                type : type_file,

            },
            button: {
                text: 'Use this image' // button label text
            },
            multiple: false // for multiple image selection set to true
        }).on('select', function() { // it also has "open" and "close" events 
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            
            if (type_file == "image") {
                // Show Images and Save Attachment ID
                jQuery(button)
                .removeClass('button')
                .html('<img class="true_pre_image" src="' + attachment.url + '" style="max-width:100%;display:block;" />')
                .next().val(attachment.id)
                .next().show();
            }
  
            if (type_file == "application/msword") {
                // Show Images and Save Attachment ID
                jQuery(button)
                .val(attachment.url)
                .next().val(attachment.id)
                .next().show();
            }
            
            
            /* if you want Show SRC Image and Save Attachment ID
              jQuery(button)
             .removeClass('button')
             .html('<input class="true_pre_src_image widefat" value="' + attachment.url + '" style="" />')
             .next().val(attachment.id)
             .next().show();
            */


            /* if you sen multiple to true, here is some code for getting the image IDs
            var attachments = frame.state().get('selection'),
                attachment_ids = new Array(),
                i = 0;
            attachments.each(function(attachment) {
                attachment_ids[i] = attachment['id'];
                console.log( attachment );
                i++;
            });
            */
        })
        .open();
    });

    /*
     * Remove image event
     */
    jQuery('body').on('click', '.misha_remove_image_button', function(){
        jQuery(this).hide().prev().val('').prev().addClass('button').html('Upload image');
        return false;
    });

    jQuery('body').on('click', '.misha_remove_file_button', function(){
        jQuery(this).hide().prev().val('').prev().val('');
        return false;
    });
});