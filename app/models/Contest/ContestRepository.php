<?php

interface ContestRepository {
    /**
     * Returns all problems associated with the passed contest
     * If the contest is null, it defaults to the first current
     *
     * @param Contest $c
     * @return Collection A laravel collection of Problem instances
     */
    public function problemsForContest(Contest $c = null);

    /**
     * Returns all users associated with the passed contest
     * If the contest is null, it defaults to the first current
     *
     * @param Contest $c
     * @return Collection a laravel collection of User instances
     */
    public function teamsForContest(Contest $c = null);

    /**
     * Returns all contests considered "current", i.e. their start
     * time is in the past.
     *
     * @return Collection a collection of contest instances
     */
    public function currentContests();

    /**
     * Returns the first "current" contest"
     */
    public function firstCurrent();
}
