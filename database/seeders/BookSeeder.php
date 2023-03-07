<?php

namespace Database\Seeders;

use App\Models\Book; //memanggil model Book
use Faker\Factory as Faker; //memanggil plugin bawaan laravel
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
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
            $book = new Book; //karena memanggil nama model maka harus daftarkan diatas

            $book->isbn = $faker->randomNumber(9); //randomNumber bawaan laravel fungsi sama dengan rand
            $book->title = $faker->title;
            $book->year = rand(2010,2022);  //rand bawaan php
            //table yg menjadi foreign key jumlah randomnya disesuaikan jumlah data yg ada di masing-masing field(publisher, author, catalog)
            $book->publisher_id = rand(1,20);
            $book->author_id = rand(1,20);
            $book->catalog_id = rand(1,4);

            $book->qty = rand(10,20);
            $book->price = rand(10000,20000);

            $book->save();
         }
    }
}
