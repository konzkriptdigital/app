<?php

namespace App\Livewire\Forms;

use App\Mail\VerificationCodeMail;
use App\Models\User;
use App\Models\verificationCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Mail;
use Livewire\Form;

class CustomLoginForm extends Form
{
    #[Validate('required|string|email')]
    public string $email = '';

    public function customAuthenticationEmailOnly ($value = null)
    {

        $user = User::where('email', $this->email)->first();

        // Generate a random password for user
        $password = Str::password(symbols: false, length: 8, numbers: false);

        // Generate 6 random digits for OTP
        $otp = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Verify if user already exist
        if(!$user) {
            $user = User::create([
                'name' => substr($this->email, 0, strpos($this->email, '@')),
                'email' => $this->email,
                'otp_secret' => $otp,
                'otp_secret_expires_at' => Carbon::now()->addMinutes(5),
                'password' => Hash::make($password)
            ]);

            Mail::to($this->email)->send(new VerificationCodeMail($otp, $this->email));
        }
        // Update User's OTP Code
        else {
            $user->otp_secret = $otp;
            $user->otp_secret_expires_at = Carbon::now()->addMinutes(5);
            $user->save();
        }

        Mail::to($user->email)->queue(new VerificationCodeMail($otp, $user->email));

        // $this->resendCode($user);
    }

    // Updating User's OTP Code
    // Sending OTP Code Email After User provide an email
    public function resendCode ($user)
    {
        $otp = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->otp_secret = $otp;
        $user->otp_secret_expires_at = Carbon::now()->addMinutes(5);
        $user->save();
        Mail::to($user->email)->send(new VerificationCodeMail($otp, $user->email));
        return $user;
    }
}
