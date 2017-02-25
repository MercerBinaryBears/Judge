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

    public function fromJudgeToTeam(User $team, Contest $contest = null)
    {
        return Message::fromJudge()
            ->where(function ($query) use ($team) {
                $query->where('responder_id', '=', $team->id)
                    ->orWhere(function ($query) {
                        $query->whereNull('responder_id');
                    });
            })
            ->get();
    }

    public function unresponded(Contest $contest = null)
    {
        $contest = $this->resolveContest($contest);

        return Message::fromTeam()
            ->whereResponderId(null)
            ->whereContestId($contest->id)
            ->orderBy('created_at', 'ASC')
            ->get();
    }

    public function from(User $user, Contest $contest = null)
    {
        $contest = $this->resolveContest($contest);

        return Message::whereContestId($contest->id)
            ->whereSenderId($user->id)
            ->get();
    }
}
