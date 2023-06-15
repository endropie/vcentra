<?php

namespace Database\Seeders;

use Faker\Generator as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TenantSeeder extends Seeder
{
    protected Faker $faker;

    public function run(): void
    {
        $this->faker = app("Faker\Generator", ['locale' => 'id_ID']);

        $this->dropDBTenants();

        $ex = ['aaa', 'bbb', 'ccc', 'ddd'];
        $no = 500;
        for ($i=0; $i < count($ex); $i++) {
            $name = "user-". $no+$i;
            $request = new Request([
                "name" => $name,
                "email" => "$name@example.com",
                "phone" => $this->faker->unique()->phoneNumber,
                "password" => "password",
            ]);

            /** @var \App\Http\Resource $res **/
            $res = app(\App\Http\ApiControllers\AuthController::class)->register($request);
            $user = $res->resource;

            auth()->login($user);

            /** @var \App\Http\Resource $res **/
            $res = app(\App\Http\ApiControllers\TenantController::class)->store(new Request([
                "id" => $ex[$i],
            ]));
            $tenant = $res->resource;

            $nos = rand(500, 599);
            for ($j=0; $j < rand(1,5); $j++) {
                $name = "user-". $no+$i ."-". $nos+$j;
                $request = new Request([
                    "name" => $name,
                    "email" => "$name@example.com",
                    "phone" => $this->faker->unique()->phoneNumber,
                    "password" => "password",
                ]);

                /** @var \App\Http\Resource $res **/
                $res = app(\App\Http\ApiControllers\AuthController::class)->register($request);

                $userTenant = $res->resource;


                app(\App\Http\ApiControllers\TenantController::class)->setUser($tenant->id, new Request([
                    "user_id" => $userTenant->id,
                ]));

            }
        }
    }

    protected function dropDBTenants()
    {
        foreach (DB::select('SHOW DATABASES') as $ob) {
            if(Str::of($ob->Database)->startsWith(config('tenancy.database.prefix')))
            {
                DB::statement("DROP DATABASE `{$ob->Database}`");
            }
        }
    }
}
