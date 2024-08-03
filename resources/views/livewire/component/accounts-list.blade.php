<div class="account--cards">
    @if ($accounts)
        @foreach ($accounts as $account)
            {{-- @livewire('component.account-item', ['account' => $account, 'key' => $account['id']]) --}}

            <div class="account--card flex flex-row gap-[10px]" :key="{{ $account['data']['id'] }}">
                <div class="account--profile">
                @if(isset($account['data']['logoUrl']))
                    <img src="{{ $account['data']['logoUrl'] }}" alt="{{ $account['data']['name'] }}" class="account--profile-image" />
                @else
                    <div class="account--profile-placeholder">
                        {{ $this->abbreviate($account['name']) }}
                    </div>
                @endif
                </div>
                <div class="flex flex-col account--content">
                    <div class="account--name">{{ $account['data']['name'] }}</div>
                    <div class="account--location-id">{{ $account['data']['address'] ?? '' }}</div>
                </div>
            </div>

        @endforeach
        <button wire:click="loadMore" class="btn">Load More</button>
    @endif
</div>
