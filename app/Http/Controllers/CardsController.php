<?php

namespace App\Http\Controllers;
use App\Deck;
use App\User;
use App\Card;
use App\Sideboard;
use App\PowerLevel;
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
        $cards = Card::where('set', $set)->with('power_levels')->get();
        $cards = $cards->map(function($card, $key){
            $card->power_levels = $card->power_levels->keyBy('name');
            return $card;
        });
        
        return response()->json(['status' => 200, 'payload' => $cards]);
    }

    public function listCards()
    {
        $cards = Card::with('power_levels')->get();
        $cards = $cards->map(function($card, $key){
            $card->power_levels = $card->power_levels->keyBy('name');
            return $card;
        });
        $power_levels = PowerLevel::all();
        $data = collect(['cards' => $cards, 'power_levels' => $power_levels]);
        return view('list-cards', ['data' => $data]);
    }
    
    public function saveCards()
    {
        return view();
    }

    public function cardLanding($id)
    {
        $card = Card::where('id', $id)->with('power_levels')->first();
        // dd($card->power_levels);
        return view('card-landing', ['card' => $card]);
    }
}
