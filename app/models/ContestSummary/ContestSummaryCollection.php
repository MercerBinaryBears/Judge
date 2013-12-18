<?php

class ContestSummaryCollection implements Iterator {
	public function __construct() {
		$this->summaries = array();
	}

	public function add(ContestSummary $c) {
		$this->summaries[] = $c;
	}

	/*
	 * Iterator methods
	 */

	function rewind() {
		$this->k = 0;
		usort($this->summaries, array('ContestSummary', 'compare'));
	}

	function current() {
		return $this->summaries[$this->k];
	}

	function key() {
		return $this->k;
	}

	function next() {
		++$this->k;
	}

	function valid() {
		return $this->k < count($this->summaries);
	}

}
