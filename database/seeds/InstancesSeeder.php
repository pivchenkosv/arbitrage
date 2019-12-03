<?php

use Illuminate\Database\Seeder;

class InstancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('instances')->insert([
            'name' => 'Exmo',
        ]);
        DB::table('instances')->insert([
            'name' => 'Bitmex',
        ]);
        DB::table('instances')->insert([
            'name' => 'Bilaxy',
        ]);
    }
}
