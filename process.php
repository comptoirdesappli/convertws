<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 18/03/2019
 * Time: 01:10
 */

if ( ! function_exists( 'process_file' ) ) {
	echo 'Invalid invocation';
	http_response_code( 403 );
	die();
}

if ( empty( $_FILES['input'] ) ) {
	echo 'Input file not set';
	http_response_code( 401 );
	die();
}

$ext               = pathinfo( $_FILES['input']['name'], PATHINFO_EXTENSION );
$filename          = tempnam( sys_get_temp_dir(), 'convertws' );
$filename_with_ext = $filename . '.' . $ext;

if ( ! move_uploaded_file( $_FILES['input']['tmp_name'], $filename_with_ext ) ) {
	echo 'Input file invalid';
	http_response_code( 401 );
	die();
}

require_once 'runners/RunnerException.php';

try {
	process_file( $filename_with_ext );
} catch ( RunnerException $exception ) {
	echo $exception->getMessage();
	http_response_code( 500 );
}

@unlink( $filename_with_ext );
@unlink( $filename );
