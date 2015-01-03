<?php

interface ProblemRepository
{
    public function forContest(Contest $c = null);
}
