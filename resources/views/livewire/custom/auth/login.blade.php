<?php

use App\Livewire\Forms\CustomLoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public CustomLoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $validated = $this->validate();

        $this->form->customAuthenticationEmailOnly();

        Session::regenerate();

        $this->redirect(route('verify.code', ['email' => $this->form->email]));
        // $this->redirectIntended(default: route('verify.code', ['email' => $this->form->email], absolute: false), navigate: true);
        // $this->redirect(default: route('verify.code', ['email' => $this->form->email], absolute: false), navigate: true);
    }
}; ?>

<div class="max-w-[300px] mx-auto">
    <x-custom.guest-logo :header="'We\'ll sign you in or create an account if you don\'t have one yet.'" />

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <!-- Email Address -->
        <div>
            <x-text-input id="email" wire:model="form.email" class="block w-full h-10 mt-1 border-[#2b2a351f] text-sm focus:border-violet-500" type="email" name="email" required autofocus autocomplete="username" placeholder="Work Email"/>
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <x-button type="submit" class="w-full no-animation bg-violet-500 hover:bg-violet-400 h-[40px] min-h-[40px] border-0 mt-2 disabled:bg-violet-300 " >
            <span class="text-sm text-white ms-2">{{ __('Continue with Email') }}</span>
        </x-button>

        <x-button link="{{ route('google.oauth') }}" no-wire-navigate class="w-full no-animation bg-white hover:bg-[#F6F6F6] hover:border-[#2b2a351f] h-[40px] min-h-[40px] border-[#2b2a351f] mt-8">
            <x-icon-google width="20"/>
            <span class="text-sm text-gray-600 ms-2">{{ __('Continue with Google') }}</span>
        </x-button>

        <x-button class="w-full no-animation bg-white hover:bg-[#F6F6F6] hover:border-[#2b2a351f] h-[40px] min-h-[40px] border-[#2b2a351f] mt-2">
            <x-icon-facebook width="20"/>
            <span class="text-sm text-gray-600 ms-2">{{ __('Continue with Facebook') }}</span>
        </x-button>

        <x-custom.guest-footer />
        {{-- <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="form.password" id="password" class="block w-full mt-1"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="text-indigo-600 border-gray-300 rounded shadow-sm focus:ring-indigo-500" name="remember">
                <span class="text-sm text-gray-600 ms-2">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div> --}}
    </form>
</div>
