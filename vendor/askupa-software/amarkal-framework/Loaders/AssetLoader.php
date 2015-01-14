<?php

namespace Amarkal\Loaders;

/**
 * Implements a script and stylesheet loader
 * 
 * Use this class to easily enqueue assets (scripts and stylesheets) for your 
 * project. Using the AssetLoader, you can load scripts and stylesheets as a 
 * front-end (public) or admin facing asset. Scripts can also be localized using 
 * the AssetLoader.
 * 
 * @see \Amarkal\Assets\AbstractAsset::get_defaults for available parameters
 * 
 * Example Usage:
 * 
 * $al = new AssetLoader();
 * 
 * // Bulk asset registering
 * $al->register_assets(array(
 *        new \Amarkal\Assets\Stylesheet(array(
 *            'handle'    => 'myStyle',
 *            'url'       => 'http://www.website.com/path/to/style.css',
 *            'facing'    => array( 'public' )
 *        )),
 *        new \Amarkal\Assets\Script(array(
 *            'handle'    => 'myScript',
 *            'url'       => 'http://www.website.com/path/to/script.js',
 *            'facing'    => array( 'admin-edit.php' )
 *        ))
 * ));
 * 
 * // Single asset registering with localized data
 * $al->register_asset(
 *        new \Amarkal\Assets\Script(array(
 *            'handle'    => 'myScript',
 *            'url'       => 'http://www.website.com/path/to/script.js',
 *            'facing'    => array( 'public' ),
 *            'localize   => array(
 *                'name'    => 'myVar'                    // The name of the variable holding the data
 *                'data'    => array('One', 'Two', 'Three')    // The data to be stored in the array
 *            )
 *        ))
 * );
 * 
 * $al->enqueue();
 * 
 */
class AssetLoader {
    
    /**
     * The list of assets
     * @var mixed    Assets array 
     */
    private $assets;
    
    /**
     * Bulk registering of assets
     * 
     * @param mixed[] $assets The list of assets to enqueue
     */
    public function register_assets( array $assets ) {
        foreach ( $assets as $asset ) {
            $this->register_asset( $asset );
        }
    }
    
    /**
     * Register a single asset
     * 
     * @param \Amarkal\Assets\AbstractAsset $new_asset The asset to enqueue
     * @throws DuplicateAssetException    If the a duplicated asset handle exists
     */
    public function register_asset( \Amarkal\Assets\AbstractAsset $new_asset )
    {
        if( NULL == $this->assets ) {
            $this->assets = array();
            $this->assets[] = $new_asset;
        }
        else {
            foreach( $this->assets as $asset ) {
                if( $asset->handle == $new_asset->handle && get_class($asset) == get_class($new_asset) ) {
                    throw new DuplicateAssetException("An asset with the handle {$new_asset->handle} already exists");
                }
            }
            $this->assets[] = $new_asset;
        }
    }
    
    /**
     * Enqueues all registered assets.
     */
    public function enqueue()
    {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_assets' ) );
    }
    
    public function enqueue_admin_assets( $hook )
    {
        foreach ( $this->assets as $asset )
        {
            foreach( $asset->facing as $facing )
            {
                $specific_page = strstr( $facing, '-' );
                $facing = $specific_page ? strstr( $facing, '-', true ) : $facing;
                
                if( 'admin' == $facing )
                {
                    if( !$specific_page || ( $specific_page && $hook == substr( $specific_page, 1 ) ) )
                    {
                        $asset->register();
                        $asset->enqueue();
                    }
                }
            }
        }
    }
    
    public function enqueue_public_assets( $hook )
    {
        foreach( $this->assets as $asset )
        {
            foreach( $asset->facing as $facing )
            {
                if( 'public' == $facing )
                {
                    $asset->register();
                    $asset->enqueue();
                }
            }
        }
    }
}
