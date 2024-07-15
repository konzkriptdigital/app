<?php

namespace App\Livewire\Forms;

use App\Mail\VerificationCodeMail;
use App\Models\User;
use App\Models\verificationCode;
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

        // Generate a random password
        // $password = Str::random(6);
        $password = 'password';

        if(!$user) {
            $user = User::create([
                'email' => $this->email,
                'password' => Hash::make($password)
            ]);
        }

        $this->resendCode($user);
    }

    public function sendEmail ($email, $password)
    {
        // Send email to the user with the generated password
        Mail::send([], [], function ($message) use ($email, $password) {
            $message->to($email)
                    ->subject('Your New Account')
                    ->setBody('Your account has been created. Your password is: ' . $password);
        });
    }

    public function resendCode ($user)
    {
        $verificationCode = DB::transaction(function () use ($user) {
            // Update or create the verification code
            $result = VerificationCode::updateOrCreate(
                ['user_id' => $user->id],
                ['code' => str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT)]
            );

            Mail::to($user->email)->send(new VerificationCodeMail($result->code, $user->email));

            return $result;
        });

        return $verificationCode;
    }
}
