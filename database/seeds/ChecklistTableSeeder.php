<?php

use Illuminate\Database\Seeder;

class ChecklistTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('checklists')->insert([
            'id' => '1',
            'object_id' => '1',
            'object_domain' => 'contact',
            'description' => 'Need to verify this guy house.',
            'is_completed' => false,
            'completed_at' => null,
            'updated_by' => null,
            'due' => null,
            'urgency' => 0,
        ]);
    }
}
