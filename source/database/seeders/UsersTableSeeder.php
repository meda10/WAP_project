<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(['name' => 'Woo', 'surname' => 'Skočdopole', 'email' => 'woo@google.gom', 'password' => '11111111111111111', 'role' => 'manager', 'address' => 'Dvořákova 25', 'zip_code' => '66605', 'city' => 'Brno', 'confirmed' => '0', 'store_id' => '1']);
        User::create(['name' => 'Boo', 'surname' => 'Gue', 'email' => 'boo@yahoo.gg', 'password' => '11111111111111111', 'role' => 'director', 'address' => 'Horská 15', 'zip_code' => '55596', 'city' => 'Tišnov', 'confirmed' => '0', 'store_id' => '2']);
        User::create(['name' => 'John', 'surname' => 'Doe', 'email' => 'john.doe@dodo.com', 'password' => '11111111111111111', 'role' => 'employee', 'address' => 'Dolní 555', 'zip_code' => '45869', 'city' => 'Praha', 'confirmed' => '1', 'store_id' => '2']);
        User::create(['name' => 'Jane', 'surname' => 'Doe', 'email' => 'jane.doe@mmmm.com', 'password' => '11111111111111111', 'role' => 'customer', 'address' => 'Honrní 89', 'zip_code' => '26805', 'city' => 'Trutnov', 'confirmed' => '0', 'store_id' => '1']);
        User::create(['name' => 'Franta', 'surname' => 'Uaaaaaag', 'email' => 'Frantuv.email@seznam.cz', 'password' => '11111111111111111', 'role' => 'customer', 'address' => 'Brněnská 64', 'zip_code' => '18505', 'city' => 'Turnov', 'confirmed' => '0', 'store_id' => '3']);
    }
}
