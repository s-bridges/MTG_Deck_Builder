<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // this is what is redirecting people to home after logging in
    protected $redirectTo = '/';

    protected function authenticated()
    {
        return redirect('/card');
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function redirectTo() {
        // dd($this->request->query('url', '/card'));
        // use a ternary to check and see if url is being pass as a query param, if it is then use it, otherwise user redirects to '/card'
        // $url = $request->query('url') ? (string) $request->query('url') : '/card';
        // use request to get url params of the current page path you are on
        // dd($this->request);
        return '/card';
    }
}
