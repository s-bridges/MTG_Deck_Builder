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
        $deck->allow_share = $this->request->input('allow_share');
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
        //$deck_id = (int) $deck_id;
        // need to fix this so there is an OR where clause...basically, if this is the user's deck, or the deck is allow_share, get me the deck
        $deck = Deck::where('id', $deck_id)
        ->where('user_id', Auth::user()->id)
        ->orWhere('allow_share', true)
        ->with('cards')->first();
        if ($deck && $deck_id === $deck['id']) {
            // set editable to false if this isn't the user's deck, otherwise, let them edit their own deck
            $editable = Auth::user()->id == $deck['user_id'];
            $data = collect(['deck' => $deck, 'editable' => $editable]);
            // conditions on where this is view or edit
            return view('deck-cards', ['data' => $data]);
        }

        return redirect('/404');
        // default don't show the page, but if they were allowed to see the deck, the above gets returned in the conditional and this line never gets hit
        // return view('errors.404');
    }

    public function deckOfTheWeek() {
        $deck = Deck::where('deck_of_the_week', true)->with('cards')->with('user')->firstOrFail();
        if ($deck) {
            $data = collect(['deck' => $deck]);
            return view('deck-of-the-week', ['data' => $data]);
        }
        return redirect('/404');
    }
}
