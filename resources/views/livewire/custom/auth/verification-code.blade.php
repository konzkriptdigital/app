<?php

use App\Livewire\Forms\CustomLoginForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\Attributes\Url;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Volt\on;
use App\Models\VerificationCode as ModelsVerificationCode;
use Illuminate\Support\Facades\Auth;


new #[Layout('layouts.guest')] class extends Component
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

    public function mount ()
    {
        $this->codes = array_fill(0, 6, '');
        $this->user = User::where('email', $this->email)->first();

        $targetTime = $this->user->updated_at;
        $this->targetTime = strtotime($targetTime) * 1000; // Milliseconds for JavaScript

        if(!$this->user) {
            $this->redirectIntended(route('login'));
        }
    }

    #[On('checkOtpCode')]
    public function checkOtpCode ()
    {
        $code = join('', $this->codes);

        if (strlen($code) === 6) {
            $timezone = config('app.timezone');
            $currentTime = Carbon::now($timezone);
            $otpExpiresAt = Carbon::parse($this->user->otp_secret_expires_at, $timezone);;

            if($otpExpiresAt->lessThan($currentTime)) {
                $this->errorMessage = "The code is expired";
            }
            else {
                if($this->user->otp_secret === $code) {
                    $this->user->otp_secret = null;
                    $this->user->otp_secret_expires_at = null;
                    $this->user->email_verified_at = Carbon::now();
                    $this->user->save();
                    Auth::login($this->user);
                    $this->redirectIntended('dashboard');
                }
                else {
                    $this->errorMessage = "The code is invalid or has been used";
                }
            }
        }
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
}; ?>


<div class="max-w-[400px] mx-auto flex flex-col justify-between h-full">
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    @if ($errorMessage)
        <div class="alert alert-danger">
            {{ $errorMessage }}
        </div>
    @endif

    <form wire:submit="resendCode"
        x-data="countDown"
        x-init="startCountdown">

        <div class="flex flex-col">
            <x-custom.guest-logo :header="'We sent temporary sign-in code to '. $email" />

            <p class="mt-4 text-sm font-bold text-center">Please paste (or type) your 6-digit code: </p>

            <div class="relative flex flex-row gap-2 mx-auto my-0 mt-6 max-w-400px">
                @for ($i = 0; $i < 6; $i++)
                    <input
                        name="codes[]"
                        wire:model="codes.{{ $i }}"
                        id="digit-{{ $i }}"
                        type="text"
                        inputmode="numeric"
                        maxlength="1"
                        pattern="[0-9]"
                        min="0"
                        max="9"
                        class="border border-[#2b2a351f] w-14 h-14 text-4xl text-center rounded-lg"
                        @input="validateInput($event, {{ $i }})"
                        @paste="handlePaste(event)"
                        @keydown="handleKeyDown(event, {{ $i }})"
                        @focus="handleFocus($event)"
                        autocomplete="off"
                        autofocus="{{ $i === 0 ? 'true' : 'false' }}"
                    >
                @endfor
            </div>

            <div class="mt-6 mb-2 text-xs" x-html="remainingTime"></div>

            <button type="submit" id="resend--code" :disabled="isButtonDisabled" class="btn normal-case w-full no-animation bg-violet-500 hover:bg-violet-400 h-[40px] min-h-[40px] border-0  disabled:bg-violet-300">
                <span class="text-sm text-white ms-2">{{ __('Resend Code') }}</span>
            </button>

        </div>
        <x-custom.guest-footer class="mt-5"/>
    </form>


@script
<script>

    Alpine.data('countDown', () => {
        return {
            targetTime: $wire.targetTime,
            duration: $wire.duration,
            remainingTime: '',
            newTime: '',
            isButtonDisabled: true,
            startCountdown() {
                this.updateTime();
                setInterval(() => {
                    this.updateTime();
                }, 1000);

                Livewire.on('updateTargetTime', (event) => {
                    console.log('event =>> ', event)
                    this.targetTime = event[0].newTargetTime;
                    this.duration = event[0].newDuration;
                    this.updateTime();
                });
            },

            updateTime() {
                const now = new Date().getTime();
                const endTime = this.targetTime + (this.duration * 1000); // End time in milliseconds
                const distance = endTime - now;

                if (distance < 0) {
                    this.remainingTime = '';
                    // document.getElementById('resend--code').removeAttribute('disabled');
                    this.isButtonDisabled = false;
                    return;
                }

                const remainingSeconds = Math.floor(distance / 1000);
                this.remainingTime = `You can resend again in <b>${remainingSeconds}</b> ${remainingSeconds === 1 ? 'second' : 'seconds'}.`;
                this.isButtonDisabled = true;

            },

            setNewTime() {
                const newTargetDate = new Date(this.newTime).getTime();
                if (!isNaN(newTargetDate)) {
                    this.targetTime = newTargetDate;
                    this.newTime = '';
                }
            },
            validateInput(event, index) {
                const input = event.target;
                const value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
                input.value = value; // Set the input value to the cleaned value
                this.updateLivewireModel(index, value);
                this.moveFocus(event, index);
                console.log(value)
            },
            updateLivewireModel(index, value) {
                $wire.codes[index] = value;
            },
            moveFocus(event, index) {
                const value = event.target.value;
                if (/^\d$/.test(value)) {
                    const nextElement = document.getElementById(`digit-${index + 1}`);
                    if (nextElement) {
                        setTimeout(() => {
                            nextElement.focus();
                            nextElement.select();
                        });
                    }
                }
                $wire.checkOtpCode();
            },
            handlePaste(event) {
                event.preventDefault();
                const pasteData = (event.clipboardData || window.clipboardData).getData('text');
                const digits = pasteData.match(/\d/g);
                if (digits) {
                    for (let i = 0; i < 6; i++) {
                        const input = document.getElementById(`digit-${i}`);
                        if (input && digits[i]) {
                            $wire.codes[i] = digits[i];

                        } else if (input) {
                            // input.value = '';
                            $wire.codes[i] = '';
                        }
                    }
                    const lastInput = document.getElementById(`digit-${digits.length - 1}`);
                    if (lastInput) {
                        setTimeout(() => {
                            lastInput.focus();
                            lastInput.select();
                        });
                    }
                    $wire.checkOtpCode();
                    console.log('pasted =>> ', pasteData, $wire.codes)
                }


            },
            handleKeyDown(event, index) {
                const input = event.target;
                const value = input.value;
                console.log('value  => ', value)

                if (event.key === 'Backspace') {
                    input.value = '';
                    input.focus();
                    input.select();
                    setTimeout(() => {
                        const prevElement = document.getElementById(`digit-${index - 1}`);
                        if (prevElement) {
                            prevElement.focus();
                            prevElement.select();
                        }
                        this.updateLivewireModel(index, '');
                    });
                    event.preventDefault();
                } else if (event.key === 'Delete') {
                    if (index < 5) {
                        const nextElement = document.getElementById(`digit-${index + 1}`);
                        if (nextElement) {
                            // input.value = '';
                            $wire.codes[index] = '';
                            nextElement.focus();
                            nextElement.select();
                        }
                    } else {
                        // input.value = '';
                        $wire.codes[index] = '';
                    }

                    this.updateLivewireModel(index, '');
                    event.preventDefault();
                } else if (event.key === 'ArrowLeft') {
                    if (index > 0) {
                        const prevElement = document.getElementById(`digit-${index - 1}`);
                        if (prevElement) {
                            prevElement.focus();
                            setTimeout(() => {
                                prevElement.select();
                            });
                        }
                    }
                } else if (event.key === 'ArrowRight') {
                    if (index < 5) {
                        const nextElement = document.getElementById(`digit-${index + 1}`);
                        if (nextElement) {
                            nextElement.focus();
                            setTimeout(() => {
                                nextElement.select();
                            });
                        }
                    }
                } else if (/^\d$/.test(event.key)) {
                    // input.value = event.key;
                    const nextElement = document.getElementById(`digit-${index + 1}`);
                    if (nextElement) {
                        setTimeout(() => {
                            nextElement.focus();
                            nextElement.select();
                        });
                    }
                    // event.preventDefault();
                }

                // Emit Livewire event to check OTP completeness
                // Livewire.emit('checkOtpCode');

            },
            handleFocus(event) {
                event.target.select();
            }
        }
    });
</script>
@endscript
</div>
