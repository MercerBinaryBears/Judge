<?php

class UnpackFile extends TestCase {
	private static $ext = 'csv';
	private static $tmp_path = '/tmp/temporary';
	private static $original = 'hello';
	private static $contents = 'Hello World';

	public function setUp() {
		parent::setUp();

		// create tmp file, then read run the function under test
		$this->createTmpFile();
		$this->runUnpackFile($this->getFullTmp(), true, $this->getFullOriginal());
	}

	private function createTmpFile() {
		$fh = fopen($this->getFullTmp(), 'w');
		fwrite($fh, static::$contents);
		fclose($fh);
	}

	private function runUnpackFile($tmp_file, $delete=true, $original=null) {
		if(!isset($tmp_file)) {
			$tmp_file = $this->getFullTmp();
		}

		list($original, $ext, $file_contents, $tmp_path) = Base::unpackFile($tmp_file, $delete, $original);
		$this->actual_original_name = $original;
		$this->actual_extension = $ext;
		$this->actual_file_contents = $file_contents;
		$this->actual_tmp_path = $tmp_path;
	}

	public function tearDown() {
		if(file_exists(static::$tmp_path)) {
			unlink(static::$tmp_path);
		}
	}

	private function getFullTmp() {
		return static::$tmp_path . '.' . static::$ext;
	}

	private function getFullOriginal() {
		return static::$original . '.' . static::$ext;
	}

	public function testReadsFileContents() {
		$this->assertEquals(static::$contents, $this->actual_file_contents);
	}

	public function testOriginalNamePassesThrough() {
		$this->assertEquals($this->getFullOriginal(), $this->actual_original_name);
	}

	public function testTmpNamePassesThrough() {
		$this->assertEquals($this->getFullTmp(), $this->actual_tmp_path);
	}

	public function testUnsetOriginalGivesTmp() {
		// run unpack file manually
		$this->runUnpackFile($this->getFullTmp());

		$this->assertEquals($this->getFullTmp(), $this->actual_original_name);
	}

	public function testExtractExtension() {
		$this->assertEquals(static::$ext, $this->actual_extension);
	}

	public function testNonexistentFileGivesNullContents() {
		// run unpack file manually
		// TODO: make this not repeated code
		list($original, $ext, $file_contents, $tmp_path) = Base::unpackFile(
			'/tmp/BOGUS.blah',
			true
			);
		$this->actual_file_contents = $file_contents;
		$this->assertNull($this->actual_file_contents);
	}

	public function testFilenameWithNoExtensionDoesntErr() {
		// run unpack file manually
		// TODO: make this not repeated code
		list($original, $ext, $file_contents, $tmp_path) = Base::unpackFile(
			'/tmp/BOGUS',
			true
			);
		// if we got here, there's no error...
		$this->assertTrue(true);
	}

	public function testDeleteFlagToTrueDeletesTheFile() {
		$this->assertFileNotExists($this->getFullTmp());
	}

	public function testDeleteFlagToFalsePreservesTheFile() {
		$this->tearDown();

		// rerun unpack and check file exists
		$this->createTmpFile();
		$this->runUnpackFile($this->getFullTmp(), false, $this->getFullOriginal());
		$this->assertFileExists($this->getFullTmp());

	}
}