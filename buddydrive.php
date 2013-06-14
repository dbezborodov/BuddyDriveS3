<?php
/*
Plugin Name: Buddydrive
Plugin URI: http://imathi.eu/tag/buddydrive/
Description: A plugin to share files, the BuddyPress way!
Version: 1.0
Author: imath
Author URI: http://imathi.eu/
License: GPLv2
Network: true
Text Domain: buddydrive
Domain Path: /languages/
*/


// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


if ( !class_exists( 'BuddyDrive' ) ) :

/**
 * Main BuddyDrive Class
 *
 * Inspired by bbpress 2.3
 */
class BuddyDrive {
	
	private $data;

	private static $instance;

	/**
	 * Main BuddyDrive Instance
	 *
	 * Inspired by bbpress 2.3
	 *
	 * Avoids the use of a global
	 *
	 * @package BuddyDrive
	 * @since 1.0
	 *
	 * @uses BuddyDrive::setup_globals() to set the global needed
	 * @uses BuddyDrive::includes() to include the required files
	 * @uses BuddyDrive::setup_actions() to set up the hooks
	 * @return object the instance
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new BuddyDrive;
			self::$instance->setup_globals();
			self::$instance->includes();
			self::$instance->setup_actions();
		}
		return self::$instance;
	}

	
	private function __construct() { /* Do nothing here */ }
	
	public function __clone() { _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'buddydrive' ), '1.0' ); }

	public function __wakeup() { _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'buddydrive' ), '1.0' ); }

	public function __isset( $key ) { return isset( $this->data[$key] ); }

	public function __get( $key ) { return isset( $this->data[$key] ) ? $this->data[$key] : null; }

	public function __set( $key, $value ) { $this->data[$key] = $value; }

	public function __unset( $key ) { if ( isset( $this->data[$key] ) ) unset( $this->data[$key] ); }

	public function __call( $name = '', $args = array() ) { unset( $name, $args ); return null; }


	/**
	 * Some usefull vars
	 *
	 * @package BuddyDrive
	 * @since 1.0
	 *
	 * @uses plugin_basename()
	 * @uses plugin_dir_path() to build BuddyDrive plugin path
	 * @uses plugin_dir_url() to build BuddyDrive plugin url
	 */
	private function setup_globals() {

		/** Version ***********************************************************/

		$this->version    = '1.0';

		/** Paths *************************************************************/

		// Setup some base path and URL information
		$this->file       = __FILE__;
		$this->basename   = apply_filters( 'buddydrive_plugin_basename', plugin_basename( $this->file ) );
		$this->plugin_dir = apply_filters( 'buddydrive_plugin_dir_path',  plugin_dir_path( $this->file ) );
		$this->plugin_url = apply_filters( 'buddydrive_plugin_dir_url',   plugin_dir_url ( $this->file ) );

		// Includes
		$this->includes_dir = apply_filters( 'buddydrive_includes_dir', trailingslashit( $this->plugin_dir . 'includes'  ) );
		$this->includes_url = apply_filters( 'buddydrive_includes_url', trailingslashit( $this->plugin_url . 'includes'  ) );
		$this->upload_dir   = false;
		$this->upload_url   = false;
		$this->images_url = apply_filters( 'buddydrive_images_url', trailingslashit( $this->includes_url . 'images'  ) );

		// Languages
		$this->lang_dir     = apply_filters( 'buddydrive_lang_dir',     trailingslashit( $this->plugin_dir . 'languages' ) );
		
		// BuddyDrive slug and name
		$this->buddydrive_slug = apply_filters( 'buddydrive_slug', 'buddydrive' );
		$this->buddydrive_name = apply_filters( 'buddydrive_name', 'BuddyDrive' );

		// Post type identifiers
		$this->buddydrive_file_post_type   = apply_filters( 'buddydrive_file_post_type',   'buddydrive-file' );
		$this->buddydrive_folder_post_type = apply_filters( 'buddydrive_folder_post_type', 'buddydrive-folder' );


		/** Misc **************************************************************/

		$this->domain         = 'buddydrive';
		$this->errors         = new WP_Error(); // Feedback
		
	}
	
	/**
	 * includes the needed files
	 *
	 * @package BuddyDrive
	 * @since 1.0
	 *
	 * @uses is_admin() for the settings files
	 */
	private function includes() {
		require( $this->includes_dir . 'buddydrive-actions.php'        );
		require( $this->includes_dir . 'buddydrive-functions.php'        );
		
		if( is_admin() ){
			require( $this->includes_dir . 'admin/buddydrive-admin.php'        );
		}
	}
	

	/**
	 * It's about hooks!
	 *
	 * @package BuddyDrive
	 * @since 1.0
	 *
	 * The main hook used is bp_include to load our custom BuddyPress component
	 */
	private function setup_actions() {

		// Add actions to plugin activation and deactivation hooks
		add_action( 'activate_'   . $this->basename, 'buddydrive_activation'   );
		add_action( 'deactivate_' . $this->basename, 'buddydrive_deactivation' );
		
		add_action( 'bp_init', array( $this, 'load_textdomain' ), 6 );
		add_action( 'bp_include', array( $this, 'load_component' ) );

		do_action_ref_array( 'buddydrive_after_setup_actions', array( &$this ) );
	}
	
	/**
	 * Loads the translation
	 *
	 * @package BuddyDrive
	 * @since 1.0
	 * @uses get_locale()
	 * @uses load_textdomain()
	 */
	public function load_textdomain() {
		// try to get locale
		$locale = apply_filters( 'buddydrive_load_textdomain_get_locale', get_locale() );

		// if we found a locale, try to load .mo file
		if ( !empty( $locale ) ) {
			// default .mo file path
			$mofile_default = sprintf( '%s/languages/%s-%s.mo', $this->plugin_dir, $this->domain, $locale );
			// final filtered file path
			$mofile = apply_filters( 'buddydrive_textdomain_mofile', $mofile_default );
			// make sure file exists, and load it
			if ( file_exists( $mofile ) ) {
				load_textdomain( $this->domain, $mofile );
			}
		}
	}

	/**
	 * Finally, let's safely load our component
	 *
	 * @package BuddyDrive
	 * @since 1.0
	 */
	public function load_component() {
		require( $this->includes_dir . 'buddydrive-component.php' );
	}
	
}

function buddydrive() {
	return buddydrive::instance();
}

buddydrive();


endif;

