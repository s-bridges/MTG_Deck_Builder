<?php

namespace App\Http\Controllers;
use App\Deck;
use App\User;
use App\Card;
use Auth;
use JavaScript;
use Illuminate\Http\Request;


class CardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function listCardsBy($set)
    {
        $cards = Card::where('set', $set)->get();
        
        return response()->json(['status' => 200, 'payload' => $cards]);
    }

    public function listCards()
    {
        return view('card.list-cards');
    }
}
