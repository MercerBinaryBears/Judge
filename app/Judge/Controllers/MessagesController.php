<?php namespace Judge\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class MessagesController extends BaseController
{
    public function index()
    {
        $user = Auth::user();

        if ($user->judge) {
            return View::make('messages_judge');
        } elseif ($user->team) {
            return View::make('messages_team');
        }
    }
}
