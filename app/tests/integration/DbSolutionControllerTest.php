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
}
