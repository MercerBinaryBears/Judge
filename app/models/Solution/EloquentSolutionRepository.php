<?php

class EloquentSolutionRepository implements SolutionRepository {
	// TODO: Convert this into an array, instead of stdClass
	public function find($id) {
		return Solution::find($id);
	}	
}
