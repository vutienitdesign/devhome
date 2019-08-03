tinymce.PluginManager.add('pokatinymce', function( editor, url ) {
    //poka_shortcodes_highlight
    editor.addButton('poka_shortcodes_highlight', {
        title : 'Highlight Text',
        image: tinyMCE_object.image_download,
        cmd   : 'highlight'
    });

    editor.addCommand('highlight', function() {
        var markOpen  = '<mark>',
            markClose = '</mark>',
            highlight = markOpen + editor.selection.getContent() + markClose;

        editor.focus();

        if( -1 < editor.getContent().indexOf( highlight ) ) {
            editor.setContent(
                editor.getContent().replace( highlight, editor.selection.getContent() )
            );

        } else {
            editor.selection.setContent(
                markOpen + editor.selection.getContent() + markClose
            );
        }
    });

    //poka_shortcodes_module
    editor.addButton('poka_shortcodes_module', {
        title: 'Shortcodes Module',
        image: tinyMCE_object.image_download,
        onclick: popupShortcodesModule
    });

    //poka_list_product
    editor.addButton('poka_list_product', {
        title: 'List Products',
        image: tinyMCE_object.image_download,
        onclick: function (e) {
            editor.windowManager.open( {
                title: 'Shortcode sản phẩm',
                body: [
                    {
                        type   : 'textbox',
                        name   : 'limited',
                        label  : 'Limited (Hiển thị giới hạn sản phẩm)',
                        value  : 4,
                    },{
                        type   : 'textbox',
                        name   : 'column',
                        label  : 'Hiển thị: (Số sản phẩm hiển thị trên 1 hàng)',
                        tooltip: 'Số sản phẩm hiển thị trên 1 hàng',
                        value  : 4,
                    }],
                onsubmit: function( e ) {
                    editor.insertContent( '[show_products columns="'+ e.data.column +'" limit="'+ e.data.limited +'"]');
                }
            });
        }
    });
    
    function popupShortcodesModule() {
        var listBox, win = editor.windowManager.open({
            title: 'Chèn Shortcodes Module',
            resizable : true,
            maximizable : true,
            width: 800,
            height: 400,
            id: 'poka-tinymce-module-shortcode',
            body: [
                {
                    type   : 'textbox',
                    name   : 'textbox',
                    label  : 'Tên Module',
                    tooltip: 'Tìm kiếm tên module',
                    value  : '',
                },
                {
                    type   : 'button',
                    name   : 'button',
                    label  : '',
                    text   : 'Search',
                    onclick: function() {
                        //https://jsfiddle.net/aeutaoLf/2/

                        var loadAjax = true;
                        $.ajax({
                            type        : 'post',
                            dataType    : 'html',
                            url         : MyAjax.ajaxurl + "pokamodule_ajax",
                            data        : {
                                "page"        : "pokamodule-shortcode",
                                "task"        : "ajax-get-module",
                                "poka-type"   : "ajax",
                                "name"      : $("#poka-tinymce-module-shortcode .mce-textbox.mce-abs-layout-item.mce-last").val(),
                                "security"    : MyAjax.security_code
                            },
                            beforeSend: function(){
                                loadAjax = false;
                            },
                            success: function (data){
                                loadAjax = true;
                                $("#poka-tinymce-module-shortcode .mce-container-body.mce-flow-layout").html(data);
                            }
                        });
                    }
                },
                {
                    type: 'container',
                    label  : '',
                    layout: 'flow',
                    minWidth: 160,
                    minHeight: 160,
                    items: [
                        {type: 'label', text: ''},
                    ]
                },
            ],
            onsubmit: function( e ) {
                $(".poka-msc-search .poka-checkbox").each(function(index, value){
                    var dataType = $(this).attr("data-type");

                    var sHtml = '';
                    if($(this).is(':checked')){
                        sHtml += '[Module id="'+$(this).val()+'"]';
                    }

                    editor.insertContent(
                        sHtml
                    );
                });

            }
        });
    };
});

