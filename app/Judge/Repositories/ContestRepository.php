<?php namespace Judge\Repositories;

use Carbon\Carbon as Carbon;

use Judge\Models\Contest;

class ContestRepository
{
    public function problemsForContest(Contest $c = null)
    {
        if ($c == null) {
            $c = $this->firstCurrent();
        }
        return $c->problems;
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
        return Contest::where('starts_at', '<=', Carbon::now()->format('Y-m-d H:i:s'))
            ->orderBy('starts_at', 'desc')->get();
    }
    
    public function firstCurrent()
    {
        return $this->currentContests()->first();
    }
}
