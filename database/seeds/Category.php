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
                    'slug' => 'cat-slug1',
                ],
                [
                    'title' => 'cat-title2',
                    'slug' => 'cat-slug2',
                ],
            ]
        );
    }
}
