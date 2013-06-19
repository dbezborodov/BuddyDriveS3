<?php
/**
 * BuddyDrive S3 routines
 *
 * @author Dmitry Bezborodov
 * @version 1.1.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

require_once('S3.php');

/**
 * Uploads a file from WP upload dir to S3 
 * $s3_bucket/wp_user_login/filename
 * 
 * @param array $data WP file info
 * @param integer $owner_id File owner ID
 * @uses S3::setExceptions() to throw an exception on S3 error
 * @uses S3::putObject() to upload the file
 * @uses bp_loggedin_user_id() to get the current user id
 * @uses get_userdata() to get the user's login
 * @uses bp_get_option() to get plugin options
 * @return array $data File info with an eventual error
 */
function buddydrive_uploadto_s3( $data, $owner_id=0 ) {

	if (!$owner_id) 
	    $owner_id = bp_loggedin_user_id();

	$user_info = get_userdata( $owner_id );

	try {
	    $s3_access_key  = bp_get_option( '_buddydrive_s3_access_key' );
	    $s3_secret_key  = bp_get_option( '_buddydrive_s3_secret_key' );
	    $s3_bucket      = bp_get_option( '_buddydrive_s3_bucket' );
	    $s3_use_rrs     = (bool) bp_get_option( '_buddydrive_s3_use_rrs' );
	    $s3_encrypt     = (bool) bp_get_option( '_buddydrive_s3_encrypt' );

	    if ( !$s3_access_key || !$s3_secret_key || !$s3_bucket ) return $data;

            $s3 = new S3($s3_access_key, $s3_secret_key);
	    $s3->setExceptions(true);

     	    $res = $s3->putObject( $s3->inputFile($data['file']), 
			        $s3_bucket, 
				$user_info->user_login . '/' . basename($data['file']), 
				S3::ACL_PRIVATE, 
				array(), 
				array('Content-Type' => $data['type']), 
				$s3_use_rrs ? S3::STORAGE_CLASS_RRS : S3::STORAGE_CLASS_STANDARD,
				$s3_encrypt ? S3::SSE_AES256 : S3::SSE_NONE );

	} catch (Exception $e) {
	    $data['error'] = $e->getMessage();
	}

	@unlink( $data['file'] );

	return $data;
}

/**
 * Download a file from S3 to given path
 * 
 * @param string $path Path to the file
 * @param string $owner_id File owner ID
 * @uses S3::setExceptions() to report a warning on S3 error
 * @uses S3::getObject() to retrieve the file
 * @uses bp_get_option() to get plugin options
 * @uses get_userdata() to get the user's login
 */
function buddydrive_downloadfrom_s3( $path, $owner_id ) {

	$user_info = get_userdata( $owner_id );

	$s3_access_key  = bp_get_option( '_buddydrive_s3_access_key' );
	$s3_secret_key  = bp_get_option( '_buddydrive_s3_secret_key' );
	$s3_bucket      = bp_get_option( '_buddydrive_s3_bucket' );

	if ( !$s3_access_key || !$s3_secret_key || !$s3_bucket ) return;

        $s3 = new S3($s3_access_key, $s3_secret_key);
	$s3->setExceptions(false);

	$res = $s3->getObject( $s3_bucket, $user_info->user_login . '/' . basename($path), $path);
}

/**
 * Returns size of a file stored on S3 
 * 
 * @param string $path Path to the file
 * @param string $owner_id File owner ID
 * @uses S3::setExceptions() to throw an exception on S3 error
 * @uses S3::getObjectInfo() to retrieve file information
 * @uses bp_get_option() to get plugin options
 * @uses get_userdata() to get the user's login
 * @return integer File size in bytes
 */
function buddydrive_filesize_s3( $path, $owner_id ) {

	$user_info = get_userdata( $owner_id );

	$s3_access_key  = bp_get_option( '_buddydrive_s3_access_key' );
	$s3_secret_key  = bp_get_option( '_buddydrive_s3_secret_key' );
	$s3_bucket      = bp_get_option( '_buddydrive_s3_bucket' );

	try {
            $s3 = new S3($s3_access_key, $s3_secret_key);
	    $s3->setExceptions(false);

   	    $info = $s3->getObjectInfo( $s3_bucket, $user_info->user_login . '/' . basename($path) );

	} catch (Exception $e) {
	}

	return $info['size'];
}

/**
 * Delete a file from S3
 * 
 * @param string $path Path to the file
 * @param string $owner_id File owner ID
 * @uses S3::setExceptions() to throw an exception on S3 error
 * @uses S3::deleteObject() to delete the file
 * @uses bp_loggedin_user_id() to get the current user id
 * @uses bp_get_option() to get plugin options
 * @uses get_userdata() to get the user's login
 */
function buddydrive_deletefrom_s3( $path, $owner_id ) {

	$user_info = get_userdata( $owner_id );

	$s3_access_key  = bp_get_option( '_buddydrive_s3_access_key' );
	$s3_secret_key  = bp_get_option( '_buddydrive_s3_secret_key' );
	$s3_bucket      = bp_get_option( '_buddydrive_s3_bucket' );

	try {
            $s3 = new S3($s3_access_key, $s3_secret_key);
	    $s3->setExceptions(true);

            $res = $s3->deleteObject( $s3_bucket, $user_info->user_login . '/' . basename($path) );

	} catch (Exception $e) {
	}
}

/**
 * Force Download links to use SSL
 *
 * @param string $link BuddyDrive file URL
 * @uses bp_get_option() to get option value
 * @return string Modified URL
 */
function buddydrive_s3_get_action_link( $link ) {
	$s3_https = (bool) bp_get_option( '_buddydrive_s3_https' );

	if ($s3_https)
 	    $link = set_url_scheme( $link, 'https' );

	return $link;
}

add_filter( 'buddydrive_get_action_link', 'buddydrive_s3_get_action_link' );
add_filter( 'bp_get_root_domain', 'buddydrive_s3_get_action_link' );
