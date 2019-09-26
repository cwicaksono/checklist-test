<?php

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
        DB::table('users')->insert([
            'email' => 'cahyo.wicaksono@gmail.com',
            'password' => app('hash')->make('123123'),
            'username' => 'cahyo',
            'userimage' => '',
            'api_key' => ''
        ]);
    }
}
