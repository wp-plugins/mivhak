(function($) {
    
    var config = {
        slug:       'mivhak_button',
        title:      'Insert code',
        width:      800,
        height:     450,
        template:   '<<% tag %> class="prettyprint lang-<% lang %>"><% code %></<% tag %>>',
        template_no_lang:'<<% tag %> class="prettyprint"><% code %></<% tag %>>',
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
                var values = {},
                    selection = editor.selection.getContent();
                
                if( "" !== selection )
                {
                    values.code = selection;
                }
                
                
                // Open a new ajax popup form window
                Amarkal.Editor.Form.open( editor, {
                    title: config.title,
                    url: ajaxurl + '?action=' + config.slug,
                    width: config.width,
                    height: config.height,
                    template: config.template,
                    on_init: on_init,
                    on_insert: on_insert,
                    values: values
                });
            }
        });
        
        // HTML to visual editor
        editor.on('BeforeSetcontent', function(event)
        {
            event.content = visualEditorFormat( event.content );
        });

        // Visual to HTML editor
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
            return innerHTMLRegex( content, config.selector, function(c) {
                c = c.replace(/(<br ?\/?>)/g, "\n");
                return entityEncode( c );
            });
        }

        /**
         * Fix the format of code blocks in the visual editor.
         * 
         * @param {type} content
         */
        function visualEditorFormat( content ) 
        {
            content = innerHTMLRegex( content, config.selector, function(c) {
                return entityEncode( c );
            });
            
            // Replace one or more line breaks with <br> inside <p> and <pre>
            content = innerHTMLRegex( content, ['pre'], function(c) {
                return c.replace(/([\n]{1})/g, "<br />");
            });
            
            // Replace all new line characters outside <p> and <pre> with a 
            // placeholder. This is a hackish way of maintaining the HTML formatting 
            // when switching between the editors
            content = content.replace(/([\n]{1})/g, '<!-- newline -->');
            content = content.replace(/([\t]{1})/g, '<!-- tab -->');
            return content;
        }

        /**
         * Return the original format of code blocks in the html editor.
         * 
         * @param {type} content
         */
        function htmlEditorFormat( content ) 
        {
            content = content.replace(/(<br ?\/?>)/g, "\n");
            
            // Replace new line placeholders with the \n character
            content = content.replace(/(<!-- newline -->)/g, "\n");
            content = content.replace(/(<!-- tab -->)/g, "\t");
            
            return innerHTMLRegex( content, config.selector, function(c) {
                return entityDecode( c );
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
                [/(&amp;)/g, "&"]
            ], content);
        }
        
        /**
         * Replace the inner content of an element whose tags are
         * given.
         * 
         * @param {string} content The string 
         * @param {array} query HTML DOM query
         * @param {function} replacer a function to run on the inner HTML of the element
         * @returns string
         */
        function innerHTMLRegex( content, tags, replacer )
        {
            for( var i = 0; i < tags.length; i++ )
            {
                var tag = tags[i];
                var regex = new RegExp("<"+tag+"([^>]*)>([\\S\\s]*?)<\\/"+tag+">","g");
                content = content.replace( regex, function( match, atts, cont ) {
                    return '<'+tag+atts+'>'+replacer( cont )+'</'+tag+'>';
                });
            }
            return content;
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
            var lang = '';
            
            if( node.attributes.hasOwnProperty('class') )
            {
                var matches = node.attributes['class'].value.match(/lang-([^ ]+)/);
                if( null !== matches )
                {
                    lang = matches[1];
                }
            }
            
            // Open a new ajax popup form window
            Amarkal.Editor.Form.open( editor, {
                title: config.title,
                url: ajaxurl + '?action=' + config.slug,
                width: config.width,
                height: config.height,
                template: config.template,
                on_init: on_init,
                on_insert: on_insert,
                values: {
                    lang: lang,
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
        
        /**
         * Called once the "insert" button (in the popup window) is clicked.
         * This is used by both the 'edit' and the 'insert' buttons in the visual editor.
         * 
         * @param {type} editor
         * @param {type} values
         */
        function on_insert( editor, values ) {
            var args = editor.windowManager.getParams(),
                node = editor.selection.getNode(),
                template = args.template;
            
            // No language has been selected, use the appropriate template
            if( "" === values.lang )
            {
                template = config.template_no_lang
            }
            
            // Remove the selected node if the node is one of [pre|code|xhr]
            // Note that getNode() returns the common ancestor of the selection, 
            // which might be the entire document, therefore this test is neccessary.
            if( ["PRE","CODE","XHR"].indexOf(node.nodeName) >= 0 ) 
            {
                editor.dom.remove( node, false );
            }
            else
            {
                editor.dom.remove( editor.selection.getContent(), false );
            }
            
            editor.insertContent( Amarkal.Editor.Form.parseTemplate( template, values ) );
        }
    });
})(jQuery);