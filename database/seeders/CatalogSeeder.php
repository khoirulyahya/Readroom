<?php

namespace Database\Seeders;

use App\Models\Catalog; //memanggil model
use Faker\Factory as Faker; //memanggil plugin bawaan laravel
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

         for ($i=0; $i < 4; $i++) {
            $catalog = new Catalog; //karena memanggil nama model maka harus daftarkan diatas

            $catalog->name = $faker->name;

            $catalog->save();
         }
    }
}
