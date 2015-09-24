<?php namespace Judge\Models;

class Tag extends Base
{
    protected $fillable = ['name'];

    public static $rules = [
        'name' => 'required'
    ];

    public function problems()
    {
        return $this->belongsToMany('Judge\Models\Problem');
    }
}
