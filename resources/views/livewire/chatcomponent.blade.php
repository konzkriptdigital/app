<?php

use Livewire\Volt\Component;
use App\Events\MessageEvent;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\on;

new class extends Component {
    public string $message = '';
    public array $messages = [];

    public $user;
    public $userId;
    // protected $listeners = ["echo-private:messages.{$this->userId},MessageEvent" => 'onMessageEvent'];

    public function mount()
    {
        $this->userId = Auth::id();
        /* $this->listeners = [
            "echo:messages,.MessageEvent" => 'onMessageEvent'
        ]; */
    }

    public function getListeners()
    {
        return [
            "echo-private:messages.1,MessageEvent" => 'onMessageEvent',
        ];
    }

    public function sendMessage ()
    {
        MessageEvent::dispatch(Auth::user()->name, $this->message, Auth::user());
        $this->reset('message');
    }

    // #[On('echo:messages,MessageEvent')]
    public function onMessageEvent ($event)
    {
        // dd($event);
        $this->messages[] = $event;
    }

    // // #[On('echo:messages,MessageEvent')]
    // public function onAccountCreated ($event)
    // {
    //     // dd($event);
    //     // $this->messages[] = $event;
    //     dd($event);
    // }
}; ?>
    {{-- // #[On('echo-private:messages,MessageEvent')] --}}
    {{-- #[On('echo-private:messages.{Auth::id()},MessageEvent')] --}}

<div>
    <x-chat-dialog :messages="$this->messages" toMethod="sendMessage" color="blue" name="Chat" />
</div>
