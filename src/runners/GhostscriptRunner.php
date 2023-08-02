<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 22/03/2019
 * Time: 08:10
 */

require_once 'BaseRunner.php';

class GhostscriptRunner extends BaseRunner {
	protected function getBin() {
		return 'gs';
	}

	protected function getProcessingFormats() {
		return [
			'pdf' => [
				'screen'   => [ 'pdf' ],
				'ebook'    => [ 'pdf' ],
				'printer'  => [ 'pdf' ],
				'prepress' => [ 'pdf' ],
			],
		];
	}

	protected function getCommand( $preset, $inputFileName, $outputDirectory, $outputFileName, $outputExtension ) {
		return "-sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/$preset -dNOPAUSE -dQUIET -dBATCH -sOutputFile=$outputFileName $inputFileName";
	}

	/**
	 * @param string $outputFileName
	 *
	 * @return string|null
	 * @throws RunnerException
	 */
	public function optimizeScreen( $outputFileName ) {
		return $this->process( 'screen', $outputFileName );
	}

	/**
	 * @param string $outputFileName
	 *
	 * @return string|null
	 * @throws RunnerException
	 */
	public function optimizeEbook( $outputFileName ) {
		return $this->process( 'ebook', $outputFileName );
	}

	/**
	 * @param string $outputFileName
	 *
	 * @return string|null
	 * @throws RunnerException
	 */
	public function optimizePrinter( $outputFileName ) {
		return $this->process( 'printer', $outputFileName );
	}

	/**
	 * @param string $outputFileName
	 *
	 * @return string|null
	 * @throws RunnerException
	 */
	public function optimizePrepress( $outputFileName ) {
		return $this->process( 'prepress', $outputFileName );
	}
}