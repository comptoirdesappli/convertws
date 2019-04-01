<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 01/04/2019
 * Time: 08:20
 */

require_once 'BaseRunner.php';

class CalibreEbookConvertRunner extends BaseRunner {
	protected function getBin() {
		return 'ebook-convert';
	}

	protected function getProcessingFormats() {
		return [
			'pdf'  => 'epub',
			'docx' => 'epub',
			'odt'  => 'epub',
			'epub' => 'mobi',
		];
	}

	protected function getCommand( $preset, $inputFileName, $outputDirectory, $outputFileName, $outputExtension ) {
		return "$inputFileName $outputFileName";
	}
}