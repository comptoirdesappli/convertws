<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 01/04/2019
 * Time: 08:25
 */

/**
 * @param $filename
 *
 * @throws RunnerException
 */
function process_file( $filename ) {
	require_once 'runners/CalibreEbookConvertRunner.php';

	$output            = isset( $_REQUEST['output'] ) ? $_REQUEST['output'] : 'epub';
	$temp_out_filename = BaseRunner::replace_extension( $filename, ".$output" );
	$output_name       = pathinfo( $_FILES['input']['name'], PATHINFO_FILENAME ) . ".$output";
	$converter         = new CalibreEbookConvertRunner( $filename );
	$converter->convert( $temp_out_filename );

	header( 'Content-type:' . mime_content_type( $temp_out_filename ) );
	header( "Content-Disposition:attachment;filename=\"$output_name\"" );

	readfile( $temp_out_filename );
	@unlink( $temp_out_filename );
}

//run process_file function
require 'process.php';