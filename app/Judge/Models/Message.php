<?php namespace Judge\Models;

use Judge\Models\Base;

class Message extends Base
{
    public static $rules = array(
        'sender_id' => 'required',
        'text' => 'required'
    );

    protected $guarded = array('id', 'created_at', 'updated_at');

    public function problem()
    {
        return $this->belongsTo('Judge\Models\Problem');
    }

    public function sender()
    {
        return $this->belongsTo('Judge\Models\User', 'sender_id');
    }

    public function responder()
    {
        return $this->belongsTo('Judge\Models\User', 'responder_id');
    }
}
