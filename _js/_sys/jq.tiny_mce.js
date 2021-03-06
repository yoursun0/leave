;if (window.jQuery) 
    (function($){
        $.extend($, {
            mce: {
                // General options
                mode: "exact",
                theme: "advanced",
                plugins: "safari,style,layer,table,advhr,advimage,advlink,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,inlinepopups",
                //plugins: "safari,pagebreak,style,layer,table,save,autosave,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,|,fullscreen",
                
                // Theme options
                theme_advanced_buttons1: "newdocument,|,print,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect,|,fullscreen",
                theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
                theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,iespell,media,advhr,|,ltr,rtl",
                //theme_advanced_buttons4: "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
                theme_advanced_toolbar_location: "top",
                theme_advanced_toolbar_align: "left",
                theme_advanced_statusbar_location: "bottom",
                theme_advanced_resizing: true,
                
                // Example word content CSS (should be your site CSS) this one removes paragraph margins
                //content_css: "_css/basic.css",
                
                // Drop lists for link/image/media/template dialogs
                template_external_list_url: "lists/template_list.js",
                external_link_list_url: "lists/link_list.js",
                external_image_list_url: "lists/image_list.js",
                media_external_list_url: "lists/media_list.js",
                
                create: function(option){
                
                },     
                remove: function(){
                    try {
						tinyMCE.editors = {};
						tinyMCE.activeEditor = null;
                    } 
                    catch (err) {
                    }
                },                
                start: function(o){
                    tinyMCE.init($.extend(this,o));
                },
                update: function(){
                    tinyMCE.triggerSave();
                }                
            }
        
        });
        
        $.extend($.fn, {
            mce: function(o){
                $.mce.start($.extend(o, {
                    elements: this.attr("id")
                }));
            }
        });
    })(jQuery);

