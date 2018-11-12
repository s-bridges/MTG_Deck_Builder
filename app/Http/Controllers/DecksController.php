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
        // need to fix this so there is an OR where clause...basically, if this is the user's deck, or the deck is allow_share, get me the deck
        $deck = Deck::where('id', $deck_id)
        ->where('user_id', Auth::user()->id)
        ->orWhere('allow_share', true)
        ->with('cards')->first();

        // users can edit their own small dick
        $editable = Auth::user()->id == $deck['user_id'];
        // if the user_id doesn't match and allow share is true, this is viewable
        $viewable = Auth::user()->id != $deck['user_id'] && $deck['allow_share'];
        $data = collect(['deck' => $deck, 'viewable' => $viewable, 'editable' => $editable]);
        // conditions on where this is view or edit
        return view('deck-cards', ['data' => $data]);
    }
}
