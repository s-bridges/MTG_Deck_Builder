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
            'name' => 'Seth Bridges',
            'email' => 'matvmp7@gmail.com',
            'password' => Hash::make('123456'),
            'type'=> 'admin'
        ]);
    }
}
