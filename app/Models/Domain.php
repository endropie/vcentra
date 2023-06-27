<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Domain as BaseDomain;

class Domain extends BaseDomain
{

    function getHostUrl() : string
    {
        return "http://$this->domain.". env('APP_DOMAIN');
    }
}
