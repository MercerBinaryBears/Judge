<?php

class ReadFileTest extends TestCase {
	private static $file_contents_attribute = 'file_contents';
	private static $original_filename_attribute = 'original_name';
	private static $extension_attribute = 'extension';
	private static $tmp_path = '/tmp/temporary';
	private static $original = 'hello';
	private static $extension = 'csv';
	private static $contents = 'Hello World';

	public function setUp() {
		parent::setUp();

		// create a tmp file
		$this->createTmpFile();
		$this->mockUploads();
	}

	public function tearDown() {
		// delete the temp file
		if(file_exists($this->getFullTmpFilename())) {
			unlink($this->getFullTmpFilename());
		}
	}

	private function createTmpFile() {
		$fh = fopen($this->getFullTmpFilename(), 'w');
		fwrite($fh, static::$contents);
		fclose($fh);
	}

	private function mockUploads() {
		// Mock the Input, so it fakes the file input
		$file = new Symfony\Component\HttpFoundation\File\UploadedFile(
			$this->getFullTmpFilename(), $this->getFullOriginalFilename());
		Input::shouldReceive('file')->with(static::$file_contents_attribute)->once()->andReturn($file);
	}

	private function getFullTmpFilename() {
		return static::$tmp_path . '.' . static::$extension;
	}

	private function getFullOriginalFilename() {
		return static::$original . '.' . static::$extension;
	}

	public function testCorrectFileRead() {
		// assign some variables, since we'll need them for reflection
		$c = static::$file_contents_attribute;
		$o = static::$original_filename_attribute;
		$e = static::$extension_attribute;

		// build the model and read
		$b = new Base();
		$b->readFile($c, $o, $e);

		// make sure that the model has the correct contents
		$this->assertEquals(static::$contents, $b->$c, "File contents were not copied into model: " . $b->$c);
		$this->assertEquals($this->getFullOriginalFilename(), $b->$o, "Original filename did not persist");
		$this->assertEquals(static::$extension, $b->$e, "Extension was not persisted");
	}

	public function testNullableFields() {
		// assign some variables, since we'll need them for reflection
		$c = static::$file_contents_attribute;

		// build the model and read. make sure that this still doesn't err
		$b = new Base();
		$b->readFile($c, null, null);

		// make sure that the model has the correct contents
		$this->assertEquals(static::$contents, $b->$c, "File contents were not copied into model: " . $b->$c);
	}


}