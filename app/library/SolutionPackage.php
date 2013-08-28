<?php

class SolutionPackage {

	/**
	 * The path to the temporary zip file
	 *
	 * @var $zip_path
	 */
	protected $zip_path;

	/**
	 * The zip file with the solution package in it
	 *
	 * @var $zip_file
	 */
	protected $zip_file;

	/**
	 * The solution that this package is for
	 *
	 * @var $solution
	 */
	protected $solution;


	/**
	 * Creates a solution package for a file
	 */
	public function __construct(Solution $s) {
		$this->solution = $s;

		$this->zip_file = new ZipArchive();

		$this->buildZip();
	}

	/**
	 * Opens the zip file for writing
	 */
	protected function openZip() {

		// TODO: refactor this to not be a hard coded directory
		$this->zip_path = '/tmp/solution_' . $this->solution->id . '_' . time() . '.zip';

		// open, checking for errors
		$open_result = $this->zip_file->open($this->zip_path, ZIPARCHIVE::CREATE);

		if($open_result !== true) {
			throw new Exception("Could not open the zip file at " . $this->zip_path . ": " . $this->zipStatusString($close_result));
		}

	}

	/**
	 * Closes the zip file, writing it to the temporary directory
	 */
	protected function closeZip() {
		// close, checking for errors
		$close_result = $this->zip_file->close();

		if($close_result !== true) {
			throw new Exception("Could not close the zip file: " . $this->zipStatusString($close_result));
		}
	}

	/**
	 * Builds the zip file, writing the judging package files to teh
	 */
	protected function buildZip() {
		// open the zip file
		$this->openZip();

		// add the solution code
		$solution_full_path = $this->solution->getPathForResource('solution_code');
		$this->addToZip($this->solution->solution_filename, $solution_full_path);

		// add the judging input
		$judging_input_full_path = $this->solution->problem->getPathForResource('judging_input');
		$this->addToZip('judge.in', $judging_input_full_path);

		// add the judging output
		$judging_output_full_path = $this->solution->problem->getPathForResource('judging_output');
		$this->addToZip('judge.out', $judging_output_full_path);

		// close the file
		$this->closeZip();
	}

	protected function addToZip($short_path, $full_path) {
		$result = $this->zip_file->addFile($full_path, $short_path);
		if($result !== true) {
			throw new Exception("Could not add the zip file at $full_path, " . $this->zipStatusString($result));
		}
	}

	public function getPath() {
		return $this->zip_path;
	}

	/**
	 * Converts a zip file status code to a string representation
	 *
	 * @param $status A Zip Status code
	 */
	private function zipStatusString( $status )
	{
		switch( (int) $status )
		{
			case ZipArchive::ER_OK           : return 'N No error';
			case ZipArchive::ER_MULTIDISK    : return 'N Multi-disk zip archives not supported';
			case ZipArchive::ER_RENAME       : return 'S Renaming temporary file failed';
			case ZipArchive::ER_CLOSE        : return 'S Closing zip archive failed';
			case ZipArchive::ER_SEEK         : return 'S Seek error';
			case ZipArchive::ER_READ         : return 'S Read error';
			case ZipArchive::ER_WRITE        : return 'S Write error';
			case ZipArchive::ER_CRC          : return 'N CRC error';
			case ZipArchive::ER_ZIPCLOSED    : return 'N Containing zip archive was closed';
			case ZipArchive::ER_NOENT        : return 'N No such file';
			case ZipArchive::ER_EXISTS       : return 'N File already exists';
			case ZipArchive::ER_OPEN         : return 'S Can\'t open file';
			case ZipArchive::ER_TMPOPEN      : return 'S Failure to create temporary file';
			case ZipArchive::ER_ZLIB         : return 'Z Zlib error';
			case ZipArchive::ER_MEMORY       : return 'N Malloc failure';
			case ZipArchive::ER_CHANGED      : return 'N Entry has been changed';
			case ZipArchive::ER_COMPNOTSUPP  : return 'N Compression method not supported';
			case ZipArchive::ER_EOF          : return 'N Premature EOF';
			case ZipArchive::ER_INVAL        : return 'N Invalid argument';
			case ZipArchive::ER_NOZIP        : return 'N Not a zip archive';
			case ZipArchive::ER_INTERNAL     : return 'N Internal error';
			case ZipArchive::ER_INCONS       : return 'N Zip archive inconsistent';
			case ZipArchive::ER_REMOVE       : return 'S Can\'t remove file';
			case ZipArchive::ER_DELETED      : return 'N Entry has been deleted';

			default: return sprintf('Unknown status %s', $status );
		}
	}
}