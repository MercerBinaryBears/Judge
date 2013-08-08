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
	public static function unpackFile($tmp_path, $delete = false, $original_name = null) {
		if($original_name == null) {
			$original_name = $tmp_path;
		}
		$split_name = explode('.', $original_name);
		$extension = array_pop($split_name);

		// check that the file is even there
		if(!file_exists($tmp_path)) {
			return array($original_name, $extension, null, $tmp_path);
		}

		$file_contents = file_get_contents($tmp_path);
		if($delete) {
			unlink($tmp_path);
		}

		return array($original_name, $extension, $file_contents, $tmp_path);
	}

	/**
	 * take an attribute name $attribute='solution_code'
	 * Get the full path name from Input::file($attribute)->getRealPath()
	 * Assign that to the passed attribute. Assign the original extension to a passed
	 * variable: $extension_attribute, as well as the client name: $original_filename_attribute
	 * Extract the extension split into a single testable method.
	 * Make sure to delete the uploaded file after reading
	 */
	public function readFile($file_contents_attribute, $original_filename_attribute = null, $extension_attribute = null) {
		$file = Input::file($file_contents_attribute);

		$this->$file_contents_attribute = file_get_contents($file->getRealPath());

		if($original_filename_attribute != null) {
			$this->$original_filename_attribute = $file->getClientOriginalName();
		}

		if($extension_attribute != null) {
			$this->$extension_attribute = $file->getExtension();
		}
	}
}