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

$ext = pathinfo( $_FILES['input']['name'], PATHINFO_EXTENSION );
while ( true ) {
	$filename = sys_get_temp_dir() . '/' . uniqid( 'convertpdf', true ) . '.' . $ext;
	if ( ! file_exists( $filename ) ) {
		break;
	}
}

if ( ! move_uploaded_file( $_FILES['input']['tmp_name'], $filename ) ) {
	echo 'Input file invalid';
	http_response_code( 401 );
	die();
}

require_once 'runners/RunnerException.php';

try {
	process_file( $filename );
} catch ( RunnerException $exception ) {
	echo $exception->getMessage();
	http_response_code( 500 );
}

@unlink( $filename );
