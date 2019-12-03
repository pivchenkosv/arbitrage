<?php

use Illuminate\Database\Seeder;

class PairsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pairs')->insert([
            'instance_id' => 1,
            'name' => 'BTC_USD',
            'first_currency' => 'BTC',
            'second_currency' => 'USD',
            'symbol' => 'BTC_USD',
            'is_enabled' => true,
        ]);
        DB::table('pairs')->insert([
            'instance_id' => 2,
            'name' => 'BTC_USD',
            'first_currency' => 'BTC',
            'second_currency' => 'USD',
            'symbol' => 'XBTUSD',
            'is_enabled' => true,
        ]);
        DB::table('pairs')->insert([
            'instance_id' => 3,
            'name' => 'BTC_USD',
            'first_currency' => 'BTC',
            'second_currency' => 'USD',
            'symbol' => 113,
            'is_enabled' => true,
        ]);
    }
}
