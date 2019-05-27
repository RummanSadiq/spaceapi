<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
                'name' => 'Electronics',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Clothes',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Medicine',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Furniture',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        DB::table('categories')->insert([
            [
                'name' => 'Mobile Phones',
                'parent_id' => '1',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Laptop & PC',
                'parent_id' => '1',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Accessories',
                'parent_id' => '1',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Heart Medicine',
                'parent_id' => '3',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Antibiotics',
                'parent_id' => '3',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        DB::table('categories')->insert([
            [
                'name' => 'Samsung',
                'parent_id' => '5',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Apple',
                'parent_id' => '5',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Cables & Chargers',
                'parent_id' => '7',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Head Phones & Speakers',
                'parent_id' => '7',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
