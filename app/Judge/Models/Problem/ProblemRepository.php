<?php namespace Judge\Models\Problem;

interface ProblemRepository
{
    public function forContest(Contest $c = null);
}
