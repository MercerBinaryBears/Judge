<?php namespace Judge\Repositories;

use Judge\Models\User;
use Judge\Models\Message;

class MessageRepository
{
    public function allGlobal()
    {
        return Message::whereIsGlobal(true)
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public function unresponded()
    {
        return Message::whereIsGlobal(false)
            ->whereResponderId(null)
            ->orderBy('created_at', 'ASC')
            ->get();
    }
}
