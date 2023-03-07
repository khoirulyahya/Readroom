<?php

namespace Database\Seeders;

use App\Models\TransactionDetail; //memanggil model
use Faker\Factory as Faker; //memanggil plugin bawaan laravel
use Illuminate\Database\Seeder;

class TransactionDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

         for ($i=1; $i < 2; $i++) {
            $transactionDetail = new TransactionDetail; //karena memanggil nama model maka harus daftarkan diatas

            //table yg menjadi foreign key jumlah randomnya disesuaikan jumlah data yg ada di masing-masing field(publisher, author, catalog)
            $transactionDetail->transaction_id = $i;
            $transactionDetail->book_id = rand(1,20);
            $transactionDetail->qty = rand(1,3);

            $transactionDetail->save();
         }
    }
}
