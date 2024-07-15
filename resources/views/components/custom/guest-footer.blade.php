
<div {{ $attributes->merge(['class' => 'login--info']) }}>
    <span class="flex flex-col items-center mt-5 text-xs ">
        <p>Would you rather use email and password?</p>
        <a class="text-blue-500 hover:opacity-70" href="{{ route('login.password') }}" wire:navigate>Continue with email and password</a>
    </span>
</div>
