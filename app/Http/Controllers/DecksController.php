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
        // save deck model with data from above
        $deck->save();
        
        $cards_array = [];
        // loop through all of the cards to set the counts
        $cards = collect($cards)->each(function ($card, $key) use (&$cards_array) {
            if (array_has($cards_array, $card['id'])) {
                // get current card count
                $current_count = (int) $cards_array[$card['id']]['count'];
                // take the current count and add a card to it
                $cards_array[$card['id']] = ['count' => $current_count + 1];
            } else {
                $cards_array[$card['id']] = ['count' => 1];
            }
            return $card;
        });

        // $cards = [3 => ['count' => 4], 6 => ['count' => 4], 8 => ['count' => 4]];

        // attach the list of cards to the deck potentially make this a sync?
        $attached = $deck->cards()->attach($cards_array);

        return response()->json(['status' => true, 'message' => 'Saved Successfully!', 'payload' => $deck->toArray()]);
    }

    public function myDecks()
    {                
        $decks = Deck::where('user_id', Auth::user()->id)->has('cards')->with('cards')->get();
        $data = collect(['decks' => $decks]);
        return view('show-decks', ['data' => $data]);
    }    

    public function specificDeck($deck_id) {
        $deck_id = (int) $deck_id;
        // need to fix this so there is an OR where clause...basically, if this is the user's deck, or the deck is allow_share, get me the deck
        $deck = Deck::where('id', $deck_id)
        ->with('cards')->first();

        if ($deck && $deck_id === $deck['id']) {
            // set editable to false if this isn't the user's dick, otherwise, let them edit their own dick
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
        $deck = Deck::where('deck_of_the_week', 1)->with('cards')->with('user')->firstOrFail();
        if ($deck) {
            $data = collect(['deck' => $deck]);
            return view('deck-of-the-week', ['data' => $data]);
        }
        return redirect('/404');
    }

    public function editDeck() {
        $deck_request = $this->request->all();
        $deck_id = (int) $deck_request['id'];
        // query for deck by id
        $deck = Deck::where('id', $deck_id)
        ->with('cards')->first();
        // make sure only deck owner can edit and that a deck existed with that id
        $can_edit = $deck && Auth::user()->id == $deck->user_id ? true: false;
        if ($can_edit) {
            // get all of the cards
            $cards = $deck_request['cards'];
            $cards_array = [];
            // loop through all of the cards to create an array of just the card ids from the DB
            $cards = collect($cards)->each(function ($card, $key) use (&$cards_array) {
                // push card pivot into cards_array for sync
                $count = (int) $card['pivot']['count'];
                $cards_array[$card['id']] = ['count' => $count];
                return $card;
            })->toArray();

            // $cards = [3 => ['count' => 4], 6 => ['count' => 4], 8 => ['count' => 4]];

            // using cards variable, sync all of the cards to the deck which is essentially removing any relationship with card ids not included in the array, and adding non existant ones
            $synced = $deck->cards()->sync($cards_array);
            
            if ($synced) {
                // messaging that the shit actually happened, for now we can just take a dump
                dd($synced);
            }
            
        } else {
            // redirect or throw unauthorize message
            return redirect('/404');
        }
    }
}
