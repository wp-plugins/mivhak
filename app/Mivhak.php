<?php
/**
 * @package   Mivhak Syntax Highlighter
 * @date      Sun May 31 2015 00:45:57
 * @version   1.2.5
 * @author    Askupa Software <contact@askupasoftware.com>
 * @link      http://products.askupasoftware.com/mivhak
 * @copyright 2014 Askupa Software
 */

namespace Mivhak;

use Amarkal\Extensions\WordPress\Plugin;
use Amarkal\Extensions\WordPress\Editor;
use Amarkal\Extensions\WordPress\Options;
use Amarkal\Loaders;

class Mivhak extends Plugin\AbstractPlugin 
{    
    private static $options;
    
    public function __construct() 
    {
        parent::__construct( dirname( __DIR__ ).'/bootstrap.php' );
        
        $this->generate_defines();

        // Register an options page
        self::$options = new Options\OptionsPage( include('configs/options.php') );
        self::$options->register();
        
        // Add a popup button to the rich editor
        Editor\Editor::add_button( include('configs/editor.php') );
        
        $this->register_assets();
        $this->add_filters();
    }
    
    public function generate_defines()
    {
        $basepath = dirname( __FILE__ );
        define( __NAMESPACE__.'\PLUGIN_DIR', $basepath );
        define( __NAMESPACE__.'\PLUGIN_URL', plugin_dir_url( $basepath ) );
        define( __NAMESPACE__.'\JS_URL', plugin_dir_url( $basepath ).'app/assets/js' );
        define( __NAMESPACE__.'\CSS_URL', plugin_dir_url( $basepath ).'app/assets/css' );
        define( __NAMESPACE__.'\IMG_URL', plugin_dir_url( $basepath ).'app/assets/img' );
        define( __NAMESPACE__.'\PLUGIN_VERSION', '1.2.5' );
    }
    
    public function reg_namespace()
    {
        $loader = new Loaders\ClassLoader();
        $loader->register_namespace( 'Mivhak', PLUGIN_DIR );
        $loader->register();
    }
    
    public function register_assets()
    {
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
                            'line_numbers'  => self::$options->get('line_numbers') == 'ON' ? true : false,
                            'auto_assign'   => self::$options->get('auto_assign'),
                            'show_meta'     => self::$options->get('show_meta') == 'ON' ? true : false,
                            'min_lines'     => self::$options->get('min_lines'),
                            'default_lang'  => self::$options->get('default_lang'),
                            'theme'         => self::$options->get('theme'),
                            'lang_list'     => include(__DIR__.'/configs/langs.php')
                        )
                    )
                )),
                new \Amarkal\Assets\Stylesheet(array(
                    'handle'        => 'mivhak-style',
                    'url'           => CSS_URL.'/mivhak.min.css',
                    'facing'        => array( 'public' ),
                    'version'       => PLUGIN_VERSION
                )),
                new \Amarkal\Assets\Script(array(
                    'handle'        => 'ace-editor',
                    'url'           => 'https://cdnjs.cloudflare.com/ajax/libs/ace/1.1.9/ace.js',
                    'facing'        => array( 'public' ),
                    'dependencies'  => array('jquery'),
                    'version'       => '1.1.9'
                ))
            )
        );
        $al->enqueue();
        
        // Custom CSS
        add_action( 'wp_head', array( __CLASS__, 'custom_css' ) );
    }
    
    private function add_filters()
    {
        add_filter( 'the_content', array( __CLASS__, 'format' ), 0 );
        add_filter( 'comment_text', array( __CLASS__, 'format' ), 0 );
        add_filter( 'bbp_get_topic_content', array( __CLASS__, 'format' ), 0 );
        add_filter( 'bbp_get_reply_content', array( __CLASS__, 'format' ), 0 );
        
        add_filter( 'tiny_mce_before_init', array( __CLASS__, 'format_TinyMCE' ), 0 );
        add_filter( 'mce_css', array( __CLASS__, 'editor_css' ) );
        add_action( 'admin_print_footer_scripts', array( __CLASS__, 'add_quicktags' ) );
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
            return $matches[1].esc_html(trim($matches[2])).$matches[3];
        }, $content);
    }
    
    /**
     * Remove the autop filter from TinyMCE editor. This allows
     * writing HTML code in the visual editor (prevents the removal of p tags etc)
     * 
     * @see http://codex.wordpress.org/TinyMCE
     * @see https://www.leighton.com/blog/stop-tinymce-in-wordpress-3-x-messing-up-your-html-code
     */
    static function format_TinyMCE( $settings ) 
    {
        $opts = '*[*]';
        $settings['valid_elements'] = $opts;
	$settings['extended_valid_elements'] = $opts;
        $settings['paste_word_valid_elements'] = $opts;
        $settings['wpautop'] = false;
        $settings['remove_linebreaks'] = false;
        $settings['apply_source_formatting'] = false;
        $settings["forced_root_block"] = false;
        $settings["force_br_newlines"] = true;
        $settings["force_p_newlines"] = true;
        $settings["convert_newlines_to_brs"] = true;
	
        return $settings;
    }
    
    static function editor_css( $wp ) 
    {
        $wp .= ',' . CSS_URL.'/editor.min.css';
        return $wp;
    }
    
    static function custom_css()
    {
        if( 'ON' == self::$options->get('css_toggle') )
        {
            $css = self::$options->get('css');
            echo "<style>$css</style>";
        }
    }
    
    public static function uninstall( $network_wide ) 
    {
        parent::uninstall($network_wide);
        self::$options->uninstall();
    }
    
    /**
     * Add Custom QuickTags to the HTML editor
     * @see http://www.wpexplorer.com/adding-wordpress-custom-quicktags/
     */
    public static function add_quicktags() 
    { ?>
        <script>if( typeof QTags !== 'undefined' ){QTags.addButton( 'pre', 'pre', '<pre>', '</pre>\n', 'p', '', 105 );}</script>
    <?php }
}
new Mivhak();