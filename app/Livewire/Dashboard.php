<?php

namespace App\Livewire;

use App\Models\Account;
use App\Traits\AbbreviatesStrings;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    use AbbreviatesStrings, WithPagination;

    public $limit = 5;
    public $offset = 0;
    // public $loadedAccounts = [];
    public int $on_page = 5;

    /* public function getListeners()
    {
        return [
            "echo-private:accounts.{$this->user->company->id},AccountCreated" => 'onAccountCreated',
        ];
    }
 */
    #[Computed]
    public function user()
    {
        $user = auth()->user();
        if(! $user) {
            return null;
        }

        return $user;
    }

    #[Computed]
    public function company ()
    {
        if(! $this->user) {
            return null;
        }

        return $this->user->company;
    }

    #[Computed]
    public function ghlOauthUrl ()
    {
        $scope = config('services.ghl.scope');
        $clientId = config('services.ghl.client_id');
        $redirectUri = config('services.ghl.redirect');
        return "https://marketplace.gohighlevel.com/oauth/chooselocation?state={$this->user->id}&response_type=code&redirect_uri={$redirectUri}&client_id={$clientId}&scope={$scope}&loginWindowOpenMode=self";
    }

    /* #[Computed]
    public function accounts ()
    {
        return Account::query()
            ->where('company_id', $this->company->id)
            ->offset($this->offset)
            ->limit($this->limit)
            ->get();

    } */
    /* #[Computed]
    public function accounts ()
    {
        return Account::query()
            ->where('company_id', $this->company->id)
            ->take($this->on_page)
            ->get();

    } */

    /* public function loadMore(): void
    {
        $this->on_page += 5;
    } */

    /* public function loadMore ()
    {
        // $this->offset += $this->limit;
        $newAccounts = Account::query()
            ->where('company_id', $this->company->id)
            ->offset($this->offset)
            ->limit($this->limit)
            ->get();

        $this->loadedAccounts = array_merge($this->loadedAccounts, $newAccounts->toArray());
        $this->offset += $this->limit;
    } */



    /* public function onAccountCreated($event)
    {

        $account = $event['account'];
        $accounts = $this->loadedAccounts;
        $exists = !empty(array_filter($accounts, function ($item) use ($account) {
            return $item['ghl_id'] === $account['ghl_id'];
        }));

        if(!$exists) {
            $this->loadedAccounts [] = $account;
        }
    }

    public function mount()
    {
        $this->loadMore();
    } */

    public function render()
    {
        return view('livewire.dashboard'/* , [
            'accounts' => $this->loadedAccounts
        ] */);
    }
}
