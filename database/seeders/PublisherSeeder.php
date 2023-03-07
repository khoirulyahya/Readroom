<?php

namespace Database\Seeders;

use App\Models\Publisher; //memanggil model
use Faker\Factory as Faker; //memanggil plugin bawaan laravel
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

         for ($i=0; $i < 20; $i++) {
            $publisher = new Publisher; //karena memanggil nama model maka harus daftarkan diatas

            $publisher->name = $faker->name;
            $publisher->email = $faker->email;
            $publisher->phone_number = '082'.$faker->randomNumber(9);
            $publisher->address = $faker->address;

            $publisher->save();
         }
    }
}
