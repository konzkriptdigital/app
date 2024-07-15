<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;


new #[Layout('layouts.app')] class extends Component
{

}; ?>

<div class="flex flex-col items-center justify-center h-full">
    <div class="relative z-10 flex flex-col gap-10 dashboard--content">
        <div class="dashboard--greetings">
            <h1>Hey Vincent 👋</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
        </div>
        <div class="bg-white agency--container">
            <div class="flex flex-row justify-between w-full agency--header">
                <div class="agency--name">Konzript Digital</div>
                <div class="flex flex-row items-center agency--actions">
                    <div class="agency--item">
                        Search
                    </div>
                    <div class="agency--item">
                        Billing
                    </div>
                    <div class="agency--item">
                        Settings
                    </div>
                </div>
            </div>
            <div class="agency--body"></div>
        </div>
    </div>
</div>
