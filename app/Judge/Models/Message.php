<?php namespace Judge\Models\Message;

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
        return $this->belongsTo('Judge\Models\Problem\Problem');
    }

    public function sender()
    {
        return $this->belongsTo('Judge\Models\User\User', 'sender_id');
    }

    public function responder()
    {
        return $this->belongsTo('Judge\Models\User\User', 'responder_id');
    }
}
