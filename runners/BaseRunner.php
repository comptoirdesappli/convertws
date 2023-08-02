<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 18/03/2019
 * Time: 00:25
 */

require_once 'RunnerException.php';

class BaseRunner {
	private $file;
	private $tempPath;
	private $extension;
	private $basename;

	/** Need Override Methods */

	/**
	 * Return bin to run
	 * @return string
	 */
	protected function getBin() {
		return '';
	}

	/**
	 * Return processing options
	 *
	 * @return array
	 */
	protected function getProcessingFormats() {
		return [];
	}

	/**
	 * Return command line
	 *
	 * @param string $preset
	 * @param string $inputFileName
	 * @param string $outputDirectory
	 * @param string $outputFileName
	 * @param string $outputExtension
	 *
	 * @return string
	 */
	protected function getCommand( $preset, $inputFileName, $outputDirectory, $outputFileName, $outputExtension ) {
		return '';
	}

	/**
	 * BaseRunner constructor.
	 *
	 * @param string $filename
	 * @param string|null $tempPath
	 *
	 * @throws RunnerException
	 */
	public function __construct( $filename, $tempPath = null ) {
		if ( $this->open( $filename ) ) {
			$this->setup( $tempPath );
		}
	}

	public static function replace_extension( $filename, $new_extension ) {
		$info = pathinfo( $filename );

		return ( $info['dirname'] ? $info['dirname'] . DIRECTORY_SEPARATOR : '' )
		       . $info['filename']
		       . '.'
		       . ltrim( $new_extension, '.' );
	}

	/**
	 * @param string|null $preset
	 * @param string $output_filename
	 *
	 * @return null|string
	 * @throws RunnerException
	 */
	public function process( $preset, $output_filename ) {
		$outputExtension     = pathinfo( $output_filename, PATHINFO_EXTENSION );
		$supportedExtensions = $this->getAllowedProcessing( $this->extension, $preset );

		if ( ! in_array( $outputExtension, $supportedExtensions ) ) {
			throw new RunnerException( "For preset {$preset}, output extension({$outputExtension}) not supported for input file({$this->basename})" );
		}

		$cmd       = $this->makeCommand( $preset, $this->tempPath, $output_filename, $outputExtension );
		$shell     = $this->exec( $cmd );
		$exit_code = $shell['return'];
		$stderr    = $shell['stderr'];
		if ( $exit_code != 0 || ! empty( $stderr ) ) {
			$stdout = $shell['stdout'];
			throw new RunnerException( "Processing Failed.\nExit code=$exit_code\nStdOut=$stdout\nStdErr=$stderr" );
		}

		if ( is_file( $output_filename ) ) {
			return $output_filename;
		}

		$temp_output_file = realpath( BaseRunner::replace_extension( $this->tempPath . DIRECTORY_SEPARATOR . $this->basename, $outputExtension ) );
		if ( is_file( $temp_output_file ) && rename( $temp_output_file, $output_filename ) ) {
			return $output_filename;
		}

		return null;
	}

	/**
	 * @param string $filename
	 *
	 * @return bool
	 * @throws RunnerException
	 */
	protected function open( $filename ) {
		if ( ! file_exists( $filename ) ) {
			throw new RunnerException( 'File does not exist --' . $filename );
		}
		$this->file = realpath( $filename );

		return true;
	}

	/**
	 * @param string|null $tempPath
	 *
	 * @throws RunnerException
	 */
	protected function setup( $tempPath ) {
		//basename
		$this->basename = pathinfo( $this->file, PATHINFO_BASENAME );

		//extension
		$extension = pathinfo( $this->file, PATHINFO_EXTENSION );

		//Check for valid input file extension
		if ( ! array_key_exists( $extension, $this->getAllowedProcessing() ) ) {
			throw new RunnerException( 'Input file extension not supported -- ' . $extension );
		}
		$this->extension = $extension;

		//setup output path
		if ( null == $tempPath || ! is_dir( $tempPath ) ) {
			$tempPath = dirname( $this->file );
		}
		$this->tempPath = realpath( $tempPath );
	}

	/**
	 * @param string $preset
	 * @param string $outputDirectory
	 * @param string $outputFileName
	 * @param string $outputExtension
	 *
	 * @return string
	 */
	protected function makeCommand( $preset, $outputDirectory, $outputFileName, $outputExtension ) {
		$inputFileName   = escapeshellarg( $this->file );
		$outputDirectory = escapeshellarg( $outputDirectory );
		$outputFileName  = escapeshellarg( $outputFileName );
		$bin             = $this->getBin();
		$cmd             = $this->getCommand( $preset, $inputFileName, $outputDirectory, $outputFileName, $outputExtension );

		return "$bin $cmd";
	}

	/**
	 * @param null|string $extension
	 * @param null|string $preset
	 *
	 * @return array|mixed
	 */
	private function getAllowedProcessing( $extension = null, $preset = null ) {
		$allowedProcessing = $this->getProcessingFormats();

		if ( $extension !== null ) {
			if ( isset( $allowedProcessing[ $extension ] ) ) {
				if ( $preset && isset( $allowedProcessing[ $extension ][ $preset ] ) ) {
					return $allowedProcessing[ $extension ][ $preset ];
				}

				return $allowedProcessing[ $extension ];
			}

			return [];
		}

		return $allowedProcessing;
	}

	/**
	 * More intelligent interface to system calls
	 *
	 * @link http://php.net/manual/en/function.system.php
	 *
	 * @param $cmd
	 * @param string $input
	 *
	 * @return array
	 */
	private function exec( $cmd, $input = '' ) {
		$process = proc_open( $cmd, [ 0 => [ 'pipe', 'r' ], 1 => [ 'pipe', 'w' ], 2 => [ 'pipe', 'w' ] ], $pipes );
		fwrite( $pipes[0], $input );
		fclose( $pipes[0] );
		$stdout = stream_get_contents( $pipes[1] );
		fclose( $pipes[1] );
		$stderr = stream_get_contents( $pipes[2] );
		fclose( $pipes[2] );
		$rtn = proc_close( $process );

		return [
			'stdout' => $stdout,
			'stderr' => $stderr,
			'return' => $rtn
		];
	}
}