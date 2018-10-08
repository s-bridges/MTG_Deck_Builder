<?php

namespace App\Http\Controllers;

use App\Models\Deck;
use Illuminate\Http\Request;

class DecksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $deck = Deck::all();

        return view('deck.index', ['deck' => $deck]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        dd($deck);
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
