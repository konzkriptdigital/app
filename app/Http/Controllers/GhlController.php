<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Services\AccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GhlController extends Controller
{
    // use AccountService;
    //
    public function code(Request $request)
    {
        if ($request->has('code') && $request->has('state')) {
            $accountService = new AccountService(
                $request->code,
                $request->state
            );

            $response = $accountService->ghlOauth();

            $message = $response['userType'] === "Location" ?
                'Location added successfully' :
                'Company added successfully';

            if($response['userType'] === "Location") {
                $userId = $accountService->getLocation($response);
            }
            else {
                $userId = $accountService->getCompany($response);
            }

            if($userId) {
                Auth::loginUsingId($userId);
                return;
                return redirect()->route('dashboard')->with('message', $message);
            }
        }

        return redirect()->route('dashboard')
            ->with('error', 'Something went wrong! Unable to authenticate GHL account!');
    }

}
