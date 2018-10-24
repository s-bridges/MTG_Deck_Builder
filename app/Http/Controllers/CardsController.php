<?php

namespace App\Http\Controllers;

use Auth;
use App\Card;
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
        dd('card');
        return view('card.listCards');
    }
}
