<?php

namespace App\Http\Controllers;
use App\Deck;
use App\User;
use App\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DecksController extends Controller
{   
    protected $request;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }
    public function showDecks()
    {
        return view('deck.showDecks');
    }
}
