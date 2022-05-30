<?php

namespace Database\Seeders;

use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Manufacturer::factory()->count(4)->create();

        Manufacturer::all()->each(function (Manufacturer $manufacturer) {
             Product::factory()->count(10)->create()->each(function (Product $product) use ($manufacturer){
                $manufacturer->products()->attach($product);
            });
        });

        $admin = User::query()->where('email', '=', 'admin@example.com')->get()->isEmpty();

        if ($admin) {
            \App\Models\User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('qweqweqwe')
            ]);
        }
    }
}
