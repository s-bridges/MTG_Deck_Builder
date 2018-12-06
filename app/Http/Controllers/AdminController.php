<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Deck;
use App\Card;
use Auth;
use Log;

class AdminController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;

    }

    public function admin()
    {
        // get deck of the week
        $dotw = Deck::where('deck_of_the_week', 1)->first();
        // get all users and decks if they have them, as well as a total count for decks a user has
        $users = User::with('decks')->withCount('decks')->get();
        $data = collect(['users' => $users, 'admin' => Auth::user(), 'dotw' => $dotw->id]);
        return view('admin-page', ['data' => $data]);
    }

    public function deckOfTheWeekSave()
    {
        $deck_to_update = (int) $this->request->input('deck_id');
        // findOrFail
        $deck = Deck::where('id', $deck_to_update)->firstOrFail();

        $deckOfWeek = Deck::where('deck_of_the_week', 1)->first();
        if ($deckOfWeek){
            $deckOfWeek->deck_of_the_week = 0;
            $deckOfWeek->save();
        }

        // set deck_of_the_week to 1
        $deck->deck_of_the_week = 1;
        // model->save()
        $saved = $deck->save();
        if ($saved) {
            return response()->json(['status' => true, 'message' => 'Saved Successfully!']);
        }
    }

    public function importCards()
    {
        $cards = Card::all();
        $cards = $cards->pluck('multiverse_id');
        $cards->each(function($item, $key){
            // file get contents based on $item
            $homepage = file_get_contents("http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=" . $item . "&type=card");
            $path = public_path('images/cards/' . $item . '.jpg'); 
            $saved = file_put_contents($path, $homepage);
            if (!$saved) {
                Log::error('Card image with ID:' . $item . ' not saved!');
            }
        });
    }

    public function changeUserType()
    {
        //here is the function to get all users, and then select specific user and give/revoke admin access
    }
}