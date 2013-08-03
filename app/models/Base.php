<?php
use \LaravelBook\Ardent\Ardent as Ardent;


// a base class for all models
class Base extends Ardent {

	/**
	 * Reads an uploaded file and returns the client's original name,
	 * extension, file contents as a string, and the temporary upload
	 * path of the file.
	 * @param $tmp_path The path to the uploaded file. This is the original client's
	 * name, preserved by the admin
	 * @param $delete A boolean to delete the file after reading
	 * @param $original_name The original name of the file. If not provided,
	 * The function will assume that it's the tmp_path's file name
	 */
	public function unpackFile($tmp_path, $delete = false, $original_name = null) {
		if($original_name == null) {
			$original_name = $tmp_path;
		}
		$split_name = explode('.', $original_name);
		$extension = array_pop($split_name);
		$file_contents = file_get_contents($tmp_path);
		if($delete) {
			unlink($tmp_path);
		}

		return array($original_name, $extension, $file_contents, $tmp_path);
	}
}