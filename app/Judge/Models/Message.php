<?php namespace Judge\Models;

use Judge\Models\Base;

class Message extends Base
{
    public static $rules = array(
        'contest_id' => 'required',
        'sender_id' => 'required',
        'text' => 'required'
    );

    protected $guarded = array('id', 'created_at', 'updated_at');

    /**
     * Gets the contest associated with this message
     */
    public function contest()
    {
        return $this->belongsTo('Judge\Models\Contest');
    }

    /**
     * Gets the problem associated with this message
     */
    public function problem()
    {
        return $this->belongsTo('Judge\Models\Problem');
    }

    /**
     * Gets the sender of this message
     */
    public function sender()
    {
        return $this->belongsTo('Judge\Models\User', 'sender_id');
    }

    /**
     * Gets the responder/recipient to this message
     */
    public function responder()
    {
        return $this->belongsTo('Judge\Models\User', 'responder_id');
    }

    /**
     * Gets the response to this message
     */
    public function getResponseTextAttribute()
    {
        return nl2br($this->attributes['response_text']);
    }

    /**
     * Gets the text of this message
     */
    public function getTextAttribute()
    {
        return nl2br($this->attributes['text']);
    }
}
