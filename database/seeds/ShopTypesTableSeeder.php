<?php

use Illuminate\Database\Seeder;

class ShopTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('shop_types')->insert([
            [
                'name' => 'Pharmacy',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bakery',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Book Shop',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Furniture Showroom',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
