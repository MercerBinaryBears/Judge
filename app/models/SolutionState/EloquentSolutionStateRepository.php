<?php

class EloquentSolutionStateRepository implements SolutionStateRepository {
	/**
	 * Gets the solution state in the database representing a
	 * solution still being judged
	 */
	public function firstPendingId() {
		return SolutionState::where('pending', true)
			->firstOrFail()
			->id;
	}

	public function all() {
		return SolutionState::all();
	}

	public function firstCorrectId() {
		return SolutionState::where('name', 'LIKE', '%Correct%')
			->firstOrFail()
			->id;
	}
}
