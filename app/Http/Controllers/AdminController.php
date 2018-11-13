<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Deck;
use App\Card;
use Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function admin()
    {
        // get all users and decks if they have them, as well as a total count for decks a user has
        $users = User::with('decks')->withCount('decks')->get();
        $data = collect(['users' => $users, 'admin' => Auth::user()]);
        return view('admin-page', ['data' => $data]);
    }

    public function deckOfTheWeekSave()
    {

    }
}