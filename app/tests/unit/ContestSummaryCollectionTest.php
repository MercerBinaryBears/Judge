<?php

use Judge\Models\ContestSummary;
use Judge\Models\ContestSummaryCollection;

class ContestSummaryCollectionTest extends TestCase
{
    public function testContestRankingSort()
    {
        $second_summary = new ContestSummary();
        $second_summary->problems_solved = 1;
        $first_summary = new ContestSummary();
        $first_summary->problems_solved = 2;

        $contest_summary_collection = new ContestSummaryCollection();
        $contest_summary_collection->push($second_summary);
        $contest_summary_collection->push($first_summary);
        $contest_summary_collection->contestRankingSort();

        $this->assertEquals($first_summary, $contest_summary_collection->first());
    }
}
