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
            return View::make('Messages.judge')
                ->withProblems($this->problems->forContest())
                ->withUnrespondedMessages($this->messages->unresponded());
        }
        
        return View::make('Messages.team')
            ->withProblems($this->problems->forContest())
            ->withMessages(Auth::user()->sent_messages)
            ->withGlobalMessages($this->messages->allGlobal());
    }

    public function store()
    {
        $defaults = ['text' => '', 'sender_id' => Auth::user()->id];

        if (Auth::user()->judge || Auth::user()->admin) {
            $defaults['is_global'] = true;
        }

        Message::create(array_merge($defaults, array_filter(Input::all())));

        return Redirect::to('/messages');
    }

    public function update($id)
    {
        $message = Message::find($id);
        $message->fill(Input::only('responder_id', 'response_text'));
        $message->save();

        return Redirect::to('/messages');
    }
}
