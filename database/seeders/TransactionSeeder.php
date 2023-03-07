<?php

namespace Database\Seeders;

use App\Models\Transaction; //memanggil model
use Faker\Factory as Faker; //memanggil plugin bawaan laravel
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

         for ($i=0; $i < 50; $i++) {
            $transaction = new Transaction; //karena memanggil nama model maka harus daftarkan diatas

            //table yg menjadi foreign key jumlah randomnya disesuaikan jumlah data yg ada di masing-masing field(publisher, author, catalog)
            $transaction->member_id = rand(1,20);
            $transaction->date_start = $faker->dateTimeBetween('-10 month');
            $transaction->date_end  = $faker->dateTimeBetween($transaction->date_start, $transaction->date_start->format('Y-m-d').' +7 days');
            $transaction->status = '1';

            $transaction->save();
         }
    }
}
