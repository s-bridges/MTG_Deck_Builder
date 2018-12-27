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
            'message'=>'required',
            'g-recaptcha-response' => 'required|captcha',
        ]);

        Notification::route('mail', 'magicdb.us@gmail.com')->notify(new SendContactNotification($request));    

        return redirect()->route('welcome')->with('success', 'The email was sent successfully!');
    }
}
