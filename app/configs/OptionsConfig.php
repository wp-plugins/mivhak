<?php

return array(
    'settings'      => array(
        'admin-title'   => 'Mivhak',
        'admin-icon'    => 'dashicons-editor-code'
    ),
    'header'        => array(
        'icon'          => Mivhak\IMG_URL.'/logo.png',
        'title'         => 'Mivhak',
        'subtitle'      => 'A lightweight syntax highlighter for WordPress',
        'version'       => 'v'.Mivhak\PLUGIN_VERSION
    ),
    'sections'      => array(
        new \Amarkal\Options\Section(array(
            'title'         => 'General',
            'description'   => 'General settings',
            'icon'          => 'fa-gear',
            'fields'        => array(
                new \Amarkal\Options\UI\Switcher(array(
                    'name'          => 'line_numbers',
                    'title'         => 'Line Numbers',
                    'default'       => 'OFF',
                    'help'          => 'Enabling this option will show line numbers on all code segments.'
                )),
                new \Amarkal\Options\UI\Switcher(array(
                    'name'          => 'show_meta',
                    'title'         => 'Show Meta',
                    'default'       => 'ON',
                    'help'          => 'Display a header with meta information and controls.'
                )),
                new \Amarkal\Options\UI\Switcher(array(
                    'name'          => 'auto_assign',
                    'title'         => 'Auto Assign',
                    'labels'        => array('PRE', 'CODE', 'XHR'),
                    'multivalue'    => true,
                    'help'          => 'Choose the tags you want Mivhak to automatically prettify.'
                ))
            )
        )),
        new \Amarkal\Options\Section(array(
            'title'         => 'Skins',
            'icon'          => 'fa-paint-brush',
            'description'   => 'Choose a skin for Mivhak',
            'fields'        => array(
                new \Amarkal\Options\UI\DropDown(array(
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
                new \Amarkal\Options\UI\Content(array(
                    'title'         => 'Preview',
                    'full_width'    => true,
                    'template'      => Mivhak\PLUGIN_DIR . '/preview.phtml'
                ))
            )
        ))
    ),
    'footer'        => array(
        'icon'          => Mivhak\IMG_URL.'/askupa-logo.png',
        'text'          => '2014 © Askupa Software'
    ),
    'subfooter'    => array(
        'text'          => ''
    )
);