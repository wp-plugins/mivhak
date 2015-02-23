(function($) {
    
    var config = {
        slug:       'mivhak_button',
        title:      'Insert code',
        width:      800,
        height:     450,
        template:   '<<% tag %> class="prettyprint lang-<% lang %>"><% code %></<% tag %>>',
        icon:       'fa fa-code',
        text:       null,
        selector:   ['code', 'pre']
    };
    
    // Add the button to the editor
    tinymce.PluginManager.add( config.slug, function( editor, url ) {
        
        editor.addButton( config.slug, { 
            text: config.text, 
            icon: config.icon, 
            title: config.title,
            stateSelector: config.selector,
            onclick: function() {
                // Open a new ajax popup form window
                Amarkal.Editor.Form.open( editor, {
                    title: config.title,
                    url: ajaxurl + '?action=' + config.slug,
                    width: config.width,
                    height: config.height,
                    template: config.template,
                    on_init: on_init
                });
            }
        });
        
        //replace from shortcode to an image placeholder
        editor.on('BeforeSetcontent', function(event)
        {
            event.content = visualEditorFormat( event.content );
        });

        //replace from image placeholder to shortcode
        editor.on('GetContent', function(event)
        {
            event.content = htmlEditorFormat( event.content );
        });
        
        // Fix code on undo/redo
        editor.on('undo redo', function(event) 
        {
            editor.setContent( undoFix( event.level.content ) );
        });

        /**
         * Since calling undo/redo also calls 'BeforeSetContent' event (and 
         * the visualEditorFormat() function), <br> elements will become parsed 
         * inside pre/code elements. This function will convert <br> elements
         * back into \n
         * 
         * @param {type} content
         */
        function undoFix( content ) 
        {
            return content.replace( /<(pre|code)([^>]*)>([\s\S]*?)<\/\1>/g, function( match, tag, atts, content) {
                content = content.replace(/(<br ?\/?>)/g, "\n");
                return '<'+tag+atts+'>'+entityEncode( content )+'</'+tag+'>';
            });
        }

        /**
         * Fix the format of code blocks in the visual editor.
         * 
         * @param {type} content
         */
        function visualEditorFormat( content ) 
        {
            return content.replace( /<(pre|code)([^>]*)>([\s\S]*?)<\/\1>/g, function( match, tag, atts, content) {
                content = '<'+tag+atts+'>'+entityEncode( content )+'</'+tag+'>';
                return content.replace(/([\n\r])/g,"<br />");
            });
        }

        /**
         * Return the original format of code blocks in the html editor.
         * 
         * @param {type} content
         */
        function htmlEditorFormat( content ) 
        {
            content = content.replace(/(<br ?\/?>)/g, "\n");
            return content.replace( /<(pre|code)([^>]*)>([\s\S]*?)<\/\1>/g, function( match, tag, atts, content) {
                return '<'+tag+atts+'>'+entityDecode( content )+'</'+tag+'>';
            });
        }
        
        /**
         * Encode HTML entities for the given text.
         * 
         * @param {type} content
         */
        function entityEncode( content )
        {
            return Amarkal.Utility.arrayReplace([
                //[/&/g,"&amp;"], // This must be the first replacement, or it will replace the html entities as well.
                [/</g,"&lt;"],
                [/>/g,"&gt;"],
                [/"/g,"&quot;"],
                [/ /g,"&nbsp;"]
            ], content);
        }
        
        /**
         * Decode HTML entities for the given text.
         * 
         * @param {type} content
         */
        function entityDecode( content )
        {
            return Amarkal.Utility.arrayReplace([
                [/(&lt;)/g, "<"],
                [/(&gt;)/g, ">"],
                [/(&quot;)/g, "\""],
                [/(&nbsp;)/g, " "],
                [/(&amp;)/g, "&"]
            ], content);
        }
        
        /**
         * Create a floating toolbar for the code blocks in the visual editor.
         * This toolbar is shown once the element is clicked.
         */
        new Amarkal.Editor.FloatingToolbar( editor, {
            buttons: [
                {
                    slug: 'mivhak_edit',
                    icon: 'dashicon dashicons-edit',
                    tooltip: 'Edit',
                    onclick: function() { editCode( editor.selection.getNode() ) }
                },
                {
                    slug: 'mivhak_remove',
                    icon: 'dashicon dashicons-no',
                    tooltip: 'Remove',
                    onclick: function() { removeCode( editor.selection.getNode() ) }
                }
            ],
            selector: 'pre, code, xhr'
        });
        
        /**
         * Edit a code block by a given node.
         * 
         * @param {type} node The code block's HTML node
         */
        function editCode( node )
        {
            var code = entityDecode( node.innerHTML.replace(/(<br ?\/?>)/g, "\n") );
            // Open a new ajax popup form window
            Amarkal.Editor.Form.open( editor, {
                title: config.title,
                url: ajaxurl + '?action=' + config.slug,
                width: config.width,
                height: config.height,
                template: config.template,
                on_init: on_init,
                on_insert: function( editor, values ) {
                    var args = editor.windowManager.getParams();
                    editor.dom.remove( editor.selection.getNode(), false );
                    editor.insertContent( Amarkal.Editor.parseTemplate( args.template, values ) );
                },
                values: {
                    lang: node.attributes['class'].value.match(/lang-([^ ]+)/)[1],
                    tag: node.nodeName.toLowerCase(),
                    code: code
                }
            });
            return;
        }
        
        /**
         * Remove a code block from the tinyMCE editor.
         * 
         * @param {type} node
         */
        function removeCode( node ) 
        {
            if ( node ) {
                if ( node.nextSibling ) {
                    editor.selection.select( node.nextSibling );
                } else if ( node.previousSibling ) {
                    editor.selection.select( node.previousSibling );
                } else {
                    editor.selection.select( node.parentNode );
                }

                editor.selection.collapse( true );
                editor.dom.remove( node );
            } else {
                editor.dom.remove( node );
            }

            editor.nodeChanged();
            editor.undoManager.add();
        }
        
        /**
         * Called once the popup has been loaded.
         * 
         * @param {type} window
         */
        function on_init( window )
        {
            var editor = $(window.document).find('[data-name="code"]')[0],
                dropdown = $(window.document).find('[data-name="lang"]')[0],
                dropdownui = window.Amarkal.UI.getComponent( dropdown ).getInput( dropdown );

            // Change the language initially and on dropdown value change
            window.Amarkal.UI.getComponent( editor ).setMode( editor, dropdownui.select2('val'));
            dropdownui.select2({width:'resolve'}).on("change", function(e) {
                window.Amarkal.UI.getComponent( editor ).setMode( editor, e.val);
            });
        }
    });
})(jQuery);