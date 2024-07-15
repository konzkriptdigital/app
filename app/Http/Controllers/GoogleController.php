<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function googleOauth ()
    {
        return Socialite::driver('google')->redirect();

    }

    public function googleCallback ()
    {
        $user = Socialite::driver('google')->stateless()->user();

        // Check if user is null
        if (!$user) {
            return redirect('/login')->withErrors(['error' => 'Unable to login with Google.']);
        }

        $find = User::where('google_id', $user->id)
            ->first();

        if($find) {
            Auth::login($find);
        }
        else {

            $randomPass = Str::random('8');
            $user = User::updateOrCreate([
                'email' => $user->email,
            ], [
                'name' => $user->name,
                'google_id' => $user->id,
                'avatar' => $user->avatar,
                'password' => Hash::make($randomPass),
            ]);

            Auth::login($user);
        }

        return redirect()->intended('dashboard');
    }
}
