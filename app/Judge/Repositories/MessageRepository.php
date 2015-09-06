<?php namespace Judge\Repositories;

use Judge\Repositories\ContestRepository;
use Judge\Models\User;
use Judge\Models\Message;

class MessageRepository
{
    public function __construct(ContestRepository $contests)
    {
        $this->contests = $contests;
    }

    protected function resolveContest(Contest $contest = null) {
        if($contest) {
            return $contest;
        }

        return $this->contests->firstCurrent();
    }

    public function allGlobal(Contest $contest = null)
    {
        $contest = $this->resolveContest($contest);

        return Message::whereIsGlobal(true)
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public function unresponded(Contest $contest = null)
    {
        $contest = $this->resolveContest($contest);

        return Message::whereIsGlobal(false)
            ->whereResponderId(null)
            ->orderBy('created_at', 'ASC')
            ->get();
    }
}
