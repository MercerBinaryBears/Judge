<?php
use \LaravelBook\Ardent\Ardent as Ardent;


// a base class for all models
class Base extends Ardent {
	public function processUpload($input_field, $path_attribute, $original_name_attribute, $extension_attribute) {
		$file = Input::file($input_field);
		if($file == null) {
			return false;
		}

		// fill out the fields
		$this->$path_attribute = $this->generateRandomPath();
		if($original_name_attribute != null) {
			$this->$original_name_attribute = $file->getClientOriginalName();
		}
		if($extension_attribute != null) {
			$split = explode('.', $file->getClientOriginalName());
			$this->$extension_attribute = array_pop($split);
		}

		// now move the file for safe keeping
		$file->move(storage_path() . "/$path_attribute/", $this->$path_attribute);

		return true;
	}

	protected function generateRandomPath($length=64) {
		$abc = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_';
		return substr(str_shuffle($abc . $abc . $abc . $abc), 0, $length);
	}
}