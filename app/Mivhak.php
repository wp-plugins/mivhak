<?php
/**
 * @package   Mivhak Syntax Highlighter
 * @date      Tue Jan 13 2015 20:18:27
 * @version   1.0.6
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/mivhak
 * @copyright 2014 Askupa Software
 */

namespace Mivhak;

use Amarkal\Extensions\WordPress\Plugin;
use Amarkal\Loaders;
use Amarkal\Extensions\WordPress\Options;

class Mivhak extends Plugin\AbstractPlugin {
    
    public function __construct() 
    {
        parent::__construct( PLUGIN_DIR, new Plugin\PluginSetup() );
     
        $this->generate_defines();
        
        // Register an options page
        $this->options = new Options\OptionsPage( include('configs/OptionsConfig.php') );
        $this->options->register();
        
        $this->register_assets();
        
        add_filter( 'the_content', array( __CLASS__, 'format' ), 0 );
        add_filter( 'comment_text', array( __CLASS__, 'format' ), 0 );
    }
    
    public function generate_defines()
    {
        $basepath = dirname( __FILE__ );
        define( __NAMESPACE__.'\PLUGIN_DIR', $basepath );
        define( __NAMESPACE__.'\PLUGIN_URL', plugin_dir_url( $basepath ) );
        define( __NAMESPACE__.'\JS_URL', plugin_dir_url( $basepath ).'app/assets/js' );
        define( __NAMESPACE__.'\CSS_URL', plugin_dir_url( $basepath ).'app/assets/css' );
        define( __NAMESPACE__.'\IMG_URL', plugin_dir_url( $basepath ).'app/assets/img' );
        define( __NAMESPACE__.'\PLUGIN_VERSION', '1.0.6' );
    }
    
    public function reg_namespace()
    {
        $loader = new Loaders\ClassLoader();
        $loader->register_namespace( 'Mivhak', PLUGIN_DIR );
        $loader->register();
    }
    
    public function register_assets()
    {
        global $mivhak_options;
        $al = new Loaders\AssetLoader();
        $al->register_assets(array(
                new \Amarkal\Assets\Script(array(
                    'handle'        => 'mivhak-script',
                    'url'           => JS_URL.'/mivhak.min.js',
                    'facing'        => array( 'public' ),
                    'version'       => PLUGIN_VERSION,
                    'dependencies'  => array('jquery'),
                    'footer'        => true,
                    'localize'      => array(
                        'name'      => 'mivhak_settings',
                        'data'      => array(
                            'line_numbers'  => $mivhak_options['line_numbers'] == 'ON' ? true : false,
                            'auto_assign'   => $mivhak_options['auto_assign'],
                            'show_meta'     => $mivhak_options['meta']['show_meta'] == 'ON' ? true : false,
                            'min_lines'     => $mivhak_options['meta']['min_lines']
                        )
                    )
                )),
                new \Amarkal\Assets\Stylesheet(array(
                    'handle'        => 'mivhak-style',
                    'url'           => CSS_URL.'/mivhak.min.css',
                    'facing'        => array( 'public' ),
                    'version'       => PLUGIN_VERSION
                )),
                new \Amarkal\Assets\Stylesheet(array(
                    'handle'        => 'mivhak-skin',
                    'url'           => CSS_URL.'/skins/'.$mivhak_options['skin'],
                    'facing'        => array( 'public' ),
                    'version'       => PLUGIN_VERSION
                ))
            )
        );
        $al->enqueue();
    }
    
    /**
     * Encodes HTML entities inside code segments.
     * 
     * @param type $content content from the_content()
     */
    static function format( $content )
    {
        $tags = array('code','pre','xhr');
        $pattern = array(); 

        foreach( $tags as $tag )
        {
            $pattern[] = "/(<".$tag."[^>]*>)(.*?)(<\/".$tag.">)/s";
        }

        // otherwise returns the database content
        return preg_replace_callback($pattern, function($matches){
            return $matches[1].esc_html($matches[2]).$matches[3];
        }, $content);
    }
}
new Mivhak();