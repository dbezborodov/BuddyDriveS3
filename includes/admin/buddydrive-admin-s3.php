<?php
/**
 * BuddyDrive S3 admin routines
 *
 * @author Dmitry Bezborodov
 * @version 1.1.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Add new fields to BuddyDrive settings
 *
 * @param array $fields Array of settings fields
 * @return array $fields Modified array
 */
function buddydrive_admin_get_s3_settings_fields( $fields ) {
	$fields['buddydrive_settings_main']['_buddydrive_s3_access_key'] = array(
				'title'             => __( 'S3 Access Key', 'buddydrive' ),
				'callback'          => 'buddydrive_admin_setting_callback_s3_access_key',
				'sanitize_callback' => 'buddydrive_sanitize_s3_access_key',
				'args'              => array()
			);

	$fields['buddydrive_settings_main']['_buddydrive_s3_secret_key'] = array(
				'title'             => __( 'S3 Secret Access Key', 'buddydrive' ),
				'callback'          => 'buddydrive_admin_setting_callback_s3_secret_key',
				'sanitize_callback' => 'buddydrive_sanitize_s3_secret_key',
				'args'              => array()
			);

	$fields['buddydrive_settings_main']['_buddydrive_s3_bucket'] = array(
				'title'             => __( 'S3 Bucket name to store files', 'buddydrive' ),
				'callback'          => 'buddydrive_admin_setting_callback_s3_bucket',
				'sanitize_callback' => 'buddydrive_sanitize_s3_bucket',
				'args'              => array()
			);

	$fields['buddydrive_settings_main']['_buddydrive_s3_use_rrs'] = array(
				'title'             => __( 'Use S3 Reduce Redundancy storage for newly uploaded files', 'buddydrive' ),
				'callback'          => 'buddydrive_admin_setting_callback_s3_use_rrs',
				'args'              => array()
			);

	$fields['buddydrive_settings_main']['_buddydrive_s3_encrypt'] = array(
				'title'             => __( 'Enable S3 Server Side Encryption for newly uploaded files', 'buddydrive' ),
				'callback'          => 'buddydrive_admin_setting_callback_s3_encrypt',
				'args'              => array()
			);

	$fields['buddydrive_settings_main']['_buddydrive_s3_https'] = array(
				'title'             => __( 'Force upload and download work only over SSL', 'buddydrive' ),
				'callback'          => 'buddydrive_admin_setting_callback_s3_https',
				'args'              => array()
			);

	$fields['buddydrive_settings_main']['_buddydrive_s3_move'] = array(
				'title'             => __( 'Move files to S3', 'buddydrive' ),
				'callback'          => 'buddydrive_admin_setting_callback_s3_move',
				'sanitize_callback' => 'buddydrive_sanitize_s3_move',
				'args'              => array()
			);

	return $fields;
}

/**
 * Let the admin set S3 Access Key
 *
 * @uses bp_get_option() to get option value
 * @return string html
 */
function buddydrive_admin_setting_callback_s3_access_key() {
	$s3_access_key = bp_get_option( '_buddydrive_s3_access_key' );
	?>

	<input name="_buddydrive_s3_access_key" type="text" id="_buddydrive_s3_access_key" value="<?php echo $s3_access_key;?>" class="regular-text" />

	<?php
}

/**
 * Sanitize the S3 Access Key
 *
 * @param string $option 
 * @return string $option
 */
function buddydrive_sanitize_s3_access_key( $option ) {
	$input = trim( $_POST['_buddydrive_s3_access_key'] );
	
	return $input;
}

/**
 * Let the admin set S3 Secret Access Key
 *
 * @uses bp_get_option() to get option value
 * @return string html
 */
function buddydrive_admin_setting_callback_s3_secret_key() {
	$s3_secret_key = bp_get_option( '_buddydrive_s3_secret_key' );
	?>

	<input name="_buddydrive_s3_secret_key" type="password" id="_buddydrive_s3_secret_key" value="<?php echo $s3_secret_key;?>" class="regular-text" />

	<?php
}

/**
 * Sanitize the S3 Secret Access Key
 *
 * @param string $option 
 * @return string $option
 */
function buddydrive_sanitize_s3_secret_key( $option ) {
	$input = trim( $_POST['_buddydrive_s3_secret_key'] );
	
	return $input;
}


/**
 * Let the admin set S3 Bucket name
 *
 * @uses bp_get_option() to get option value
 * @return string html
 */
function buddydrive_admin_setting_callback_s3_bucket() {
	$s3_bucket = bp_get_option( '_buddydrive_s3_bucket' );
	?>

	<input name="_buddydrive_s3_bucket" type="text" id="_buddydrive_s3_bucket" value="<?php echo $s3_bucket;?>" class="regular-text" />

	<?php
}

/**
 * Sanitize the S3 Bucket name
 *
 * @param string $option 
 * @return string $option
 */
function buddydrive_sanitize_s3_bucket( $option ) {
	$input = trim( $_POST['_buddydrive_s3_bucket'] );
	
	return $input;
}


/**
 * Let the admin choose storage option
 *
 * @uses bp_get_option() to get option value
 * @return string html
 */
function buddydrive_admin_setting_callback_s3_use_rrs() {
	$s3_use_rrs = (bool) bp_get_option( '_buddydrive_s3_use_rrs' );
	?>

	<input id="_buddydrive_s3_use_rrs" name="_buddydrive_s3_use_rrs" type="checkbox" value="1" <?php checked( $s3_use_rrs ); ?> />
	<label for="_buddydrive_s3_use_rrs"><a href='http://aws.amazon.com/s3/faqs/#What_is_RRS' target=_blank><?php _e( 'What\'s this?', 'buddypress' ); ?></a></label>

	<?php
}

/**
 * Let the admin choose server side encryption
 *
 * @uses bp_get_option() to get option value
 * @return string html
 */
function buddydrive_admin_setting_callback_s3_encrypt() {
	$s3_encrypt = (bool) bp_get_option( '_buddydrive_s3_encrypt' );
	?>

	<input id="_buddydrive_s3_encrypt" name="_buddydrive_s3_encrypt" type="checkbox" value="1" <?php checked( $s3_encrypt ); ?> />
	<label for="_buddydrive_s3_encrypt"><a href='http://aws.amazon.com/s3/faqs/#What_options_do_I_have_for_encrypting_data_stored_on_Amazon_S3' target=_blank><?php _e( 'What\'s this?', 'buddypress' ); ?></a></label>

	<?php
}

/**
 * Let the admin force using SSL (https)
 *
 * @uses bp_get_option() to get option value
 * @return string html
 */
function buddydrive_admin_setting_callback_s3_https() {
	$s3_https = (bool) bp_get_option( '_buddydrive_s3_https' );
	?>

	<input id="_buddydrive_s3_https" name="_buddydrive_s3_https" type="checkbox" value="1" <?php checked( $s3_https ); ?> />
	<label for="_buddydrive_s3_https"></label>

	<?php
}

/**
 * Let the admin move local files to S3
 *
 * @return string html
 */
function buddydrive_admin_setting_callback_s3_move() {
	?>

	<input id="_buddydrive_s3_move" name="_buddydrive_s3_move" type="checkbox" value="1" />
	<label for="_buddydrive_s3_move">Use this to move files from local version of Buddydrive to S3</label>

	<?php
}

/**
 * Sanitize the Move checkbox
 *
 * @param string $option 
 * @uses WP_Query to retrieve posts from WP database
 * @uses buddydrive_uploadto_s3() to upload files
 * @uses wp_reset_postdata() to reset global $post
 * @return string $option
 */
function buddydrive_sanitize_s3_move( $option ) {
	if ( $_POST['_buddydrive_s3_move'] ) {
		set_time_limit(0);

		// Select all BuddyDrive files
		$query = new WP_Query($args = array( 'post_status' => 'publish',
						     'post_type'   => buddydrive()->buddydrive_file_post_type
							));

		$upload_datas = wp_upload_dir();
		$buddydrive_dir = $upload_datas["basedir"] .'/buddydrive';

		// Move all existing to S3
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				$file = basename( $query->post->guid );
				$path = $buddydrive_dir .'/'. $file;

				if( file_exists( $path ) ) {
				    buddydrive_uploadto_s3( array('file' => $path, 
								  'type' => $query->post->post_mime_type), 
							    $query->post->post_author );
				}
			}
		}

		wp_reset_postdata();
	}
	return 0;
}

add_filter( 'buddydrive_admin_get_settings_fields', 'buddydrive_admin_get_s3_settings_fields' );
