<?php

namespace App\Livewire\Component;

use App\Models\Account;
use App\Traits\AbbreviatesStrings;
use Livewire\Component;

class AccountItem extends Component
{
    use AbbreviatesStrings;
    public $account = [];

    public function mount($account)
    {
        $this->account = $account;
    }

    public function render()
    {
        return view('livewire.component.account-item');
    }
}
