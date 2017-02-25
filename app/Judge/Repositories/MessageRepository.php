<?php namespace Judge\Repositories;

use Judge\Repositories\ContestRepository;
use Judge\Models\Contest;
use Judge\Models\User;
use Judge\Models\Message;

class MessageRepository
{
    public function __construct(ContestRepository $contests)
    {
        $this->contests = $contests;
    }

    protected function resolveContest(Contest $contest = null)
    {
        // No contest? try to find one
        if (!$contest) {
            $contest = $this->contests->firstCurrent();
        }

        // Still no contest, just use an empty one, so we can pull an empty id
        if (!$contest) {
            $contest = new Contest();
        }

        return $contest;
    }

    public function allGlobal(Contest $contest = null)
    {
        $contest = $this->resolveContest($contest);

        return Message::whereContestId($contest->id)
            ->join('users as judges', 'judges.id', '=', 'messages.sender_id')
            ->where('judges.judge', '=', true)
            ->select('messages.*')
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public function unresponded(Contest $contest = null)
    {
        $contest = $this->resolveContest($contest);

        return Message::whereIsGlobal(false)
            ->whereResponderId(null)
            ->whereContestId($contest->id)
            ->orderBy('created_at', 'ASC')
            ->get();
    }
}
