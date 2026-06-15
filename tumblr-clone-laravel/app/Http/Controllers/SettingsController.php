<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($request->isMethod('post') && $request->has('bio')) {
            $request->validate(['bio' => 'nullable|string|max:500']);

            $user->update(['bio' => $request->bio]);

            return redirect()->route('settings');
        }

        return view('settings.index', compact('user'));
    }

    public function updateAvatar(Request $request)
    {
        $request->validate(['avatar' => 'required|image|max:2048']);

        $path = $request->file('avatar')->store('avatars', 'public');

        auth()->user()->update(['avatar' => $path]);

        return redirect()->route('settings');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Parola actuală este incorectă.']);
        }

        $user->update(['password' => $request->new_password]);

        return redirect()->route('settings')->with('success', 'Parola a fost schimbată.');
    }
}
