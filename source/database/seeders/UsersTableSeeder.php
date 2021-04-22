<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(['name' => 'Role', 'surname' => 'Director', 'email' => 'director@gmail.com', 'password' => Hash::make('123456789'), 'role' => 'director', 'address' => 'Černohorská 6935', 'zip_code' => '68805', 'city' => 'Olomouc', 'confirmed' => '1', 'store_id' => '1']);
        User::create(['name' => 'Role', 'surname' => 'Manager', 'email' => 'manager@gmail.com', 'password' => Hash::make('123456789'), 'role' => 'manager', 'address' => 'Dlouhá 485', 'zip_code' => '66405', 'city' => 'Brno', 'confirmed' => '1', 'store_id' => '1']);
        User::create(['name' => 'Role', 'surname' => 'Employee', 'email' => 'employee@gmail.com', 'password' => Hash::make('123456789'), 'role' => 'employee', 'address' => 'Brněnská 255', 'zip_code' => '44605', 'city' => 'Těšín', 'confirmed' => '1', 'store_id' => '1']);
        User::create(['name' => 'Role', 'surname' => 'Customer', 'email' => 'customer@gmail.com', 'password' => Hash::make('123456789'), 'role' => 'customer', 'address' => 'Janáčkova 895', 'zip_code' => '62505', 'city' => 'Ostrava', 'confirmed' => '1', 'store_id' => '1']);
        User::create(['name' => 'Woo', 'surname' => 'Skočdopole', 'email' => 'woo@gmail.com', 'password' => Hash::make('123456789'), 'role' => 'manager', 'address' => 'Dvořákova 25', 'zip_code' => '66605', 'city' => 'Praha', 'confirmed' => '1', 'store_id' => '1']);
        User::create(['name' => 'Boo', 'surname' => 'Gue', 'email' => 'boo@yahoo.gg', 'password' => Hash::make('123456789'), 'role' => 'director', 'address' => 'Horská 15', 'zip_code' => '55596', 'city' => 'Tišnov', 'confirmed' => '0', 'store_id' => '2']);
        User::create(['name' => 'John', 'surname' => 'Doe', 'email' => 'john.doe@dodo.com', 'password' => Hash::make('123456789'), 'role' => 'employee', 'address' => 'Dolní 555', 'zip_code' => '45869', 'city' => 'Praha', 'confirmed' => '1', 'store_id' => '2']);
        User::create(['name' => 'Jane', 'surname' => 'Doe', 'email' => 'jane.doe@mmmm.com', 'password' => Hash::make('123456789'), 'role' => 'customer', 'address' => 'Honrní 89', 'zip_code' => '26805', 'city' => 'Trutnov', 'confirmed' => '0', 'store_id' => '1']);
        User::create(['name' => 'Franta', 'surname' => 'Uaaaaaag', 'email' => 'Frantuv.email@seznam.cz', 'password' => Hash::make('123456789'), 'role' => 'customer', 'address' => 'Brněnská 64', 'zip_code' => '18505', 'city' => 'Turnov', 'confirmed' => '0', 'store_id' => '3']);
    }
}
