<?php

use Illuminate\Database\Seeder;
use App\User;
use App\PowerLevel;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::create([
        //     'username' => 'h3r0_sH0t',
        //     'name' => 'Seth Bridges',
        //     'email' => 'matvmp7@gmail.com',
        //     'password' => Hash::make('123456'),
        //     'type'=> 'admin'
        // ]);

        // User::create([
        //     'username' => 'daggerToes',
        //     'name' => 'Alex Rindone',
        //     'email' => 'arindone1679@gmail.com',
        //     'password' => Hash::make('iapp@123'),
        //     'type'=> 'admin'
        // ]);

        //Aggro
        PowerLevel::create([
            "name" => "Aggro",
            "description" => "An agressive deck which attempts to win the game through persistent, quick damage dealing. Usually these decks will use small, hard-hitting creatures to win the game."
        ]);

        //Combo
        PowerLevel::create([
            "name" => "Combo",
            "description" => "A deck or archetype which uses a combo as its victory condition. The deck is designed entirely for the purpose of setting up and protecting the combo."
        ]);

        //Control
        PowerLevel::create([
            "name" => "Control",
            "description" => "A deck of that aims to control the opponent's cards and progression with, ideally, the end result where one has full control of everything that is done during the game. Control decks typically get their edge through card advantage. They are very powerful and present in virtually every format in the game."
        ]);
        
        //Control
        PowerLevel::create([
            "name" => "Limited",
            "description" => "A deck that is built by drafting cards during a Magic the Gathering draft event. Players build decks of 40 cards and often play games against other drafters in best two of three."
        ]);
    }
}
