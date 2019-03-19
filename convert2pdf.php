<?php

if ( empty( $_FILES['input'] ) ) {
	echo 'Input file not set';
	http_response_code( 401 );
	die();
}

$ext = pathinfo( $_FILES['input']['name'], PATHINFO_EXTENSION );
while ( true ) {
	$file_base = sys_get_temp_dir() . '/' . uniqid( 'convertpdf', true );
	$filename = $file_base . '.' . $ext;
	if ( ! file_exists( $filename ) ) {
		break;
	}
}

if ( ! move_uploaded_file( $_FILES['input']['tmp_name'], $filename ) ) {
	echo 'Input file invalid';
	http_response_code( 401 );
	die();
}

require_once 'vendor/autoload.php';

try {
	$pdffile   = $file_base . '.pdf';
	$pdfname   = pathinfo( $_FILES['input']['name'], PATHINFO_FILENAME ) . '.pdf';
	$converter = new \NcJoes\OfficeConverter\OfficeConverter( $filename );
	$converter->convertTo( $pdffile );

	header( "Content-type:application/pdf" );
	header( "Content-Disposition:attachment;filename=\"$pdfname\"" );

	readfile( $pdffile );
} catch ( \NcJoes\OfficeConverter\OfficeConverterException $exception ) {
	echo $exception->getMessage();
	http_response_code( 500 );
}

@unlink( $filename );
@unlink( $pdffile );
