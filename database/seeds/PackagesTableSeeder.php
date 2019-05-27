<?php

use Illuminate\Database\Seeder;

class PackagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('packages')->insert([
            [
                'name' => 'Starter',
                'cost' => "0",
                'duration' => "3",
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem fugiat expedita in, culpa ratione perspiciatis iusto aut error nobis vitae necessitatibus nam! Sunt autem ipsum neque explicabo! Est, vitae iste!',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Pro',
                'cost' => "1500",
                'duration' => "30",
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem fugiat expedita in, culpa ratione perspiciatis iusto aut error nobis vitae necessitatibus nam! Sunt autem ipsum neque explicabo! Est, vitae iste!',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Advanced',
                'cost' => "2500",
                'duration' => "30",
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem fugiat expedita in, culpa ratione perspiciatis iusto aut error nobis vitae necessitatibus nam! Sunt autem ipsum neque explicabo! Est, vitae iste!',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
