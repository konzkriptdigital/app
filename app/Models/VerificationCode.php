<?php

namespace App\Models;

use App\Observers\VerificationCodeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[ObservedBy([VerificationCodeObserver::class])]
class VerificationCode extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];
    /**
     * Get the user that owns the VerificationCode
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
