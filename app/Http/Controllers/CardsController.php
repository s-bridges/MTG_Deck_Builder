<?php

namespace App\Http\Controllers;

use App\Card;
use Illuminate\Http\Request;


class CardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function listCards()
    {
        return view('card.listCards');
    }
}
