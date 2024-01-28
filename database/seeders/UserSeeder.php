<?php

namespace Database\Seeders;

Use Hash;
use DB;
use Str;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("SET foreign_key_checks=0");
            DB::table('users')->truncate();
        DB::statement("SET foreign_key_checks=1");

        $users_all = [];

        $users = User::create([
            'name'              => 'Test 1',
            'email'             => 'test1@example.com',
            'email_verified_at' => null,
            'password'          => Hash::make('test12345'),
            'remember_token'    => Str::random(10),
        ]);

        $users_all[] = $users->id;

        $users = User::create([
            'name'              => 'Test 2',
            'email'             => 'test2@example.com',
            'email_verified_at' => null,
            'password'          => Hash::make('test23456'),
            'remember_token'    => Str::random(10),

        ]);

        $users_all[] = $users->id;
    }
}
