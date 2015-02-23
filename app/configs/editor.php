<?php

use Amarkal\UI;

return new Amarkal\Extensions\WordPress\Editor\Plugin(array(
    'slug'      => 'mivhak_button',
    'row'       => 1,
    'script'    => Mivhak\JS_URL.'/editor.js',
    'callback'  => new Amarkal\Extensions\WordPress\Editor\FormCallback(array(
        new Amarkal\UI\Components\DropDown(array(
            'name'          => 'lang',
            'title'         => 'Language',
            'description'   => 'Choose the programming language.',
            'default'       => 'javascript',
            'options'       => include('langs.php')
        )),
        new UI\Components\ToggleButton(array(
            'name'          => 'tag',
            'title'         => 'Display Type',
            'description'   => 'Choose between displaying the code inlined with the text, or in its own block.',
            'default'       => 'pre',
            'labels'        => array(
                'code'      => 'Inline',
                'pre'       => 'Block' 
            )
        )),
        new UI\Components\CodeEditor(array(
            'name'      => 'code',
            'title'     => 'Code',
            'language'  => 'javascript',
            'default'   => "/**\n * Insert your code here\n */",
            'full'      => true
        ))
    ))
));
