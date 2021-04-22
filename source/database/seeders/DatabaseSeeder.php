<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(GenreTableSeeder::class);
        $this->call(StoreTableSeeder::class);
        $this->call(DiscountTableSeeder::class);
        $this->call(ParticipantTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(TitleTableSeeder::class);
        $this->call(RolePermissionSeeder::class);
    }
}
