<?php namespace Judge\Repositories;

use Judge\Repositories\ContestRepository;

class EloquentProblemRepository implements ProblemRepository
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
