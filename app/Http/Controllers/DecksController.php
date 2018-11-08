<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Deck;
use App\User;
use App\Card;
use Auth;
use JavaScript;


class DecksController extends Controller
{  
    protected $request;

    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
    }

    public function saveDecks()
    {
        $deck = new Deck;
        $deck->name = $this->request->input('name');
        $deck->description = $this->request->input('description');
        $deck->user_id = Auth::user()->id;
        $cards = $this->request->input('cards');
        $cards = collect($cards)->map(function ($card_id, $key) {
            return $card_id['id'];
        });

        $deck->save();
        foreach ($cards as $card_id)
        {
            $deck->cards()->attach($card_id);
        }
        return response()->json(['status' => true, 'message' => 'Saved Successfully!', 'payload' => $deck->toArray()]);
    }

    public function myDecks()
    {                
        $decks = Deck::where('user_id', Auth::user()->id)->has('cards')->with('cards')->get();
        $data = collect(['decks' => $decks]);
        return view('show-decks', ['data' => $data]);
    }    

    public function specificDeck($deck_id) {
        $deck = Deck::where('user_id', Auth::user()->id)->where('id', $deck_id)->with('cards')->first();
        $data = collect(['decks' => $decks]);
        return view('show-decks', ['data' => $data]);
    }
}
