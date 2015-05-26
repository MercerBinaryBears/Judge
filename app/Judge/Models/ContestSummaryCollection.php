<?php namespace Judge\Models;

use Illuminate\Support\Collection;

class ContestSummaryCollection extends Collection
{
    public function contestRankingSort()
    {
        usort($this->items, array('Judge\Models\ContestSummary', 'compare'));

        return $this;
    }
}
