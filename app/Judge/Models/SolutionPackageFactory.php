<?php namespace Judge\Models;

use \ZipArchive;

class SolutionPackageFactory
{
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
     * Creates a solution package factory
     */
    public function __construct(ZipArchive $zip)
    {
        $this->zip_file = $zip;
    }

    public function setSolution(Solution $s)
    {
        $this->solution = $s;
        $this->buildZip();
    }

    /**
     * Opens the zip file for writing
     */
    public function openZip()
    {
        // TODO: refactor this to not be a hard coded directory
        $this->zip_path = '/tmp/solution_' . $this->solution->id . '_' . time() . '.zip';

        // open, checking for errors
        $open_result = $this->zip_file->open($this->zip_path, ZIPARCHIVE::CREATE);

        if ($open_result !== true) {
            $message = "Could not open the zip file at " . $this->zip_path . ": " . $this->zip_file->getStatusString();
            throw new Exception($message);
        }
    }

    /**
     * Closes the zip file, writing it to the temporary directory
     */
    public function closeZip()
    {
        // close, checking for errors
        $close_result = $this->zip_file->close();

        if ($close_result !== true) {
            throw new Exception("Could not close the zip file: " . $this->zip_file->getStatusString());
        }
    }

    /**
     * Builds the zip file, writing the judging package files to teh
     */
    public function buildZip()
    {
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

    public function addToZip($short_path, $full_path)
    {
        $result = $this->zip_file->addFile($full_path, $short_path);
        if ($result !== true) {
            throw new Exception("Could not add the zip file at $full_path, " . $this->zip_file->getStatusString());
        }
    }

    public function getPath()
    {
        return $this->zip_path;
    }
}
