<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            if (Auth::attempt($credentials)) {
                return redirect()->route('dashboard');
            }

            return back()->withErrors(['email' => 'Email sau parolă incorectă.']);
        }

        return view('auth.login');
    }

    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'username'         => 'required|string|max:50|unique:users',
                'email'            => 'required|email|max:255|unique:users',
                'password'         => 'required|min:6',
                'confirm_password' => 'required|same:password',
            ]);

            $user = User::create([
                'username' => $data['username'],
                'email'    => $data['email'],
                'password' => $data['password'],
            ]);

            Auth::login($user);

            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
