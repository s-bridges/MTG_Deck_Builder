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
        // before adding new DOTW, loop through all decks and set all DOTW to 0
        $decks = Deck::where('id', $deck_id)->with('cards')->first();
        //set deck_of_the_week = 0; probably in vue though
        // provide deck number, and change DOTW to 1
        
    }

    public function changeUserType()
    {
        //here is the function to get all users, and then select specific user and give/revoke admin access
    }
}