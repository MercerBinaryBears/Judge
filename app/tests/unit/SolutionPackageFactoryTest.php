<?php

use Judge\Models\Solution;
use Judge\Models\SolutionPackageFactory;

class SolutionPackageFactoryTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->zip_file = Mockery::mock('\ZipArchive');
        $this->solution = Mockery::mock('Judge\Models\Solution');
        $this->factory = new SolutionPackageFactory($this->zip_file);
        $this->factory->setSolution($this->solution);
    }

    public function testSetSolution()
    {
        // Make sure that the setter passes the factory back, for method chaining
        $this->assertEquals($this->factory, $this->factory->setSolution($this->solution));
    }

    /**
     * @expectedException Judge\Exceptions\ZipException
     */
    public function testOpenZipWithErrors()
    {
        $this->solution->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);
        $this->zip_file->shouldReceive('open')->once()->andReturn(false);
        $this->zip_file->shouldReceive('getStatusString')->once()->andReturn('');
        $this->factory->openZip();
    }

    public function testCloseZipWithNoErrors()
    {
        $this->zip_file->shouldReceive('close')->once()->andReturn(true);
        $this->factory->closeZip();
    }

    /**
     * @expectedException Judge\Exceptions\ZipException
     */
    public function testCloseZipWithErrors()
    {
        $this->zip_file->shouldReceive('close')->once()->andReturn(false);
        $this->zip_file->shouldReceive('getStatusString')->once()->andReturn('');
        $this->factory->closeZip();
    }

    /**
     * @expectedException Judge\Exceptions\ZipException
     */
    public function testAddToZipWithErrors()
    {
        $this->zip_file->shouldReceive('addFile')->once()->with('FULL_PATH', 'SHORT_PATH')->once()->andReturn(false);
        $this->zip_file->shouldReceive('getStatusString')->once()->andReturn('');
        $this->factory->addToZip('SHORT_PATH', 'FULL_PATH');
    }

    public function testAddToZipWithNoErrors()
    {
        $this->zip_file->shouldReceive('addFile')->once()->with('FULL_PATH', 'SHORT_PATH')->once()->andReturn(true);
        $this->factory->addToZip('SHORT_PATH', 'FULL_PATH');
    }

    public function testGetPath()
    {
        $this->solution->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);
        $this->zip_file->shouldReceive('open')->once()->andReturn(true);
        $this->factory->openZip();

        $result = $this->factory->getPath();
        $this->assertNotEquals('', $result);
    }
}
