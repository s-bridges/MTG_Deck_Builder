<?php

namespace App\Http\Controllers;
use App\Deck;
use App\User;
use App\Card;
use Auth;
use JavaScript;
use Illuminate\Http\Request;


class DecksController extends Controller
{   
    protected $request;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /* public function index()
    {
        
    } */
   /*  public function showDecks()
    {
        return view('deck.showDecks');
    }
    public function search(Request $request)
    {
        $card = Card::where('name', $request->keywords)->get();

        return response()->json($card);
    } */

    /**
     * Here is to new dreams
     */
    public function store(Request $request)
    {
        dd($request);
        $data = $request->all();

        $deck = Deck::create([
            'user_id' => $data['user_id'],
            'name' => $data['name'],
            'cards' => $data['userDeck'],
        ]);

        JavaScript::put([
            'cardlist' => $deck['cards']
        ]);

        return redirect('/home');

    }
    public function show(Deck $deck)
    {
        $user = Auth::id();
        $testLead = \App\Card::where('id', $deck[id])->get();

        $deck['leader'] = $testLead[0];

        $userDecks = \App\Deck::where('user_id', $user)->orderBy('updated_at', 'desc')->get();
        foreach ($userDecks as $userDeck) {
            $lead = \App\Card::where('id', $userDeck['id'])->get();
            $userDeck['leader'] = $lead[0];
        };

        JavaScript::put([
            'cardlist' => $deck['cards'],
            'editDeck' => $deck,
            'isEdit' => true,
            'decks' => $userDecks
        ]);

        return view('deckbuilder');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Deck  $deck
     * @return \Illuminate\Http\Response
     */
    public function edit(Deck $deck)
    {
        //
    }
     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deck  $deck
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deck $deck)
    {
        // dd($request);
        $deck = Deck::find($request->deck_id);
        $deck['user_id'] = $request->user_id;
        $deck['name'] = $request->name;
        $deck['cards'] = $request->userDeck;
        $deck['id'] = $request->deck_id;
        $deck->save();
        return redirect('/home');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Deck  $deck
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deck $deck)
    {
        $toDelete = Deck::find($deck->id);
        $toDelete->delete();
        return redirect('/home');
    }
}
