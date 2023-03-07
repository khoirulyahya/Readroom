<?php

namespace Database\Seeders;

use App\Models\Author; //memanggil model
use Faker\Factory as Faker; //memanggil plugin bawaan laravel
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
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
            $author = new Author; //karena memanggil nama model maka harus daftarkan diatas

            $author->name = $faker->name;
            $author->email = $faker->email;
            $author->phone_number = '0821'.$faker->randomNumber(8);
            $author->address = $faker->address;

            $author->save();
         }
    }
}
