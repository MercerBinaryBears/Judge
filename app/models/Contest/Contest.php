<?php

use Carbon\Carbon as Carbon;

class Contest extends Base {
    /**
     * Validation rules for a contest
     */
    public static $rules = array(
        'name' => 'required',
        'starts_at' => 'required',
        );

    /**
     * Gets the problems associated with a contest
     */
    public function problems() {
        return $this->hasMany('Problem');
    }

    /**
     * Gets the users associated with a contest
     */
    public function users() {
        return $this->belongsToMany('User');
    }

    /**
     * A scope for the contest table that provides only
     * The "current" contests, i.e. ones that have already started
     * Perhaps in the future, we should also check the end time, and make
     * a contest inactive if its been x seconds after the end time.
     */
    public function scopeCurrent($query) {
        return $query->where('starts_at', '<=', Carbon::now()->format('Y-m-d H:i:s'))
            ->orderBy('starts_at', 'desc');
    }
}
