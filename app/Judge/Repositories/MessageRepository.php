<?php namespace Judge\Repositories;

use Judge\Models\User;
use Judge\Models\Message;

class MessageRepository
{
    public function allGlobal()
    {
        return Message::whereIsGlobal(true)
            ->get();
    }

    public function unresponded()
    {
        return Message::whereIsGlobal(false)
            ->whereIsNull('responder_id')
            ->get();
    }
}
