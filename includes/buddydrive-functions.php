<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * What is the version in db ?
 *
 * @uses get_option() to get the BuddyDrive version
 * @return string the version
 */
function buddydrive_get_db_version(){
	return get_option( '_buddydrive_version' );
}

/**
 * What is the version of the plugin.
 *
 * @uses buddydrive()
 * @return string the version of the plugin
 */
function buddydrive_get_version() {
	return buddydrive()->version;
}

/**
 * Is it the first install ?
 *
 * @uses get_option() to get the BuddyDrive version
 * @return boolean true or false
 */
function buddydrive_is_install() {
	$buddydrive_version = get_option( '_buddydrive_version', '' );
	
	if( empty( $buddydrive_version ) )
		return true;
	else
		return false;
}

/**
 * Do we need to eventually update ?
 *
 * @uses get_option() to get the BuddyDrive version
 * @return boolean true or false
 */
function buddydrive_is_update() {
	$buddydrive_version = get_option( '_buddydrive_version', '' );
	
	if( !empty( $buddydrive_version ) )
		return true;
	else
		return false;
}

/**
 * displays the slug of the plugin
 * 
 * @uses buddydrive_get_slug() to get it!
 */
function buddydrive_slug() {
	echo buddydrive_get_slug();
}
	
	/**
	 * Gets the slug of the plugin
	 * 
	 * @uses buddydrive()
	 * @return string the slug
	 */
	function buddydrive_get_slug() {
		return buddydrive()->buddydrive_slug;
	}

/**
 * displays the name of the plugin
 * 
 * @uses buddydrive_get_name() to get it!
 */
function buddydrive_name() {
	echo buddydrive_get_name();
}

	/**
	 * Gets the name of the plugin
	 * 
	 * @uses buddydrive()
	 * @return string the name
	 */
	function buddydrive_get_name() {
		return buddydrive()->buddydrive_name;
	}

/**
 * displays file post type of the plugin
 * 
 * @uses buddydrive_get_file_post_type() to get it!
 */
function buddydrive_file_post_type() {
	echo buddydrive_get_file_post_type();
}
	
	/**
	 * Gets the file post type of the plugin
	 * 
	 * @uses buddydrive()
	 * @return string the file post type
	 */
	function buddydrive_get_file_post_type() {
		return buddydrive()->buddydrive_file_post_type;
	}

/**
 * displays folder post type of the plugin
 * 
 * @uses buddydrive_get_folder_post_type() to get it!
 */
function buddydrive_folder_post_type() {
	echo buddydrive_get_folder_post_type();
}

	/**
	 * Gets the folder post type of the plugin
	 * 
	 * @uses buddydrive()
	 * @return string the folder post type
	 */
	function buddydrive_get_folder_post_type() {
		return buddydrive()->buddydrive_folder_post_type;
	}

/**
 * What is the path to the includes dir ?
 *
 * @uses  buddydrive()
 * @return string the path
 */
function buddydrive_get_includes_dir() {
	return buddydrive()->includes_dir;
}

/**
 * What is the path of the plugin dir ?
 *
 * @uses  buddydrive()
 * @return string the path
 */
function buddydrive_get_plugin_dir() {
	return buddydrive()->plugin_dir;
}

/**
 * What is the url to the plugin dir ?
 *
 * @uses  buddydrive()
 * @return string the url
 */
function buddydrive_get_plugin_url() {
	return buddydrive()->plugin_url;
}

/**
 * What is the url of includes dir ?
 *
 * @uses  buddydrive()
 * @return string the url
 */
function buddydrive_get_includes_url() {
	return buddydrive()->includes_url;
}

/**
 * What is the url to the images dir ?
 *
 * @uses  buddydrive()
 * @return string the url
 */
function buddydrive_get_images_url() {
	return buddydrive()->images_url;
}

/**
 * What is the root url for BuddyDrive ?
 * 
 * @uses buddydrive_get_root_url() to get it
 */
function buddydrive_root_url() {
	echo buddydrive_get_root_url();
}

	/**
	 * Gets the root url for BuddyDrive
	 *
	 * @uses bp_get_root_domain() to get the root blog domain
	 * @uses buddydrive_get_slug() to get BuddyDrive Slug
	 * @return strin the url
	 */
	function buddydrive_get_root_url() {
		$root_domain_url = bp_get_root_domain();
		$buddydrive_slug = buddydrive_get_slug();
		$buddydrive_root_url = trailingslashit( $root_domain_url ) . $buddydrive_slug;
		return $buddydrive_root_url;
	}

/**
 * Builds an array for BuddyDrive upload data
 *
 * @uses wp_upload_dir() to get WordPress basedir and baseurl
 * @return array
 */
function buddydrive_get_upload_data() {
	$upload_datas = wp_upload_dir();
	
	$buddydrive_dir = $upload_datas["basedir"] .'/buddydrive';
	$buddydrive_url = $upload_datas["baseurl"] .'/buddydrive';
	$buddydrive_upload_data = array( 'dir' => $buddydrive_dir, 'url' => $buddydrive_url );
	
	//finally returns $buddydrive_upload_data
	return $buddydrive_upload_data;
}

/**
 * Handles Plugin activation
 *
 * @uses bp_core_get_directory_page_ids() to get the BuddyPress component page ids
 * @uses buddydrive_get_slug() to get BuddyDrive slug
 * @uses wp_insert_post() to eventually create a new page for BuddyDrive
 * @uses buddydrive_get_name() to get BuddyDrive plugin name
 * @uses bp_core_update_directory_page_ids() to update the BuddyPres component pages ids
 */
function buddydrive_activation() {

	// let's check for BuddyDrive page in directory pages first !
	$directory_pages = bp_core_get_directory_page_ids();
	$buddydrive_slug = buddydrive_get_slug();

	if( empty( $directory_pages[$buddydrive_slug] ) ) {
		// let's create a page and add it to BuddyPress directory pages
		$buddydrive_page_content = __( 'BuddyDrive uses this page to manage the downloads of your buddies files, please leave it as is. It will not show in your navigation bar.', 'buddydrive');

		$buddydrive_page_id = wp_insert_post( array( 
												'comment_status' => 'closed', 
												'ping_status'    => 'closed', 
												'post_title'     => buddydrive_get_name(),
												'post_content'   => $buddydrive_page_content,
												'post_name'      => $buddydrive_slug,
												'post_status'    => 'publish', 
												'post_type'      => 'page' 
												) );
		
		$directory_pages[$buddydrive_slug] = $buddydrive_page_id;
		bp_core_update_directory_page_ids( $directory_pages );
	}

	do_action( 'buddydrive_activation' );
}

/**
 * Handles plugin deactivation
 * 
 * @uses bp_core_get_directory_page_ids() to get the BuddyPress component page ids
 * @uses buddydrive_get_slug() to get BuddyDrive slug
 * @uses wp_delete_post() to eventually delete the BuddyDrive page
 * @uses bp_core_update_directory_page_ids() to update the BuddyPres component pages ids
 */
function buddydrive_deactivation() {

	$directory_pages = bp_core_get_directory_page_ids();
	$buddydrive_slug = buddydrive_get_slug();

	if( !empty( $directory_pages[$buddydrive_slug] ) ) {
		// let's remove the page as the plugin is deactivated.
		
		$buddydrive_page_id = $directory_pages[$buddydrive_slug];
		wp_delete_post( $buddydrive_page_id, true );
		
		unset( $directory_pages[$buddydrive_slug] );
		bp_core_update_directory_page_ids( $directory_pages );
	}


	do_action( 'buddydrive_deactivation' );
}

/**
 * Welcome screen step one : set transient
 * 
 * @uses buddydrive_is_install() to check of first install
 * @uses set_transient() to temporarly save some data to db
 */
function buddydrive_add_activation_redirect() {

	// Bail if activating from network, or bulk
	if ( isset( $_GET['activate-multi'] ) )
		return;

	// Record that this is a new installation, so we show the right
	// welcome message
	if ( buddydrive_is_install() ) {
		set_transient( '_buddydrive_is_new_install', true, 30 );
	}

	// Add the transient to redirect
	set_transient( '_buddydrive_activation_redirect', true, 30 );
}

/**
 * Welcome screen step two
 * 
 * @uses get_transient() 
 * @uses delete_transient()
 * @uses wp_safe_redirect to redirect to the Welcome screen
 * @uses add_query_arg() to add some arguments to the url
 * @uses bp_get_admin_url() to build the admin url
 */
function buddydrive_do_activation_redirect() {

	// Bail if no activation redirect
	if ( ! get_transient( '_buddydrive_activation_redirect' ) )
		return;

	// Delete the redirect transient
	delete_transient( '_buddydrive_activation_redirect' );

	// Bail if activating from network, or bulk
	if ( isset( $_GET['activate-multi'] ) )
		return;

	$query_args = array( 'page' => 'buddydrive-about' );

	if ( get_transient( '_buddydrive_is_new_install' ) ) {
		$query_args['is_new_install'] = '1';
		delete_transient( '_buddydrive_is_new_install' );
	}

	// Redirect to BuddyDrive about page
	wp_safe_redirect( add_query_arg( $query_args, bp_get_admin_url( 'index.php' ) ) );
}


/**
 * Checks plugin version against db and updates
 *
 * @uses buddydrive_is_install() to see if first install
 * @uses buddydrive_get_db_version() to get db version
 * @uses buddydrive_get_version() to get BuddyDrive plugin version
 */
function buddydrive_check_version() {
	if( buddydrive_is_install() || version_compare( buddydrive_get_db_version(), buddydrive_get_version(), '<' ) ) {
		
		update_option( '_buddydrive_version', buddydrive_get_version() );

	}
}

add_action( 'buddydrive_admin_init', 'buddydrive_check_version' );


/**
 * Returns the BuddyDrive Max upload size
 * 
 * @param  boolean $bytes do we want it in bytes ?
 * @uses wp_max_upload_size() to get the config max upload size
 * @uses get_option() to get the admin settings for BuddyDrive
 * @return int the max upload size
 */
function buddydrive_max_upload_size( $bytes = false ) {
	$max_upload = wp_max_upload_size();
	$max_upload_mo = $max_upload / 1024 / 1024;
	
	$buddydrive_max_upload  = get_option( '_buddydrive_max_upload', $max_upload_mo );
	$buddydrive_max_upload = intval( $buddydrive_max_upload );

	if( empty( $bytes ) )
		return $buddydrive_max_upload;
	else
		return $buddydrive_max_upload * 1024 * 1024;

}

/**
 * Tells if a value is checked in an array
 * 
 * @param  string $value the value to check
 * @param  array $array where too check ?
 * @uses checked() to activate the checkbox
 * @return boolean|string (false or 'checked')
 */
function buddydrive_array_checked( $value = false, $array = false ) {
	
	if( empty( $value ) || empty( $array ) )
		return false;

	$array = array_flip( $array );

	if( in_array( $value, $array ) )
		return checked(true);

}

/**
 * What are the mime types allowed by admin ?
 * 
 * @param  array $allowed_file_types WordPress default
 * @uses get_option() to get the choice of the admin
 * @return array the mime types allowed by admin
 */
function buddydrive_allowed_file_types( $allowed_file_types ) {
	
	$allowed_ext = get_option('_buddydrive_allowed_extensions');

	if( empty( $allowed_ext ) || !is_array( $allowed_ext ) || count( $allowed_ext ) < 1 )
		return $allowed_file_types;

	$allowed_ext = array_flip($allowed_ext);
	$allowed_ext = array_intersect_key( $allowed_file_types, $allowed_ext );

	return $allowed_ext;
}
