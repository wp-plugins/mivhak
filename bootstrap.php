<?php
/**
 * Mivhak Syntax Highlighter
 *
 * A lightweight syntax highlighter for WordPress.
 *
 * @package   Mivhak Syntax Highlighter
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/mivhak
 * @copyright 2014 Askupa Software
 *
 * @wordpress-plugin
 * Plugin Name:     Mivhak Syntax Highlighter
 * Plugin URI:		http://products.askupasoftware.com/mivhak
 * Description:		A lightweight syntax highlighter for WordPress.
 * Version:			1.0.4
 * Author:			Askupa Software
 * Author URI:		http://www.askupasoftware.com
 * Text Domain:		mivhak
 * Domain Path:		/languages
 */

if( !function_exists('mivhak_bootstrap') )
{
    function mivhak_bootstrap()
    {
        
        $validator = require_once 'vendor/askupa-software/amarkal-framework/EnvironmentValidator.php';
        if ( $validator->is_valid( 'plugin' ) )
        { 
            require_once 'app/Mivhak.php';
        }
    }
    add_action( 'plugins_loaded', 'mivhak_bootstrap' );
}
