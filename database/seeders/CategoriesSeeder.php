<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->truncate();

        DB::table('categories')->insert(array(
            0 =>
            array(
                'id' => '1',
                'name' => 'Karet Kering',
                'created_at' => '2023-04-11 02:47:36',
                'updated_at' => '2023-04-11 02:47:36',
            ),
            1 =>
            array(
                'id' => '2',
                'name' => 'Minyak Sawit',
                'created_at' => '2023-04-11 02:47:36',
                'updated_at' => '2023-04-11 02:47:36',
            ),
            2 =>
            array(
                'id' => '3',
                'name' => 'Biji Sawit',
                'created_at' => '2023-04-11 02:47:36',
                'updated_at' => '2023-04-11 02:47:36',
            ),
            3 =>
            array(
                'id' => '4',
                'name' => 'Teh',
                'created_at' => '2023-04-11 02:47:36',
                'updated_at' => '2023-04-11 02:47:36',
            ),
            4 =>
            array(
                'id' => '5',
                'name' => 'Gula Tebu',
                'created_at' => '2023-04-11 02:47:36',
                'updated_at' => '2023-04-11 02:47:36',
            ),
        )
        );
    }
}
