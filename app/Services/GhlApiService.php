<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class GhlApiService
{
    public $user = null;

    public function __construct(
        public $code = null,
        public $state = null
    )
    {
        //
    }
}
