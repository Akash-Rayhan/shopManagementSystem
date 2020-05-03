<?php

use Illuminate\Database\Seeder;

class ShopSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('shop_sizes')->insert([
            [
                'id' => '1',
                'name' => 'Small',
            ],

            [
                'id' => '2',
                'name' => 'Medium',
            ],

            [
                'id' => '3',
                'name' => 'Large',
            ]
        ]);
    }
}
