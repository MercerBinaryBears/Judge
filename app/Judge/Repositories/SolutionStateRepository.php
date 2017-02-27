<?php namespace Judge\Repositories;

use Judge\Models\SolutionState;

class SolutionStateRepository
{
    public function __construct()
    {
        $this->pending_id = null;
        $this->correct_id = null;
    }

    /**
     * Gets the solution state in the database representing a
     * solution still being judged
     */
    public function firstPendingId()
    {
        if ($this->pending_id === null) {
            $this->pending_id = SolutionState::where('pending', true)
                ->firstOrFail()
                ->id;
        }

        return $this->pending_id;
    }

    public function all()
    {
        return SolutionState::all();
    }

    public function firstCorrectId()
    {
        if ($this->correct_id === null) {
            $this->correct_id = SolutionState::where('name', 'LIKE', '%Correct%')
                ->firstOrFail()
                ->id;
        }

        return $this->correct_id;
    }
}
