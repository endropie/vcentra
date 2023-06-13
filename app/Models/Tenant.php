<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    public function tenant_users()
    {
        return $this->hasMany(TenantUser::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, TenantUser::class);
    }

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'created_uid',
        ];
    }

    public function scopeWhereUser($query, $id)
    {
        return $query->where(
            fn($q) => $q->whereHas('tenant_users', fn($q) => $q->where('user_id', $id))
                        ->orWhere('created_uid', $id)
        );
    }

    public static function booted()
    {
        static::creating(function (self $model) {
            if ($user = auth()->user()) {
                $model->created_uid = $user->id;
            }
            else abort(401, "Create New Tenant Failed. Authorization failed.");

            return $model;
        });

        static::created(function (self $model) {
            $domainName = $model->id.".". env('APP_DOMAIN', 'localhost');
            $model->domains()->create(['domain' => $domainName]);
        });
    }
}
