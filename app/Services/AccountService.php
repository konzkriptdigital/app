<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class AccountService
{
    public $user = null;

    public function __construct(
        public $code = null,
        public $state = null
    )
    {
        //
    }

    public function ghlOauth()
    {
        // throw
        $response = $this->getAccessToken([
            "grant_type" => "authorization_code",
            "code" => $this->code
        ]);

        if(isset($response['error'])) {
            throw new \Exception($response['error_description']);
        }

        return $response;
    }

    public function ghlRequest ($method, $url, $body, $accessToken)
    {

        if($method === 'GET') {
            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Version' => config('services.ghl.version')
                ])
                ->get($url)
                ->json();

        }

        /* if($method === 'POST') {
            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Version' => config('services.ghl.version')
                ])
                ->post($url, $body)
                ->json();

        } */

        return $response;
    }

    public function getLocation($request)
    {
        $locationId = $request['locationId'];
        $response =$this->ghlRequest(
            'GET',
            "https://services.leadconnectorhq.com/locations/{$locationId}",
            null,
            $request['access_token']
        );

        if($response['location']) {
            DB::transaction(function() use ($response, $request) {
                $location = $response['location'];

                $company = $this->createCompany([
                    'id' => $location['companyId'],
                    'name' => $location['companyId']
                ], null);

                $company->accounts()->updateOrCreate(
                    [
                        'ghl_id' => $location['id']
                    ],
                    [
                        'ghl_id' => $location['id'],
                        'name' => $location['name'],
                        'profile' => $location['logoUrl'] ?? null,
                        'email' => $location['email'] ?? null,
                        'data' => $location,
                        'access_token' => $request['access_token'] ?? null,
                        'refresh_token' => $request['refresh_token'] ?? null,
                        'token_type' => $request['token_type'] ?? null,
                        'expires_in' => $request['expires_in'] ?? null,
                    ]
                );

            });
        }

        return $this->state;
    }

    public function getCompany($oauth)
    {
        $companyId = $oauth['companyId'];

        $response = $this->ghlRequest(
            'GET',
            "https://services.leadconnectorhq.com/companies/{$companyId}",
            null,
            $oauth['access_token']
        );

        if($response['company']) {
            $this->createCompany($response['company'], $oauth);
            $this->getAccounts($oauth);
        }

        return $this->state;
    }

    public function getAccessToken($body)
    {
        $query = array_merge($body, [
            "client_id" => config('services.ghl.client_id'),
            "client_secret" => config('services.ghl.client_secret'),
        ]);

        $token = Http::asForm()
            ->post("https://services.leadconnectorhq.com/oauth/token", $query)->json();
        return $token;
    }

    public function createCompany($company, $request)
    {

        if(!$this->user) {
            $user = $this->user = User::where('id', $this->state)
                ->first();
        }
        else {
            $user = $this->user;
        }

        return DB::transaction(function() use ($user, $company, $request) {

            return $user->company()->updateOrCreate(
                [
                    'ghl_id' => $company['id']
                ],
                [
                    'name' => $company['name'] ?? $company['id'],
                    'profile' => $company['logoUrl'] ?? null,
                    'email' => $company['email'] ?? null,
                    'phone' => $company['phone'] ?? null,
                    'address' => $company['address'] ?? null,
                    'city' => $company['city'] ?? null,
                    'state' => $company['state'] ?? null,
                    'zip' => $company['postalCode'] ?? null,
                    'country' => $company['country'] ?? null,
                    'data' => $company,
                    'access_token' => $request['access_token'] ?? null,
                    'refresh_token' => $request['refresh_token'] ?? null,
                    'token_type' => $request['token_type'] ?? null,
                    'expires_in' => $request['expires_in'] ?? null
                ]
            );
        });
    }

    public function getAccounts($oauth)
    {
        $accounts = $this->ghlRequest(
            'GET',
            "https://services.leadconnectorhq.com/locations/search?limit=10",
            null,
            $oauth['access_token']
        );

        if(!$this->user) {
            $user = $this->user = User::where('id', $this->state)
                ->first();
        }
        else {
            $user = $this->user;
        }

        // Get the accounts from the user's company
        // $companyAccounts = $user->company->accounts->pluck('ghl_id')->toArray();

        if($accounts['locations']) {

            collect($accounts['locations'])
                ->map(function ($account) use ($user) {
                    return [
                        'company_id' => $user->company->id,
                        'ghl_id' => $account['id'],
                        'name' => $account['name'],
                        'email' => $account['email'] ?? null,
                        'profile' => $account['logoUrl'] ?? null,
                        'data' => json_encode($account)
                    ];
                })
                ->chunk(5)
                ->each(function (Collection $chunk) use ($user) {
                    $user
                        ->company
                        ->accounts()
                        ->upsert($chunk->toArray(), ['ghl_id'],
                            [
                                'name', 'email', 'profile', 'data'
                            ]
                        );
                });
        }
    }
}
