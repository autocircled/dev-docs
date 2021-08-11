<?php
/**
 * Plugin Name: Dev Docs - Theme & Plugin Documentation
 * Plugin URI: https://devhelp.us/dev-docs
 * Description: 
 * Version: 1.0.0
 * Author: autocircle
 * Author URI: https://devhelp.us/
 * Text Domain: dev-docs
 * Domain Path: /languages/
 *
 * @author autocircle
 * @package DevDocs
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Do not open this file directly.' );
}

if ( !function_exists('is_plugin_active') ){
    /**
    * Including Plugin file for security
    * Include_once
    * 
    * @since 1.0.0
    */
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if( ! defined( 'DEVDOCS_CAPABILITY' ) ){
    
    $DEVDOCS_capability = apply_filters( 'dev_docs_menu_capability', 'manage_options' );
    
    define( 'DEVDOCS_CAPABILITY', $DEVDOCS_capability );
    
}

if ( ! defined( 'DEVDOCS_NAME' ) ) {
    
    define( 'DEVDOCS_NAME', esc_html__( 'Dev Docs - Theme & Plugin Documentation', 'dev-docs' ));
    
}

if ( ! defined( 'DEVDOCS_BASE_NAME' ) ) {
    
    define( 'DEVDOCS_BASE_NAME', plugin_basename( __FILE__ ) );
    
}

if ( ! defined( 'DEVDOCS_BASE_DIR' ) ) {
    
    define( 'DEVDOCS_BASE_DIR', plugin_dir_path( __FILE__ ) );
    
}

if ( ! defined( 'DEVDOCS_BASE_URL' ) ) {
    
    define( 'DEVDOCS_BASE_URL', plugins_url() . '/'. plugin_basename( dirname( __FILE__ ) ) . '/' );
    
}

final class DEV_DOCS {

    /**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 *
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.0';

    /**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

    /**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var $_instance The single instance of the class.
	 */
	private static $_instance = null;

    /**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return An instance of this class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

    /**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
                
		add_action( 'plugins_loaded', [ $this, 'on_plugin_loaded' ] );

	}

    /**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function i18n() {

		load_plugin_textdomain( 'dev-docs' );

	}

    /**
	 * On Plugins Loaded
	 *
	 * Checks if Elementor has loaded, and performs some compatibility checks.
	 * If All checks pass, inits the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function on_plugin_loaded() {
        if ( $this->is_compatible() ) {
            $this->i18n();
        }
        
    }

    /**
	 * Compatibility Checks
	 *
	 * Checks if the installed version of Elementor meets the plugin's minimum requirement.
	 * Checks if the installed PHP version meets the plugin's minimum requirement.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function is_compatible() {
            
        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return false;
        }

        return true;
            
    }

    /**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'dev-docs' ),
			'<strong>' . esc_html( DEVDOCS_NAME ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'dev-docs' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

}