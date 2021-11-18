<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Category extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert(
            [
                [
                    'title' => 'cat-title1',
                    'description' => 'cat-description1',
                ],
                [
                    'title' => 'cat-title2',
                    'description' => 'cat-description2',
                ],
            ]
        );
    }
}
