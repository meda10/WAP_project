<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;


class StoreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {     
        //Store::create(['description' => 'Popis prodejny', 'address' => '', 'reference_number' => '', 'zip_code' => '', 'city' => '']);
     
        Store::create(['description' => 'Popis prodejny', 'address' => 'Bubeníčkova, 11', 'zip_code' => 61500, 'city' => 'Brno-Židenice']);
        Store::create(['description' => 'Popis prodejny', 'address' => 'Nábřeží Ludvíka Svobody, 1222', 'zip_code' => 11100, 'city' => 'Praha']);
        Store::create(['description' => 'Popis prodejny', 'address' => 'nám. Míru, 24', 'zip_code' => 66601, 'city' => 'Tišnov']);
    }
}
