
<div class="flex flex-col items-center justify-center h-full">
    <div class="relative z-10 flex flex-col gap-10 dashboard--content">
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <div class="dashboard--greetings">
            <h1>Hey Vincent 👋</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
        </div>
        <div class="bg-white agency--container">
            <div class="flex flex-row justify-between w-full agency--header">
                <div class="agency--name">Konzript Digital</div>
                <div class="flex flex-row items-center agency--actions">
                    <button class="btn" wire:click="authorizeAccount">
                        <x-icon-building-library width="16" class="width: 18px; height: 18px;" />
                        <span>{{ __('Add Account') }}</span>
                    </button>
                    <x-button link="{{ $this->ghlOauthUrl }}" external>
                        <x-icon-building-library width="16" class="width: 18px; height: 18px;" />
                        <span>{{ __('Add Account') }}</span>
                    </x-button>
                    <button class="btn">
                        <x-icon-credit-card width="16" class="width: 18px; height: 18px;"/>
                        <span>{{ __('Billing') }}</span>
                    </button>
                    <button class="btn">
                        <x-icon-cogs-6-tooth width="16" class="width: 18px; height: 18px;"/>
                        <span>{{ __('Settings') }}</span>
                    </button>
                </div>
            </div>
                {{-- @if ($this->accounts)
                    @foreach ($this->accounts as $account)
                        <livewire:components.account :account="$account" :key="$account['id']" />
                    @endforeach

                    <button wire:click="loadMore" class="btn">Load More</button>
                @endif --}}
            {{-- @livewire('component.accounts-list', ['company_id' => $this->company->id]) --}}

            {{-- <div class="account--cards">
                @if (isset($accounts))
                    @foreach ($accounts as $account)
                        <livewire:components.account :account="$account" :key="$account['id']" />
                    @endforeach

                    <button wire:click="loadMore" class="btn">Load More</button>
                @endif
            </div> --}}
            {{-- @forelse($this->accounts as $account)
                <livewire:components.account :account="$account" :key="$account['id']" />
            @empty
                <p>No Posts Found</p>
            @endforelse
            <button wire:click="loadMore" class="btn">Load More</button> --}}

            @livewire('component.accounts-list', ['company' => $this->company, 'user' => $this->user])
        </div>
    </div>
</div>
