<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Judge\Models\Solution;
use Laracasts\TestDummy\Factory;

class DbSolutionPackageFactoryTest extends DbTestCase
{
    public function testBuildZip()
    {
        // make the folders needed
        File::makeDirectory(storage_path() . '/solution_code', 511, false, true);

        // make the files needed
        File::put(storage_path() . '/solution_code/CODE', 'CODE');

        // stub the database records
        $problem = Factory::create('problem', [
            'judging_input' => 'INPUT',
            'judging_output' => 'OUTPUT'
        ]);
        $solution = Factory::create('solution', [
            'solution_code' => 'CODE',
            'problem_id' => $problem->id
        ]);

        // run the zip building process
        $factory = App::make('Judge\Factories\SolutionPackageFactory');
        $factory->setSolution($solution);
        $factory->buildZip();

        $zip = new ZipArchive();
        $zip->open($factory->getPath());

        $this->assertEquals(3, $zip->numFiles);
        $this->assertEquals('CODE', $zip->getFromIndex(0));
        $this->assertEquals('INPUT', $zip->getFromIndex(1));
        $this->assertEquals('OUTPUT', $zip->getFromIndex(2));
    }
}
