<?php

use Amarkal\Extensions\WordPress\Options;

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
    'footer_text'   => '2014 © Askupa Software',
    'subfooter_text'=> '',
    'sections'      => array(
        new Options\Section(array(
            'title'         => 'General',
            'description'   => 'General settings',
            'icon'          => 'fa-gear',
            'fields'        => array(
                new Options\UI\Switcher(array(
                    'name'          => 'line_numbers',
                    'title'         => 'Line Numbers',
                    'default'       => 'OFF',
                    'help'          => 'Enabling this option will show line numbers on all code segments.'
                )),
                new Options\UI\MultiField(array(
                    'name'          => 'meta',
                    'title'         => 'Meta Header',
                    'fields'        => array(
                        new Options\UI\Switcher(array(
                            'name'          => 'show_meta',
                            'title'         => 'Show Meta',
                            'default'       => 'ON',
                            'help'          => 'Display a header with meta information and controls.'
                        )),
                        new Options\UI\Spinner(array(
                            'name'          => 'min_lines',
                            'title'         => 'Minimum Lines',
                            'min'           => 1,
                            'step'          => 1,
                            'default'       => 1,
                            'help'          => 'Code segments with less lines than specified here will not show a meta header.'
                        ))
                    )
                )),
                new Options\UI\Switcher(array(
                    'name'          => 'auto_assign',
                    'title'         => 'Auto Assign',
                    'labels'        => array('PRE', 'CODE', 'XHR'),
                    'multivalue'    => true,
                    'help'          => 'Choose the tags you want Mivhak to automatically prettify.'
                ))
            )
        )),
        new Options\Section(array(
            'title'         => 'Skins',
            'icon'          => 'fa-paint-brush',
            'description'   => 'Choose a skin for Mivhak',
            'fields'        => array(
                new Options\UI\DropDown(array(
                    'name'          => 'skin',
                    'title'         => 'Skin',
                    'help'          => 'Choose the skin that best matches your theme\'s style.',
                    'default'       => 'default.css',
                    'options'       => array(
                        'Default'           => 'default.css',
                        'Desert'            => 'desert.css',
                        'Doxy'              => 'doxy.css',
                        'Github'            => 'github.css',
                        'Hemisu (dark)'     => 'hemisu-dark.css',
                        'Hemisu (light)'    => 'hemisu-light.css',
                        'Mivhak'            => 'mivhak.css',

                    )
                )),
                new Options\UI\Content(array(
                    'title'         => 'Preview',
                    'full_width'    => true,
                    'template'      => Mivhak\PLUGIN_DIR . '/preview.phtml'
                ))
            )
        ))
    )
);