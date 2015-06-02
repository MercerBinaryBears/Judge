<?php

use Laracasts\TestDummy\Factory;
use Judge\Controllers\SolutionController;
use Judge\Models\SolutionState;

class DbSolutionControllerTest extends DbTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->controller = App::make('Judge\Controllers\SolutionController');
    }

    public function testIndexForTeam()
    {
        $team = Factory::create('team');
        Auth::shouldReceive('user')->zeroOrMoreTimes()->andReturn($team);

        Factory::create('solution', [
            'user_id' => $team->id
        ]);

        $view = $this->controller->index();

        $this->assertCount(1, $view['solutions']);
        $this->assertCount(1, $view['problems']);
        $this->assertNotEquals(0, $view['languages']->count());
    }

    public function testIndexForJudge()
    {
        $judge = Factory::create('judge');
        Auth::shouldReceive('user')->zeroOrMoreTimes()->andReturn($judge);

        Factory::create('solution', [
            'claiming_judge_id' => null,
            'solution_state_id' => SolutionState::wherePending(true)->first()->id
        ]);

        $view = $this->controller->index();

        $this->assertCount(1, $view['unjudged_solutions']);
        $this->assertCount(0, $view['claimed_solutions']);
    }

    public function testEditWithClaimedSolution()
    {
        $solution = Factory::create('solution');
        $response = $this->controller->edit($solution->id);
        $this->assertInstanceOf('Illuminate\Http\RedirectResponse', $response);
    }

    public function testEditWithUnclaimedSolution()
    {
        $judge = Factory::create('judge');
        Auth::shouldReceive('user')->zeroOrMoreTimes()->andReturn($judge);
        $solution = Factory::create('solution', ['claiming_judge_id' => null]);
        $response = $this->controller->edit($solution->id);
        $this->assertInstanceOf('Illuminate\View\View', $response);
    }

    public function testStoreWithMissingData()
    {
        $team = Factory::create('team');
        Auth::shouldReceive('id')->zeroOrMoreTimes()->andReturn($team->id);
        
        $solution_code = Mockery::mock('Symfony\Component\HttpFoundation\File\UploadedFile');
        $this->action('POST', 'Judge\Controllers\SolutionController@store', [ ], ['solution_code' => $solution_code]);

        $this->assertTrue(Session::has('error'));
    }

    public function testUpdateWithWrongOwner()
    {
        $solution = Factory::create('solution');
        $judge = Factory::create('judge');
        Auth::shouldReceive('user')->zeroOrMoreTimes()->andReturn($judge);
        Auth::shouldReceive('id')->zeroOrMoreTimes()->andReturn($judge);

        $this->controller->update($solution->id);
        $this->assertTrue(Session::has('error'));
    }

    public function testUpdateWithInvalidSolutionStateId()
    {
        $solution = Factory::create('solution');
        $judge = $solution->claiming_judge;
        Auth::shouldReceive('user')->zeroOrMoreTimes()->andReturn($judge);
        Auth::shouldReceive('id')->zeroOrMoreTimes()->andReturn($judge);

        // Forgot a solution state
        $this->action('POST', 'Judge\Controllers\SolutionController@update', [$solution->id]);

        $this->assertTrue(Session::has('error'));
    }

    public function testUnclaimWithWrongJudge()
    {
        $solution = Factory::create('solution');
        $judge = Factory::create('judge');
        Auth::shouldReceive('user')->zeroOrMoreTimes()->andReturn(null);

        $this->action('POST', 'Judge\Controllers\SolutionController@unclaim', [$solution->id]);

        $this->assertTrue(Session::has('error'));
    }

    public function testUnclaimWithCorrectJudge()
    {
        $solution = Factory::create('solution', ['claiming_judge_id' => null]);
        $judge = Factory::create('judge');
        Auth::shouldReceive('id')->zeroOrMoreTimes()->andReturn($judge->id);
        Auth::shouldReceive('user')->zeroOrMoreTimes()->andReturn($judge);

        $this->action('POST', 'Judge\Controllers\SolutionController@unclaim', [$solution->id]);

        $this->assertFalse(Session::has('error'));
    }

    public function testPackageDownload()
    {
        $solution = Factory::create('solution');

        File::put('/tmp/downloadtest', 'TEST');
        $factory = Mockery::mock();
        $factory->shouldReceive('setSolution')->once();
        $factory->shouldReceive('buildZip')->once();
        $factory->shouldReceive('getPath')->once()->andReturn('/tmp/downloadtest');
        App::shouldReceive('make')->once()->with('Judge\Factories\SolutionPackageFactory')->andReturn($factory);

        $result = $this->action('GET', 'Judge\Controllers\SolutionController@package', [$solution->id]);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\BinaryFileResponse', $result);
    }
}
