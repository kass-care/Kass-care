<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated($request, $user)
    {

        if ($user->role == 'admin') {

            return redirect('/dashboard');

        }

        if ($user->role == 'caregiver') {

            return redirect('/caregiver-dashboard');

        }

        if ($user->role == 'provider') {

            return redirect('/provider-dashboard');

        }

        return redirect('/dashboard');

    }

}
