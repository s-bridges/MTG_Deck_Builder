<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Deck;
use App\Card;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function admin()
    {
        $users = User::all();
        $decks = Deck::all();
        $cards = Card::all();

        return view('admin', compact('users', 'decks', 'cards'));
    }
}