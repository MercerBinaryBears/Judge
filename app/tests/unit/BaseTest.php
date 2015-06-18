<?php

use Judge\Models\Base;

class BaseModelTest extends TestCase
{
    protected function assertStringContains($haystack, $needle)
    {
        $position = strpos($haystack, $needle);
        $found = ($position !== false);
        $this->assertTrue($found);
    }

    public function testProcessUploadWithNoFile()
    {
        $model = new Base();

        Input::shouldReceive('file')->once()->with('INPUT_FIELD')->andReturn(null);

        $result = $model->processUpload('INPUT_FIELD', 'PATH', 'ORIGINAL_NAME', 'EXTENSION');
        $this->assertFalse($result);
    }

    public function testProcessUploadWithFile()
    {
        $file = Mockery::mock();
        $file->shouldReceive('getClientOriginalName')->twice()->andReturn('ORIGINAL.NAME');
        $file->shouldReceive('move')->once()->with(Mockery::type('string'), Mockery::type('string'));
        Input::shouldReceive('file')->once()->with('INPUT_FIELD')->andReturn($file);

        $model = new Base();
        $model->PATH = 'PATH';
        $result = $model->processUpload('INPUT_FIELD', 'PATH', 'ORIGINAL', 'EXTENSION');
        $this->assertTrue($result);
        $this->assertEquals('ORIGINAL.NAME', $model->ORIGINAL);
        $this->assertEquals('NAME', $model->EXTENSION);
    }

    public function testGenerateRandomStringWithoutLength()
    {
        $model = new Base();
        $result = $model->generateRandomString();
        $this->assertEquals(64, strlen($result));
        $new_result = $model->generateRandomString();
        $this->assertNotEquals($result, $new_result);
    }

    public function testGenerateRandomStringWithLength()
    {
        $model = new Base();
        $result = $model->generateRandomString(10);
        $this->assertEquals(10, strlen($result));
    }

    public function testGenerateRandomStringWithInvalidLength()
    {
        $model = new Base();
        $result = $model->generateRandomString(-1);
        $this->assertEquals(64, strlen($result));
    }

    public function testGetDirectoryForResource()
    {
        $model = new Base();
        $path = $model->getDirectoryForResource('MYPATH');
        $this->assertStringContains($path, 'MYPATH');
    }

    public function testGetPathForResource()
    {
        $model = new Base();
        $model->resource = 'x';
        $path = $model->getPathForResource('resource');
        $this->assertStringContains($path, 'resource/x');
    }
}
