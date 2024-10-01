<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class addUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Mauricio',
            'email' => 'msvareira@gmail.com',
            'password' => Hash::make('a2102674633'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
