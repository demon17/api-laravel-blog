<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            [
                [
                    'name' => 'admin',
                    'email' => 'test@test.test',
                    'email_verified_at' => null,
                    'password' => '$2y$10$eVXXlXV09b0j8az/ZpBKnex9zyCC8LCzY.dDs922MufPUPUjwx4Fe', // 123456
                    'api_token' => 'example_api_token',
                    'remember_token' => null,
                ],
            ]
        );
    }
}
