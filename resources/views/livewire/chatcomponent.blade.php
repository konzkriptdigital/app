<?php

use Livewire\Volt\Component;
use App\Events\MessageEvent;
use Illuminate\Support\Facades\Auth;


new class extends Component {
    public string $message = '';
    public array $messages = [];

    public function sendMessage ()
    {
        MessageEvent::dispatch(Auth::user()->name, $this->message, Auth::user());
        $this->reset('message');
    }

    #[On('echo-private:messages,MessageEvent')]
    public function onMessengeEvent ()
    {
        $this->messages[] = $event;
    }
}; ?>

<div>
    <x-chat-dialog :messages="$this->messages" toMethod="sendMessage" color="blue" name="Chat" />
</div>
