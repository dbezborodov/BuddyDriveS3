<?php
/**
 * BuddyDrive S3 routines
 *
 * @author Dmitry Bezborodov <bezborodov@gmail.com>
 * @version 0.1.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

require_once('settings.php');
require_once('S3.php');

/**
 * Uploads a file from WP upload dir to S3 
 * S3_BUCKET/wp_user_login/filename
 * 
 * @param array $data WP file info
 * @uses S3::setExceptions() to throw an exception on S3 error
 * @uses S3::putObject() to upload the file
 * @uses bp_loggedin_user_id() to get the current user id
 * @uses get_userdata() to get the user's login
 * @return array $data File info with an eventual error
 */
function buddydrive_uploadto_s3( $data ) {

	$user_info = get_userdata( bp_loggedin_user_id() );

	try {
            $s3 = new S3(S3_ACCESS_KEY, S3_SECRET_ACCESS_KEY);
	    $s3->setExceptions(true);

     	    $res = $s3->putObject( $s3->inputFile($data['file']), 
			        S3_BUCKET, 
				$user_info->user_login . '/' . basename($data['file']), 
				S3::ACL_PRIVATE, 
				array(), 
				array('Content-Type' => $data['type']), 
				S3_USE_RRS ? S3::STORAGE_CLASS_RRS : S3::STORAGE_CLASS_STANDARD );

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
 * @uses get_userdata() to get the user's login
 */
function buddydrive_downloadfrom_s3( $path, $owner_id ) {

	$user_info = get_userdata( $owner_id );

        $s3 = new S3(S3_ACCESS_KEY, S3_SECRET_ACCESS_KEY);
	$s3->setExceptions(false);

	$res = $s3->getObject( S3_BUCKET, $user_info->user_login . '/' . basename($path), $path);
}

/**
 * Returns size of a file stored on S3 
 * 
 * @param string $path Path to the file
 * @param string $owner_id File owner ID
 * @uses S3::setExceptions() to throw an exception on S3 error
 * @uses S3::getObjectInfo() to retrieve file information
 * @uses get_userdata() to get the user's login
 * @return integer File size in bytes
 */
function buddydrive_filesize_s3( $path, $owner_id ) {

	$user_info = get_userdata( $owner_id );

	try {
            $s3 = new S3(S3_ACCESS_KEY, S3_SECRET_ACCESS_KEY);
	    $s3->setExceptions(false);

   	    $info = $s3->getObjectInfo( S3_BUCKET, $user_info->user_login . '/' . basename($path) );

	} catch (Exception $e) {
	}

	return $info['size'];
}

/**
 * Delete a file from S3
 * 
 * @param string $path Path to the file
 * @uses S3::setExceptions() to throw an exception on S3 error
 * @uses S3::deleteObject() to delete the file
 * @uses bp_loggedin_user_id() to get the current user id
 * @uses get_userdata() to get the user's login
 */
function buddydrive_deletefrom_s3( $path ) {

	$user_info = get_userdata( bp_loggedin_user_id() );

	try {
            $s3 = new S3(S3_ACCESS_KEY, S3_SECRET_ACCESS_KEY);
	    $s3->setExceptions(true);

            $res = $s3->deleteObject( S3_BUCKET, $user_info->user_login . '/' . basename($path) );

	} catch (Exception $e) {
	}
}