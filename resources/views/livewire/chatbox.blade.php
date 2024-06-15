<?php

use App\Events\MessageSent;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * @var string[]
     */
    public array $messages = [];
    public string $message = '';
    protected $listeners = ['echo:messages,MessageSent' => 'onMessageSent'];

    public function addMessage()
    {
        MessageSent::dispatch(auth()->user()->name, $this->message);
        $this->reset('message');
    }

    public function onMessageSent($event)
    {
        // dd($event);
        $this->messages[] = $event;
    }
}
?>

<div x-data="{ open: true }" >
    <div :class="{'-translate-y-0': open, 'translate-y-full': !open}" class="fixed transition-all duration-300 transform bottom-10 right-12 h-60 w-80">
        <div class="mb-2">
            <button @click="open = !open" type="button" :class="{ 'text-indigo-600 dark:text-white hover:bg-transparent': open }" class="w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-white rounded-lg hover:bg-indigo-400 dark:bg-indigo-600 dark:hover:bg-indigo-400">
                Chat

                <x-heroicon-o-chevron-up x-show="!open" x-cloak class="block ms-auto size-4" />
                <x-heroicon-o-chevron-down x-show="open" x-cloak class="block ms-auto size-4" />

            </button>
        </div>
        <div class="flex flex-col w-full h-full px-2 py-4 overflow-auto bg-white border border-gray-300 rounded dark:bg-gray-800 dark:border-gray-700">
            <div x-ref="chatBox" class="flex flex-col flex-1 p-4 text-sm gap-y-1">
                @foreach($messages as $message)
                    <div><span class="text-indigo-600">{{ $message['name'] }}:</span> <span class="dark:text-white">{{ $message['message'] }}</span></div>
                @endforeach
            </div>
            <div>
                <form wire:submit.prevent="addMessage" class="flex gap-2">
                    <x-text-input wire:model="message" x-ref="messageInput" name="message" id="message" class="block w-full" />
                    <x-primary-button>
                        Send
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>
</div>
