<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Extensions\AuthTenancy\Concerns\AuthorizableToken;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

class User extends Authenticatable
{
    use CentralConnection, AuthorizableToken, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'phone_verified_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function user_tenants()
    {
        return $this->belongsToMany(Tenant::class, TenantUser::class, 'user_id', 'tenant_id');
    }

    public function owner_tenants()
    {
        return $this->hasMany(Tenant::class, 'created_uid');
    }

    public function getAccessTenantsAttribute()
    {
        return Tenant::whereHas('tenant_users', fn($q) => $q->where('user_id', $this->id))->orWhere('created_uid', $this->id)->get();
    }


    public function isTenantAllowed($tenantID): bool
    {
        return (boolean) Tenant::whereUser($this->id)->find($tenantID);
    }
}
