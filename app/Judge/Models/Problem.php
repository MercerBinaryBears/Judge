<?php namespace Judge\Models;

class Problem extends Base
{
    protected $fillable = ['name', 'contest_id', 'judging_input', 'judging_output', 'difficulty'];

    public static $rules = array(
        'name' => 'required',
        'contest_id' => 'required',
        'judging_input' => 'required',
        'judging_output' => 'required',
        'difficulty' => 'integer|min:1|max:5'
    );

    /**
     * Gets the contest that this problem belongs to
     */
    public function contest()
    {
        return $this->belongsTo('Judge\Models\Contest');
    }

    /**
     * Gets all solutions for this problem
     */
    public function solutions()
    {
        return $this->hasMany('Judge\Models\Solution');
    }

    public function tags()
    {
        return $this->belongsToMany('Judge\Models\Tag');
    }

    /**
     * Gets the problems for the current contest
     */
    public function scopeForCurrentContest($query)
    {
        $contests = Contest::current()->first();
        if ($contests == null) {
            return Problem::where('id', null);
        }
        return $query->where('contest_id', $contests->id)->orderBy('created_at');
    }
}
