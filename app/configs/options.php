<?php

use Amarkal\Extensions\WordPress\Options;
use Amarkal\UI\Components;

return array(
    'banner'        => Mivhak\IMG_URL.'/banner.jpg',
    'title'         => 'Mivhak',
    'subtitle'      => 'A lightweight syntax highlighter for WordPress',
    'version'       => 'v'.Mivhak\PLUGIN_VERSION,
    'author'        => 'Askupa Software',
    'author_url'    => 'http://askupasoftware.com',
    'sidebar_title' => 'Mivhak',
    'sidebar_icon'  => 'dashicons-editor-code',
    'footer_icon'   => Mivhak\IMG_URL.'/askupa-logo.png',
    'footer_text'   => date("Y").' © Askupa Software',
    'subfooter_text'=> '',
    'sections'      => array(
        new Options\Section(array(
            'title'         => 'General',
            'description'   => 'General syntax highlighting settings.',
            'icon'          => 'fa-gear',
            'fields'        => array(
                new Components\ToggleButton(array(
                    'name'          => 'line_numbers',
                    'title'         => 'Line Numbers',
                    'default'       => 'OFF',
                    'help'          => 'Enabling this option will show line numbers on all code segments.'
                )),
                new Components\ToggleButton(array(
                    'name'          => 'show_meta',
                    'title'         => 'Show Meta Bar',
                    'default'       => 'ON',
                    'help'          => 'Display a header bar with meta information and controls.'
                )),
                new Components\Spinner(array(
                    'name'          => 'min_lines',
                    'title'         => 'Minimum Lines',
                    'min'           => 1,
                    'step'          => 1,
                    'default'       => 1,
                    'help'          => 'Code segments with less lines than specified here will not show a meta header bar.'
                )),
                new Components\ToggleButton(array(
                    'name'          => 'auto_assign',
                    'title'         => 'Auto Assign',
                    'labels'        => array('PRE', 'CODE', 'XHR'),
                    'multivalue'    => true,
                    'help'          => 'Choose the tags you want Mivhak to automatically prettify.'
                )),
                new Amarkal\UI\Components\DropDown(array(
                    'name'          => 'default_lang',
                    'title'         => 'Default Language',
                    'help'          => 'Choose a programming language that will be used by default for code blocks that have not specified a programming language.',
                    'default'       => '',
                    'options'       => array( '' => 'None' ) + include('langs.php')
                ))
            )
        )),
        new Options\Section(array(
            'title'         => 'Appearance',
            'icon'          => 'fa-paint-brush',
            'description'   => 'Setup the look and feel of Mivhak',
            'fields'        => array(
                new Components\ToggleButton(array(
                    'name'          => 'css_toggle',
                    'title'         => 'Use Custom CSS',
                    'help'          => 'Toggle on/off to use the custom CSS on the next field',
                    'default'       => 'OFF'
                )),
                new Components\CodeEditor(array(
                    'name'          => 'css',
                    'title'         => 'Custom CSS Code',
                    'help'          => 'Insert your custom CSS here',
                    'language'      => 'css',
                    'default'       => "/**\n * Insert your custom CSS here\n */"
                )),
                new Components\DropDown(array(
                    'name'          => 'theme',
                    'title'         => 'Theme',
                    'help'          => 'Choose a highlighter theme that best matches your blog\'s theme style.',
                    'default'       => 'default.css',
                    'options'       => include __DIR__.'/themes.php'
                )),
                new Components\Content(array(
                    'title'         => 'Preview',
                    'full_width'    => true,
                    'template'      => Mivhak\PLUGIN_DIR . '/preview.phtml'
                ))
            )
        ))
    )
);