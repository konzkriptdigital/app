@props(['header'])

<div>
    <a href="/" wire:navigate class="flex items-center justify-center mt-14">
        <x-application-logo class="w-20 h-20 text-gray-500 fill-current" />
    </a>
    <p class="mb-4 text-sm text-center text-gray-800 mt-7">{{ $header }}</p>
</div>
