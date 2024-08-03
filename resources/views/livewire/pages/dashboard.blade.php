<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\on;
use Livewire\Attributes\Locked;
use Livewire\WithPagination;
use App\Traits\AbbreviatesStrings;
use App\Events\AccountCreated;
use App\Models\Account;

new #[Layout('layouts.app')] class extends Component
{
    use AbbreviatesStrings, WithPagination;

    public $ghlOauthUrl;

    public $accounts;
    public $userId;
    public $user;
    public $account;


    // protected $listeners = ['echo:chat,AccountCreated' => 'onAccountCreated'];
    // protected $listeners = ['onAccountCreated'];

    // public function getListeners()
    // {
    //     return [
    //         "echo-private:accounts.{$this->userId},AccountCreated" => 'onAccountCreated',
    //     ];
    // }

    public function with ()
    {
        if($this->user->company) {
            $accounts = Account::query()
                ->where('company_id', $this->user->company->id)
                ->paginate(2);
        }

        return [
            'accounts' => $accounts
        ];
    }

    public function mount()
    {
        $this->user = auth()->user();
        $this->userId = $this->user->id;
        // $company = $user?->company;
        // $this->accounts = $company?->accounts ? $company->accounts->toArray() : [];
        // $this->accounts = $company?->accounts ? $company->accounts->paginate(10) : [];


        $scope = config('services.ghl.scope');
        $clientId = config('services.ghl.client_id');
        $redirectUri = config('services.ghl.redirect');
        $this->ghlOauthUrl = "https://marketplace.gohighlevel.com/oauth/chooselocation?state={$this->userId}&response_type=code&redirect_uri={$redirectUri}&client_id={$clientId}&scope={$scope}&loginWindowOpenMode=self";
    }

    public function authorizeAccount()
    {
        $this->redirect($this->ghlOauthUrl);
    }

    // public function onAccountCreated($event)
    // {

    //     $account = $event['account'];
    //     $exists = !empty(array_filter($this->accounts, function ($item) use ($account) {
    //         return $item['ghl_id'] === $account['ghl_id'];
    //     }));

    //     if(!$exists) {
    //         $this->accounts[] = $event['account'];
    //     }
    // }


}; ?>

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
                    <x-button link="{{ $ghlOauthUrl }}" external>
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
            <div class="account--cards">

            </div>
        </div>
    </div>
</div>
