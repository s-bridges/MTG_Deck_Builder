<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Deck;
use App\User;
use App\Card;
use App\Sideboard;
use Auth;
use JavaScript;


class DecksController extends Controller
{  
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function saveDecks()
    {
        // I want this to only be hit by an auth user
        // $this->middleware('auth'); only use this if it's not being placed on the route itself in web.php
        // none of the below runs if the above fails
        $deck = new Deck;
        $deck->name = $this->request->input('name');
        $deck->description = $this->request->input('description');
        $deck->user_id = Auth::user()->id;
        $cards = $this->request->input('cards');
        // get sideboard cards, like cards above
        $sideboard = $this->request->input('sideboard');

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

        // sideboard like cards_array
        $sideboard_cards_array = [];
        // loop through all of the cards to set the counts
        $sideboard = collect($sideboard)->each(function ($sideboard_card, $key) use (&$sideboard_cards_array) {
            if (array_has($sideboard_cards_array, $sideboard_card['id'])) {
                // get current card count
                $sideboard_current_count = (int) $sideboard_cards_array[$sideboard_card['id']]['count'];
                // take the current count and add a card to it
                $sideboard_cards_array[$sideboard_card['id']] = ['count' => $sideboard_current_count + 1];
            } else {
                $sideboard_cards_array[$sideboard_card['id']] = ['count' => 1];
            }
            return $sideboard_card;
        });


        // $cards = [3 => ['count' => 4], 6 => ['count' => 4], 8 => ['count' => 4]];

        // attach the list of cards to the deck potentially make this a sync?
        $attached = $deck->cards()->attach($cards_array);

        // dupe above for sideboard
        $attached_sideboard = $deck->sideboard_cards()->attach($sideboard_cards_array);

        return response()->json(['status' => true, 'message' => 'Saved Successfully!', 'payload' => $deck->toArray()]);
    }

    public function myDecks()
    {                
        $decks = Deck::where('user_id', Auth::user()->id)->has('cards')->with('cards')->get();
        $data = collect(['decks' => $decks]);
        return view('show-decks', ['data' => $data]);
    }    

    public function specificDeck($deck_id) {
        $check = Auth::check();
        $deck_id = (int) $deck_id;
        // this query below is perfect     
        $deck = Deck::where('id', $deck_id)
        ->with('cards')->with('sideboard_cards')->first();

        if($check == true) {
            // this if below should be put together with the one above using &&
            if ($deck && $deck_id === $deck['id']) {
                // set editable to false if this isn't the user's deck, otherwise, let them edit their own deck
                $editable = Auth::user()->id == $deck['user_id'];
                $data = collect(['deck' => $deck, 'editable' => $editable]);
                // conditions on where this is view or edit
                return view('deck-cards', ['data' => $data]);
            }
        }
        else {
            $data = collect(['deck' => $deck]);
                // only view
                return view('deck-cards', ['data' => $data]);
        }               
        return redirect('/404');
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
        ->with('cards')->with('sideboard_cards')->first();
        // make sure only deck owner can edit and that a deck existed with that id
        $can_edit = $deck && Auth::user()->id == $deck->user_id ? true: false;
        if ($can_edit) {
            // check to see if deck name or description is different, if it is then update the fields
            if ($name_changed = $deck->name != $deck_request['name'] || $des_changed = $deck->description != $deck_request['description']) {
                if ($name_changed) {
                    $deck->name = $deck_request['name'];
                }
                if ($des_changed) {
                    $deck->description = $deck_request['description'];
                }
                // update if one of the two is different
                $deck->save();
            }
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

            // sideboard like cards_array
            $sideboard_cards_array = [];
            // all of the sideboard cards from the request
            $sideboard = $deck_request['sideboard_cards'];
            // loop through all of the cards to set the counts
            $sideboard = collect($sideboard)->each(function ($sideboard_card, $key) use (&$sideboard_cards_array) {
                if (array_has($sideboard_cards_array, $sideboard_card['id'])) {
                    // get current card count
                    $sideboard_current_count = (int) $sideboard_cards_array[$sideboard_card['id']]['pivot']['count'];
                    // dd($sideboard_current_count);
                    // take the current count and add a card to it
                    $sideboard_cards_array[$sideboard_card['id']] = ['count' => $sideboard_current_count + 1];
                } else {
                    $sideboard_cards_array[$sideboard_card['id']] = ['count' => $sideboard_card['pivot']['count']];
                }
                return $sideboard_card;
            });

            // dupe above for sideboard, but we use sync since some are being added and others kept
            $synced_sideboard = $deck->sideboard_cards()->sync($sideboard_cards_array);

            // $cards = [3 => ['count' => 4], 6 => ['count' => 4], 8 => ['count' => 4]];

            // using cards variable, sync all of the cards to the deck which is essentially removing any relationship with card ids not included in the array, and adding non existant ones
            $synced = $deck->cards()->sync($cards_array);
            
            if ($synced || $synced_sideboard) {
                // messaging that the shit actually happened, for now we can just take a dump
                return response()->json(['status' => true, 'message' => 'Saved Successfully!', 'payload' => $deck->toArray()]);
            } else {
                // we can get more detailed with an error message later on, like actually outputting it here
                return response()->json(['status' => false, 'message' => 'Sync Failed, please reload the page']);
            }
            
        } else {
            // redirect or throw unauthorize message
            return redirect('/404');
        }
    }

    public function deleteDeck($id) {
        // get the deck and delete it as long as the owner owns it
        $deck = Deck::where('id', $id)->where('user_id', Auth::user()->id)->first();
        $deleted = $deck->delete();
        // return a response that it was in fact deleted
        if ($deleted) {
            return response()->json(['status' => true, 'message' => 'Deleted Successfully!']);
        } else {
            return response()->json(['status' => false, 'message' => json_encode($delete)]);
        }
    }

    public function listAllDecks() {
        $decks = Deck::has('cards')->with('cards')->get();
        $data = collect(['decks' => $decks]);
        return view('all-decks', ['data' => $data]);
    }
}
