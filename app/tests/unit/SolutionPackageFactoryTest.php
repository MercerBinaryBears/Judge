<?php

use Judge\Models\Solution;
use Judge\Models\SolutionPackageFactory;

class SolutionPackageFactoryTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->zip_file = Mockery::mock('\ZipArchive');
        $this->solution = new Solution();
        $this->factory = new SolutionPackageFactory($this->zip_file);
        $this->factory->setSolution($this->solution);
    }

    /**
     * @expectedException Judge\Exceptions\ZipException
     */
    public function testOpenZipWithErrors()
    {
        $this->zip_file->shouldReceive('open')->once()->andReturn(false);
        $this->zip_file->shouldReceive('getStatusString')->once()->andReturn('');
        $this->factory->openZip();
    }
}
