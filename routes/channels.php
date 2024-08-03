<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

/* Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
}); */

// Hindi naka setup yung channels route mo, kaya ayaw.
// Broadcast::channel('messages', function ($user, $id) {
Broadcast::channel('messages.{id}', function (User $user, $id) {
    // logger($user->id);
    // logger($id);
    // return (int) $user->id === (int) $id;
    return true;
});

Broadcast::channel('accounts.{id}', function (User $user, $companyId) {
    logger($user->company->id);
    logger($companyId);
    return (int) $user->company->id === (int) $companyId;
});

// Broadcast::channel('chat', function ($user, $userId) {
//     return true;
// });
