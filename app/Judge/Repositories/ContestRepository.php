<?php namespace Judge\Repositories;

use Carbon\Carbon as Carbon;
use Illuminate\Support\Collection;
use Judge\Models\Contest;

class ContestRepository
{
    public function __construct()
    {
        $this->current_contest = null;
    }

    public function problemsForContest(Contest $c = null)
    {
        if ($c == null) {
            $c = $this->firstCurrent();
        }
        if ($c == null) {
            return Collection::make([]);
        }
        return $c->problems()->with('contest')->get();
    }

    public function teamsForContest(Contest $c = null)
    {
        if ($c == null) {
            $c = $this->firstCurrent();
        }
        return $c->users()->whereTeam(1)->get();
    }

    public function currentContests()
    {
        return Contest::with('problems')
            ->where('starts_at', '<=', Carbon::now()->format('Y-m-d H:i:s'))
            ->orderBy('starts_at', 'desc')->get();
    }
    
    public function firstCurrent()
    {
        if (!$this->current_contest) {
            $this->current_contest = $this->currentContests()->first();
        }

        return $this->current_contest;
    }
}
