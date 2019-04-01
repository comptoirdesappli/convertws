<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 01/04/2019
 * Time: 08:45
 */

require_once 'BaseRunner.php';

class QpdfRunner extends BaseRunner {
	protected function getBin() {
		return 'qpdf';
	}

	protected function getProcessingFormats() {
		return [
			'pdf' => [
				'unprotect' => [ 'pdf' ],
			],
		];
	}

	protected function getCommand( $preset, $inputFileName, $outputDirectory, $outputFileName, $outputExtension ) {
		return "--decrypt {$inputFileName} {$outputExtension}";
	}

	/**
	 * @param string $outputFileName
	 *
	 * @return string|null
	 * @throws RunnerException
	 */
	public function unprotect( $outputFileName ) {
		return $this->process( 'unprotect', $outputFileName );
	}
}