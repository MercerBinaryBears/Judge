<?php namespace Judge\Repositories;

use Illuminate\Support\Collection;
use Judge\Repositories\ContestRepository;
use Judge\Models\Contest;
use Judge\Models\User;

class UserRepository
{
    public function __construct(ContestRepository $contests)
    {
        $this->contests = $contests;
    }
    
    public function forContest(Contest $c = null)
    {
        if ($c == null) {
            $c = $this->contests->firstCurrent();
        }

        if ($c == null) {
            return Collection::make([]);
        }

        return $c->users;
    }
}
