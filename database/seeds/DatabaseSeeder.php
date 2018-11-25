<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'h3r0_sH0t',
            'name' => 'Seth Bridges',
            'email' => 'matvmp7@gmail.com',
            'password' => Hash::make('123456'),
            'type'=> 'admin'
        ]);

        User::create([
            'username' => 'daggerToes',
            'name' => 'Alex Rindone',
            'email' => 'arindone1679@gmail.com',
            'password' => Hash::make('iapp@123'),
            'type'=> 'admin'
        ]);
    }
}
