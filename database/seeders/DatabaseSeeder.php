<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\helpers\Helpers;
use App\Models\AccountKey;
use App\Models\BussinesService;
use App\Models\Partenaire;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Administrateur',
            'email' => 'test@example.com',
            'password' => bcrypt('123456789'),
            'phone' => '+12398190255',
            'api_key' => "wt-1".Helpers::generatealeatoire(40),
            'api_secret' => Helpers::generatealeatoire(50),
            'user_type'=>0,//user administrateur
            'email_verified_at' => now(),
            'activate' => true,
        ]);
        $merchant=new AccountKey();
        $merchant->name="Default merchant";
        $merchant->user_id=User::query()->first()->id;
        $merchant->save();
        $merchant->find($merchant->id);
        $merchant->merchant_key=$merchant->id . Helpers::generatealeatoire(40);
        $merchant->save();
        $bussiness=new BussinesService();
        $bussiness->url="https://sandbox.wacepay.com";
        $bussiness->username="r3VxUxoRzslUCjw7KSDu@wacepay.com";
        $bussiness->password="15WNDrg1zcHFJCwRQ4UY0wta1rH6Co2BTnEi6feDHQLK9bmkVh";
        $bussiness->apikey="";
        $bussiness->name="Wacepay";
        $bussiness->code_name="wacepay";
        $bussiness->save();
        $patnaire=new Partenaire();
        $patnaire->name="Wace pay";
        $patnaire->code="wacepay";
        $patnaire->activate=true;
        $patnaire->save();
        $patnaire2=new Partenaire();
        $patnaire2->name="PayCI";
        $patnaire2->code="payci";
        $patnaire2->activate=false;
        $patnaire2->save();
        $patnaire3=new Partenaire();
        $patnaire3->name="PayDunya";
        $patnaire3->code="paydunya";
        $patnaire3->activate=false;
        $patnaire3->save();
    }
}
