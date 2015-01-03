<?php namespace Judge\Models\SolutionState;

interface SolutionStateRepository
{
    public function firstPendingId();
    public function firstCorrectId();
    public function all();
}
