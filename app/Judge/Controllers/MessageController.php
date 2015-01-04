<?php namespace Judge\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class MessageController extends BaseController
{
    public function index()
    {
        $user = Auth::user();

        if ($user->judge || $user->admin) {
            return View::make('Messages.judge');
        } elseif ($user->team) {
            return View::make('Messages.team');
        }
    }
}
