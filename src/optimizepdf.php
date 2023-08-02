<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 22/03/2019
 * Time: 08:11
 */

/**
 * @param $filename
 *
 * @throws RunnerException
 */
function process_file( $filename ) {
	require_once 'runners/GhostscriptRunner.php';

	$temp_pdf_filename = BaseRunner::replace_extension( $filename, '.out.pdf' );
	$output_pdf_name   = pathinfo( $_FILES['input']['name'], PATHINFO_FILENAME ) . '.pdf';
	$converter         = new GhostscriptRunner( $filename );
	$converter->process( isset( $_POST['preset'] ) ? $_POST['preset'] : 'ebook', $temp_pdf_filename );

	header( "Content-type:application/pdf" );
	header( "Content-Disposition:attachment;filename=\"$output_pdf_name\"" );

	readfile( $temp_pdf_filename );
	@unlink( $temp_pdf_filename );
}

//run process_file function
require 'process.php';
