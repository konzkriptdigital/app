<?php

namespace App\Livewire\Auth;

use Livewire\Component;

use App\Livewire\Forms\CustomLoginForm;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Url;
use App\Models\User;
use App\Models\VerificationCode as ModelsVerificationCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class VerificationCode extends Component
{

    public CustomLoginForm $form;

    #[Url]
    #[Validate('required|string|email')]
    public $email;

    public $user;
    public $timeRemaining = 0;
    public $minutes = 3;

    public $targetTime;
    public $duration = 60;
    public $codes = [];
    public $errorMessage;

    #[On('updateCode')]
    public function updateCode ()
    {

        if ($this->isOtpComplete()) {

            $code = join('', $this->codes);
            $verify = ModelsVerificationCode::where('code', $code)
                ->with('user')
                ->first();
            if ($verify) {
                Auth::login($verify->user);
                $this->redirectIntended('dashboard');
            }
            else {
                $this->errorMessage = "The code is invalid or has been used";
            }
        }
    }

    public function mount ()
    {
        $this->codes = array_fill(0, 6, '');
        $this->user = User::where('email', $this->email)->first();

        $targetTime = $this->user->otp->updated_at;
        $this->targetTime = strtotime($targetTime) * 1000; // Milliseconds for JavaScript

        if(!$this->user) {
            $this->redirectIntended(route('login'));
        }
    }

    public function isOtpComplete()
    {
        foreach ($this->codes as $code) {
            if (empty($code)) {
                return false;
            }
        }
        return true;
    }

    public function resendCode ()
    {
        $code = $this->form->resendCode($this->user);
        $this->targetTime = strtotime($code->updated_at) * 1000; // Milliseconds for JavaScript

        $this->dispatch('updateTargetTime', [
            'newTargetTime' => $this->targetTime,
            'newDuration' => $this->duration,
        ]);
    }

    public function render()
    {
        return view('livewire.auth.verification-code')
            ->layout('layouts.guest');
    }

}
