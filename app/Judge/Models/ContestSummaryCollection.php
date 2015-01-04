<?php namespace Judge\Models;

use \Iterator;

class ContestSummaryCollection implements Iterator
{
    public function __construct()
    {
        $this->summaries = array();
    }

    public function add(ContestSummary $c)
    {
        $this->summaries[] = $c;
    }

    public function rewind()
    {
        $this->k = 0;
        usort($this->summaries, array('Judge\Models\ContestSummary\ContestSummary', 'compare'));
    }

    public function current()
    {
        return $this->summaries[$this->k];
    }

    public function key()
    {
        return $this->k;
    }

    public function next()
    {
        ++$this->k;
    }

    public function valid()
    {
        return $this->k < count($this->summaries);
    }
}
