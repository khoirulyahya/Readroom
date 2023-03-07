<?php

namespace Database\Seeders;

use App\Models\Member; //memanggil model
use Faker\Factory as Faker; //memanggil plugin bawaan laravel
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

         for ($i=0; $i < 16; $i++) {
            $member = new Member; //karena memanggil nama model maka harus daftarkan diatas

            $member->name = $faker->name;
            $member->gender = $faker->randomElement($array = array ('M', 'F')); //random gender
            $member->phone_number = '082'.$faker->randomNumber(9);
            $member->address = $faker->address;
            $member->email = $faker->email;

            $member->save();
         }
    }
}
