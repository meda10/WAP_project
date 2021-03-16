<?php

namespace Database\Seeders;

use App\Models\Participant;
use Illuminate\Database\Seeder;

class ParticipantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $participant = Participant::create(['name' => 'Pepa', 'surname' => 'Skočdopole', 'birth' => '2000-03-03', 'created_at' => '2019-03-03', 'updated_at' => '2019-03-03']);
        $participant = Participant::create(['name' => 'Karel', 'surname' => 'Neskočdopole', 'birth' => '2010-11-03', 'created_at' => '2019-03-03', 'updated_at' => '2019-03-03']);
        $participant = Participant::create(['name' => 'Arnold', 'surname' => 'Skočildopole', 'birth' => '1940-03-12', 'created_at' => '2019-03-03', 'updated_at' => '2019-03-03']);
        $participant = Participant::create(['name' => 'Květoň', 'surname' => 'Neskočil', 'birth' => '1973-04-03', 'created_at' => '2019-03-03', 'updated_at' => '2019-03-03']);
        $participant = Participant::create(['name' => 'Rudolf', 'surname' => 'Poskočil', 'birth' => '1985-08-03', 'created_at' => '2019-03-03', 'updated_at' => '2019-03-03']);
        $participant = Participant::create(['name' => 'Herbert', 'surname' => 'Zaskočil', 'birth' => '1990-03-03', 'created_at' => '2019-03-03', 'updated_at' => '2019-03-03']);

        //todo link to movies
    }
}
