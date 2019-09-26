<?php

use Illuminate\Database\Seeder;

class TodoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('todo')->insert([
            'todo' => 'Task Pertama',
            'category' => 'work',
            'user_id' => '1',
            'description' => 'penjelasan task pertama adalah sebagai berikut',
        ]);
    }
}
