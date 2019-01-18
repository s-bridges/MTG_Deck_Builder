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
        $ip = $_SERVER['REMOTE_ADDR'];
        $this->validate($request, [
            'name'=>'required|max:255|string',
            'email'=>'required|email|max:255',
            'message'=>'required'
        ]);
        // check to make sure honeyPot isn't filled out, if it is then a bot did it.
        if ($_POST['_honeyPot'] != '' || $_POST['_honeyPot2'] != '') {
            return redirect()->back()->withErrors(['Are you sure you are human?']);
        }

        // passed validation, send email and redirect with message
        Notification::route('mail', 'magicdb.us@gmail.com')->notify(new SendContactNotification($request));    

        return redirect()->back()->with('success', 'The email was sent successfully!');
    }
}
