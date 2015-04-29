(function($){
    
    // Extend jQuery functionality
    $.fn.extend({
        mivhak: function (settings) {
            return this.each(function () {
                new Mivhak( this, settings );
            });
        }
    });
    
    /**
     * Highlight code using the Ace Editor for all code elements
     * in the root node.
     * 
     * @see Mivhak.prototype.defaults
     * @param {Node} root
     * @param {Object} settings
     */
    function Mivhak( root, settings )
    {
        var self = this;
        this.config = $.extend({}, this.defaults(), settings );
        
        // Assign the class 'prettyprint' to all code segments
        if(this.config.auto_assign !== "")
        {
            $(root).find(this.config.auto_assign).each(function(){
                $(this).addClass('prettyprint');
            });
        }
        
        $('.prettyprint').each(function(){
            self.prettify(this);
        });
    }
    
    /**
     * Do syntax highlighting for the given element
     * 
     * @param {Node} el
     */
    Mivhak.prototype.prettify = function( el )
    {
        var config = this.config,
            lang = this.getLangs(el),
            tag = el.tagName,
            editor  = ace.edit(el);
        
        // Auto detect language
        if( lang.length === 0 )
        {
            lang = [config.default_lang];
        }

        // Hide line numbers for inline code elements
        if( tag == 'CODE' || !config.line_numbers )
        {
            editor.renderer.setShowGutter(false);
            // Prevent scrolling
            editor.getSession().on('changeScrollLeft', function(){
                editor.getSession().setScrollLeft(0);
            });
        }

        if( 'CODE' != tag )
        {
            $(el).wrap('<div class="ace-code-wrapper"></div>');
            if( config.show_meta && editor.session.getLength() >= config.min_lines )
            {
                $(el).before(this.buildHeader(el,editor));
            }
        }

        ace.config.set('basePath', 'https://cdnjs.cloudflare.com/ajax/libs/ace/1.1.8/');
        editor.setReadOnly(true);
        editor.setTheme("ace/theme/"+config.theme);
        editor.getSession().setMode("ace/mode/"+lang);
        editor.getSession().setUseWorker(false); // Disable syntax checking

        editor.setOptions({
            maxLines: Infinity
        });
    };
    
    /**
     * Default settings for Mivhak
     * @returns {Object}
     */
    Mivhak.prototype.defaults = function ()
    {
        return {
            auto_assign : false,    // comma delimited list of tag names ('tag1,tag2')
            show_meta   : false,
            line_numbers: false,
            min_lines   : 2,
            theme       : 'twilight',
            default_lang: 'text',
            lang_list   : {}
        };
    }
    
    /**
     * List of button settings for the header bar
     * 
     * @returns {Array}
     */
    Mivhak.prototype.buttons = function()
    {
        return [
            {
                description : 'Copy this code to clipboard',
                class       : 'mivhak-icon copy-icon',
                func        : function( editor, button ){
                    
                    if($(button).hasClass('active'))
                    {
                        editor.selection.clearSelection();
                    }
                    else
                    {
                        editor.selection.selectAll();
                        editor.focus();
                    }
                    $(button).toggleClass('active');
                }
            },
            {
                description : 'Open this code in a new window',
                class       : 'mivhak-icon expand-icon',
                func        : function( editor ){
                    var win = window.open('','','width=500,height=330,resizable=1');
                    editor.selection.selectAll();
                    var output = editor.session.getTextRange(editor.getSelectionRange());
                    win.document.write('<pre>'+$('<div/>').text(output).html()+'</pre>');
                    editor.selection.clearSelection();
                }
            },
            {
                description : 'Wrap lines',
                class       : 'mivhak-icon wrap-icon',
                func        : function( editor, button ){
                    if($(button).hasClass('active'))
                    {
                        editor.getSession().setUseWrapMode(false);
                    }
                    else
                    {
                        editor.getSession().setUseWrapMode(true);
                    }
                    $(button).toggleClass('active');
                }
            }
        ];
    };
    
    /**
     * Generate the meta header for the code snippet
     * 
     * @param {Node} wrapper
     * @returns {Node}
     */
    Mivhak.prototype.buildHeader = function( wrapper, editor )
    {
        var header  = document.createElement('div');
        var text    = document.createElement('div');
        var control = document.createElement('div');
        var lang    = document.createElement('div');
        var langs   = this.getLangs(wrapper);
        
        header.className    = "meta";
        text.className      = "text";
        control.className   = "control";
        lang.className      = "lang";
        
        if( langs.length !== 0 )
        {
            for( var i = 0; i < langs.length; i++ )
            {
                langs[i] = this.config.lang_list[langs[i]];
            }
            $(lang).html(langs.join(', '));
            $(header).append(lang);
        }
        
        $(control).append(this.generateButtons(editor));
        $(text).html(editor.session.getLength() + " Lines");
        $(header).append(text, control);
        
        return header;
    };
    
    /**
     * Generate buttons for the header bar.
     * 
     * @param {type} editor
     * @returns {Array} The generated buttons
     */
    Mivhak.prototype.generateButtons = function( editor )
    {
        var buttons = [];
        $.each( this.buttons(), function(){
            var button = document.createElement('div');
            var icon = document.createElement('i');
            var func = this.func;
            
            icon.className = this.class;
            button.title = this.description;
            button.appendChild(icon);
            $(button).click(function(){
                func(editor, button);
            });
            buttons.push(button);
        });
        return buttons;
    };
    
    /**
     * Get the programming languages specified for this current code segment.
     * 
     * Languages can be specified via css classes e.g. 'lang-html'
     * 
     * @param {type} o
     * @returns {undefined}
     */
    Mivhak.prototype.getLangs = function( el ) 
    {
        var lang_classes = el.className.match(/lang-([^ ])+/g);
        var langs = [];
        
        if( lang_classes === null ) return [];
        
        for( var i = 0; i < lang_classes.length; i++ )
        {
            langs.push(lang_classes[i].split('-')[1]);
        }
        return langs;
    };
    
    // Run!
    $(document).mivhak(mivhak_settings);
}(window.jQuery));