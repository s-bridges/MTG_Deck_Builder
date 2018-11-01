<?php

namespace App\Http\Controllers;

use Session;
use App\Notifications\SendContactNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    public function showContact()
    {
        return view('contact.form');
    }

    public function sendEmail(Request $request)
    {
        $this->validate($request, [
            'name'=>'required|max:255|string',
            'email'=>'required|email|max:255',
            'message'=>'required'
        ]);

        Notification::route('mail', 'magicdb.us@gmail.com')->notify(new SendContactNotification($request));    


        return redirect()->route('home')->with('success', 'The email was sent successfully!');
    }
}
