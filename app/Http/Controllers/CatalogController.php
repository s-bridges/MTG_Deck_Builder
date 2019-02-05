<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Card;
use App\Catalog;
use Auth;
use JavaScript;

class CatalogController extends Controller
{
    //    
    // public function listCards()
    // {
    //     $cards = Card::all();
    //     $data = collect(['cards' => $cards]);
    //     return view('catalog', ['data' => $data]);
    // }

    public function viewCatalog(){
        $user_id = Auth::user()->id;
        $cards = Card::all();
        $catalog = Catalog::where('user_id', $user_id)->get();
        $data = collect(['catalog' => $catalog, 'cards' => $cards]);
        dd($data);
        return view('catalog', ['data' => $data]);
    }

    public function saveCatalog()
    {
        $catalog = new Catalog; 
        $catalog->user_id = Auth::user()->id;
        $cards = $this->request->input('cards');

        $catalog->save();
        
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

        $attached = $catalog->cards()->attach($cards_array);


        return response()->json(['status' => true, 'message' => 'Saved Successfully!', 'payload' => $catalog->toArray()]);
    }

}
