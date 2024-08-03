<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'facebook_id',
        'avatar',
        'is_finish',
        'otp_secret',
        'otp_secret_expires_at',
        'first_name',
        'last_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the company associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function hasAccounts ()
    {
        $comapny = $this->company;
        if(! $comapny) {
            return false;
        }

        return $comapny->accounts()->exist();
    }
    // /**
    //  * Get the otp associated with the User
    //  *
    //  * @return \Illuminate\Database\Eloquent\Relations\HasOne
    //  */
    // public function otp(): HasOne
    // {
    //     return $this->hasOne(VerificationCode::class);
    // }
}
