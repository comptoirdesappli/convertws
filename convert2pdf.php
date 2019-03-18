<?php

/**
 * @param $filename
 *
 * @throws RunnerException
 */
function process_file( $filename ) {
	require_once 'runners/LibreOfficeConverter.php';

	$pdffile   = $filename . '.pdf';
	$pdfname   = pathinfo( $_FILES['input']['name'], PATHINFO_FILENAME ) . '.pdf';
	$converter = new LibreOfficeConverter( $filename );
	$converter->convert( $pdffile );

	header( "Content-type:application/pdf" );
	header( "Content-Disposition:attachment;filename='$pdfname'" );

	readfile( $pdffile );
	@unlink( $filename );
}

//run process_file function
require 'process.php';
