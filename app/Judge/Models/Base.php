<?php namespace Judge\Models;

use \LaravelBook\Ardent\Ardent as Ardent;

// a base class for all models
class Base extends Ardent
{

    /**
     * A method for processing uploads. Reads an uploaded and assigns class properties automagically
     *
     * @param string $input_field The input field name that points to the uploaded file
     * @param string $path_attribute The name of the model's attribute to store the file's new location on disk
     * @param string $original_name_attribute The name of the model's attribute to store the client's original name for the file
     * @param string $extension_attribute The model's attribute to store the file's extension
     * @return bool A boolean indicating success or failure
     */
    public function processUpload($input_field, $path_attribute, $original_name_attribute, $extension_attribute)
    {
        /**
         * Pulls the Symfony\Component\HttpFoundation\File\UploadedFile from Post input. If the file
         * doesn't exists, we return false, indicating a failure
         */
        $file = Input::file($input_field);
        if ($file == null) {
            return false;
        }

        // Generate a new path for the string, based on a random string.
        $this->$path_attribute = $this->generateRandomString();

        // If the function's caller passed an $original_name_attribute, populate it on the model for them
        if ($original_name_attribute != null) {
            $this->$original_name_attribute = $file->getClientOriginalName();
        }

        // If the function's caller passed an $extension_attribute, populate it on the model for them
        if ($extension_attribute != null) {
            $split = explode('.', $file->getClientOriginalName());
            $this->$extension_attribute = array_pop($split);
        }

        // We now move the file to the random path we generated.
        // We'll store the file in the storage directory of the app, within a subdirectory prefixed
        // by the $path_attribute. Perhaps there is a better way?
        $file->move($this->getDirectoryForResource($path_attribute), $this->$path_attribute);

        return true;
    }

    /**
     * Generates a random path-safe string of a specified length.
     *
     * @param int $length An optional string length.
     * @return string A nice random string of the length you provided
     */
    public function generateRandomString($length = 64)
    {
        // some path safe characters
        $abc = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_-';

        // clamp the $length to positive, if there was any weird problem...
        if ($length < 0) {
            $length = 64;
        }

        // concatenate the string with itself a couple times, shuffle it and take
        // a substring of the required length. If the number is larger than the
        // length of the string, substr will clip it.
        return substr(str_shuffle($abc . $abc . $abc . $abc), 0, $length);
    }

    /**
     * Builds the directory name for a downloadable resource.
     *
     * @param string $path_attribute The attribute on the model to build a download directory for
     * @return string a fully qualified directory for this resource
     */
    public function getDirectoryForResource($path_attribute)
    {
        return storage_path() . "/$path_attribute/";
    }

    /**
     * Gets the full solution path for an ALREADY existing resource
     * @param string $path_attribute The attribute on the model to build a download directory fo
     * @return string the fully qualified path for this resource
     */
    public function getPathForResource($path_attribute)
    {
        return $this->getDirectoryForResource($path_attribute) . $this->$path_attribute;
    }
}
