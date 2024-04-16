<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //
    public function signup()
    {
        return inertia('Auth/Signup');
    }

    public function signin()
    {
        return inertia('Auth/Signin');
    }

    public function signinStore(Request $request)
    {
        if (!Auth::attempt($request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]), true)) {

            throw ValidationException::withMessages([
                'email' => 'Authentication failed'
            ]);
        };

        $request->session()->regenerate();
        return redirect()->intended('home');
    }

    public function signupStore(Request $request)
    {
        $user = User::create($request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8'
        ]));

        Auth::login($user);

        return redirect()->route('home')->with('success', "User created.");
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // $request->user()->logout();

        return redirect()->route('signin');
    }
}
