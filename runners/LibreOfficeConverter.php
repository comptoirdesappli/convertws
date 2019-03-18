<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 18/03/2019
 * Time: 00:56
 */

require_once 'BaseRunner.php';

class LibreOfficeConverter extends BaseRunner {
	protected function getBin() {
		return 'libreoffice';
	}

	protected function getProcessingFormats() {
		return [
			'pptx' => [ 'pdf' ],
			'ppt'  => [ 'pdf' ],
			'pdf'  => [ 'pdf' ],
			'docx' => [ 'pdf', 'odt', 'html' ],
			'doc'  => [ 'pdf', 'odt', 'html' ],
			'xlsx' => [ 'pdf' ],
			'xls'  => [ 'pdf' ],
		];
	}

	protected function getCommand( $preset, $inputFileName, $outputDirectory, $outputFileName, $outputExtension ) {
		return "--headless --convert-to {$outputExtension} {$inputFileName} --outdir {$outputDirectory}";
	}

	/**
	 * @param string $outputFileName
	 *
	 * @return string|null
	 * @throws RunnerException
	 */
	public function convert( $outputFileName ) {
		return $this->process( null, $outputFileName );
	}
}