<?php namespace Judge\Repositories;

use Judge\Repositories\ContestRepository;
use Judge\Models\Contest;
use Judge\Models\Problem;

class ProblemRepository
{
    public function __construct(ContestRepository $contests)
    {
        $this->contests = $contests;
    }
    
    public function forContest(Contest $c = null)
    {
        if ($c == null) {
            $c = $this->contests->firstCurrent();
        }
        return Problem::whereContestId($c->id)->get();
    }
}
