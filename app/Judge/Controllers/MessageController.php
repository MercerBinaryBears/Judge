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

        if ($user->judge || $user->admin) {
            return View::make('Messages.judge');
        } elseif ($user->team) {
            return View::make('Messages.team')
                ->withProblems($this->problems->forContest())
                ->withMessages(Auth::user()->sent_messages)
                ->withGlobalMessages($this->messages->allGlobal());
        }
    }

    public function store()
    {
        $defaults = ['text' => '', 'sender_id' => Auth::user()->id];

        Message::create(array_merge($defaults, array_filter(Input::all())));

        return Redirect::to('/messages');
    }
}
