<?php

namespace App\Livewire\Component;

use App\Models\Account;
use App\Models\Company;
use App\Models\User;
use App\Traits\AbbreviatesStrings;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class AccountsList extends Component
{

    use AbbreviatesStrings, WithPagination;

    public Company $company;
    public User $user;
    public $limit = 5;
    public $offset = 0;
    public $loadedAccounts = [];
    public int $on_page = 5;

    public function loadMore ()
    {
        // $this->offset += $this->limit;
        $newAccounts = Account::query()
            ->where('company_id', $this->company->id)
            ->offset($this->offset)
            ->limit($this->limit)
            ->get();

        $this->loadedAccounts = array_merge($this->loadedAccounts, $newAccounts->toArray());
        $this->offset += $this->limit;
    }

    #[On('echo-private:accounts.{company.id}, AccountCreated')]
    public function onAccountCreated($event)
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
    }

    public function render()
    {
        return view('livewire.component.accounts-list', [
            'accounts' => $this->loadedAccounts
        ]);
    }
}
