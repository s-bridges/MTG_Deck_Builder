<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Deck;
use App\User;
use App\Card;

class DecksController extends Controller
{   
    protected $request;

    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dd($data);
        $deck = Deck::where('user_id', Auth::user()->id)->get();
        $user = Auth::user();
        $data = collect(['user' => $user, 'deck' => $deck]);
        return view('deck.index', ['data' => $data]);

        /** NOT TO STANDARD | SAVE UNTIL WORKING
         * $deck = Deck::with('user')->where('user_id', 1)->where('id', 1)->get();
         *
         * return view('deck.index', ['deck' => $deck]);
         */
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = $this->request->all();
        $form['user_id'] = Auth::user()->id;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Deck  $deck
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $deck = Deck::with('user_id')->findOrFail($id);
        //dd($deck);
        return view('deck.show', ['deck'=>$deck]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Deck  $deck
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deck $deck)
    {
        //
    }
}
