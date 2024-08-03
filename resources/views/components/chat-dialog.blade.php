@props(['messages', 'color', 'name', 'toMethod'])
<div x-data="{ open: true }" class="">
    <div :class="{ '-translate-y-0': open, 'translate-y-full': !open }"
        class="fixed z-50 h-64 transition-all duration-300 transform w-72 bottom-10 right-14">
        <div class="">
            <button @click="open = !open" type="button" :class="{ 'text-white hover:bg-{{ $color }}-400': open }"
                class="w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-white rounded-t-lg hover:bg-{{ $color }}-400 bg-{{ $color }}-600">
                {{ $name }}
                <svg xmlns="http://www.w3.org/2000/svg" x-show="!open" x-cloak fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="block font-bold size-4 ms-auto">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                </svg>

                <svg xmlns="http://www.w3.org/2000/svg" x-show="open" x-cloak fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="block font-bold size-4 ms-auto">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>

            </button>
        </div>
        <div class="flex flex-col w-full h-full px-2 py-4 overflow-auto bg-white border border-gray-300 rounded-b-lg">
            <div x-ref="chatBox" class="flex flex-col flex-1 p-4 text-sm gap-y-1">
                @foreach ($messages as $message)
                    <div>
                        <span class="text-gray-900">{{ $message['name'] }}:</span>
                        <span class="text-gray-600">{{ $message['text'] }}</span>
                    </div>
                @endforeach
            </div>
            <div>
                <form wire:submit.prevent="{{ $toMethod }}" class="flex gap-2">
                    <x-text-input wire:model="message" x-ref="messageInput" name="message" id="message"
                        class="block w-full" />
                    <x-primary-button>
                        Send
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>
</div>
