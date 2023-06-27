<?php

return [
    "domain_account" => "account.". env('APP_DOMAIN', 'localhost'),
    "apps" => [
        "account" => [
            "host" => env('VAPP_ACCOUNT_HOST'),
            "public_path" => env('VAPP_ACCOUNT_PUBLIC_PATH', 'vbuild/account'),
        ],
        "app1" => [
            "host" => env('VAPP1_HOST'),
            "public_path" => env('VAPP1_PUBLIC_PATH', 'vbuild/app1'),
        ],
        "app2" => [
            "host" => env('VAPP2_HOST'),
            "public_path" => env('VAPP2_PUBLIC_PATH', 'vbuild/app2'),
        ],
        "app3" => [
            "host" => env('VAPP3_HOST'),
            "public_path" => env('VAPP3_PUBLIC_PATH', 'vbuild/app3'),
        ],
        "app4" => [
            "host" => env('VAPP4_HOST'),
            "public_path" => env('VAPP4_PUBLIC_PATH', 'vbuild/app4'),
        ],
    ]

];
