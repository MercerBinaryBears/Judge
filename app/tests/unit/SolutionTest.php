<?php

use Illuminate\Database\Eloquent\Collection as Collection;

use Judge\Models\Solution;

class SolutionTest extends TestCase {
	public function setUp() {
		parent::setUp();
	}

	public function mockSolution($methods_to_mock) {
		$this->solution = Mockery::mock("Judge\\Models\\Solution[$methods_to_mock]");
		$this->solution->shouldReceive('save')->andReturn(true);
	}

	/*
	 * An Easy way to mock the auth function
	 */
	public function mockUser($user_id = null, $judge=false, $admin=false) {
		$user = null;
		if($user_id != null) {
			$user = new stdClass;
			$user->id = $user_id;
			$user->judge = $judge;
			$user->admin = $admin;
		}
		Auth::shouldReceive('user')->zeroOrMoreTimes()->andReturn($user);
	}

    public function testProblemRelationship()
    {
        $solution = new Solution();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $solution->problem());
    }

    public function testUserRelationship()
    {
        $solution = new Solution();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $solution->user());
    }

    public function testSolutionStateRelationship()
    {
        $solution = new Solution();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $solution->solutionState());
    }

    public function testClaimingJudge()
    {
        $solution = new Solution();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $solution->claimingJudge());
    }

    public function testLanguage()
    {
        $solution = new Solution();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $solution->language());
    }

	public function testNoLoginNoAlter() {
		$this->mockUser();
		$this->mockSolution('save');

		$this->assertFalse($this->solution->canBeAltered());
	}

	public function testLoginButNotJudgeOrAdmin() {
		$this->mockUser(12);
		$this->mockSolution('save');

		$this->assertFalse($this->solution->canBeAltered());
	}

	public function testJudgesCanAlterUnclaimed() {
		$this->mockUser(12, true);
		$this->mockSolution('save');

		$this->assertTrue($this->solution->canBeAltered());
	}
	
	public function testAdminCanAlterUnclaimed() {
		$this->mockUser(12, false, true);
		$this->mockSolution('save');

		$this->assertTrue($this->solution->canBeAltered());
	}

	public function testClaimedNonownedCannotBeAltered() {
		$this->mockUser(12, true);
		$this->mockSolution('save,ownedByCurrentUser');
		$this->solution->claiming_judge_id = 10;
		$this->solution->shouldReceive('ownedByCurrentUser')->andReturn(false);

		$this->assertFalse($this->solution->canBeAltered());
	}
	
	public function testClaimedOwnedCanBeAltered() {
		$this->mockUser(12, true);
		$this->mockSolution('save,ownedByCurrentUser');
		$this->solution->claiming_judge_id = null;
		$this->solution->shouldReceive('ownedByCurrentUser')->andReturn(true);

		$this->assertTrue($this->solution->canBeAltered());
	}

    public function testOwnedByCurrentUserWithMismatch()
    {
        Auth::shouldReceive('id')->once()->andReturn(1);
        $solution = new Solution(['claiming_judge_id' => 2]);
        $this->assertFalse($solution->ownedByCurrentUser());
    }

    public function testOwnedByCurrentUserWithMatch()
    {
        Auth::shouldReceive('id')->once()->andReturn(1);
        $solution = new Solution(['claiming_judge_id' => 1]);
        $this->assertTrue($solution->ownedByCurrentUser());
    }

	public function testClaimingFailsForNonAlterable() {
		$this->mockUser(12, true);
		$this->mockSolution('save,canBeAltered');
		$this->solution->claiming_judge_id = 20;
		$this->solution->shouldReceive('canBeAltered')->andReturn(false);

		$result = $this->solution->claim();
		
		$this->assertFalse($result);
		$this->assertEquals(20, $this->solution->claiming_judge_id);
	}

	public function testClaimingPassesForAlterable() {
		$this->mockUser(12, true);
		$this->mockSolution('save,canBeAltered');
		$this->solution->claiming_judge_id = null;
		$this->solution->shouldReceive('canBeAltered')->andReturn(true);

		$result = $this->solution->claim();
		
		$this->assertTrue($result);
		$this->assertEquals(12, $this->solution->claiming_judge_id);
	}

	public function testUnclaimingFailsForNonAlterable() {
		$this->mockUser(12, true);
		$this->mockSolution('save,canBeAltered');
		$this->solution->claiming_judge_id = 20;
		$this->solution->shouldReceive('canBeAltered')->andReturn(false);

		$result = $this->solution->unclaim();
		
		$this->assertFalse($result);
		$this->assertEquals(20, $this->solution->claiming_judge_id);
	}

	public function testUnclaimingPassesForAlterable() {
		$this->mockUser(12, true);
		$this->mockSolution('save,canBeAltered');
		$this->solution->claiming_judge_id = 12;
		$this->solution->shouldReceive('canBeAltered')->andReturn(true);

		$result = $this->solution->unclaim();
		
		$this->assertTrue($result);
		$this->assertEquals(null, $this->solution->claiming_judge_id);
	}

	public function testPrettyDiff() {
		$contest = new stdClass;
		$contest->starts_at = '2014-01-01 00:00:00';

		$contest_repo = Mockery::mock()
			->shouldReceive('firstCurrent')
			->once()->andReturn($contest)
			->getMock();

		App::shouldReceive('make')->once()->with('Judge\Repositories\ContestRepository')
			->andReturn($contest_repo);

		$s = new Solution();
		$s->created_at = '2014-01-01 01:02:00';

		$this->assertEquals('62 minutes after contest start',
			$s->submissionPrettyDiff());
	}
}
