<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<aside x-data="{ open: false }" class="flex flex-col items-center pt-4 bg-transparent w-14">
    <div class="flex flex-col mb-auto">
        <div class="flex items-center shrink-0">
            <a href="{{ route('dashboard') }}" wire:navigate>
                <x-application-logo class="block w-auto text-gray-800 fill-current h-9" />
            </a>
        </div>
    </div>
    <x-dropdown align="right" width="48" >
        <x-slot name="trigger">
            <button class="inline-flex items-center text-sm font-medium p-[10px] leading-4 text-gray-500 transition duration-150 ease-in-out w-14 h-14 hover:text-gray-700 focus:outline-none">
                @if (auth()->user()->avatar)
                    <img class="object-cover w-full h-full rounded-full" src="{{ asset('images/profile.jpg') }}" alt="{{ auth()->user()->name }}" />
                @else
                    @php
                        $names = [auth()->user()->name]; // Example for array of names
                        $initials = [];

                        foreach ($names as $name) {
                            $nameParts = explode(' ', trim($name));
                            $firstName = array_shift($nameParts);
                            $lastName = array_pop($nameParts);
                            $initials = (
                                mb_substr($firstName, 0, 1) .
                                mb_substr($lastName, 0, 1)
                            );
                        }
                    @endphp
                    <span class="capitalize user--name-abbreviation">
                        {{ $initials }}
                    </span>
                @endif

            </button>
        </x-slot>

        <x-menu-item title="{{ __('Profile') }}" link="{{ route('profile') }}" icon="o-envelope" />
        <x-menu-item title="{{ __('Log Out') }}" wire:click="logout" icon="o-paper-airplane" badge="78+" />

    </x-dropdown>
</aside>
