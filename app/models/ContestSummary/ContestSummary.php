<?php

class ContestSummary {
    /**
     * The user this contest summary represents
     */
    public $user;

    /**
     * The Penalty Points that the user has for this contest
     */
    public $penalty_points;

    /**
     * The number of problems the user has solved
     */
    public $problems_solved;

    /**
     * The array of summaries for each problem
     */
    public $problem_summaries;

    /**
     * The main comparison function for two contest summaries
     */
    public static function compare(ContestSummary $c1, ContestSummary $c2) {
        /*
         * Check problems solved first
         */
        if($c1->problems_solved < $c2->problems_solved) {
            return 1;
        }
        else if($c1->problems_solved > $c2->problems_solved) {
            return -1;
        }

        /*
         * Check penalty points next
         */
        if($c1->penalty_points < $c2->penalty_points) {
            return -1;
        }
        else if($c1->penalty_points > $c2->penalty_points) {
            return 1;
        }

        /*
         * Lastly, sort alphabetically by username
         */
        return strcasecmp($c1->user->username, $c2->user->username);
    }
}
