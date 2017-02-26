<?php namespace Judge\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

use Judge\Models\Message;

class MessageController extends BaseController
{
    public function index()
    {
        $user = Auth::user();
        $contest_problems = $this->problems->forContest();

        if ($user->judge || $user->admin) {
            return View::make('Messages.judge')
                ->withProblems($contest_problems)
                ->withTeams($this->users->forContest())
                ->withMyMessages($this->messages->from($user))
                ->withUnrespondedMessages($this->messages->unresponded());
        }

        $sent_messages = $user->sentMessages()->orderBy('created_at', 'DESC')->get();

        return View::make('Messages.team')
            ->withProblems($contest_problems)
            ->withMessages($sent_messages)
            ->withGlobalMessages($this->messages->fromJudgeToTeam($user));
    }

    public function store()
    {
        $user = Auth::user();
        $defaults = ['text' => '', 'sender_id' => $user->id, 'contest_id' => $this->contests->firstCurrent()->id];

        $result = Message::create(array_merge($defaults, array_filter(Input::all())));

        if ($result->validationErrors->has('text')) {
            \Flash::warning('Please provide some message text');
        }

        return Redirect::to('/messages');
    }

    public function update($id)
    {
        if (Input::get('response_text') != '') {
            $message = Message::find($id);
            $message->fill(Input::only('responder_id', 'response_text'));
            $message->save();
        } else {
            \Flash::warning('Please provide a response');
        }

        return Redirect::to('/messages');
    }
}
