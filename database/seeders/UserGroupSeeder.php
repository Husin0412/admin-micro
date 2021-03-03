<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class UserGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_group')->insert([
        [    
            'guid' => 1,
            'gname' => 'supersu',
            'roles' => json_encode([
                "view" => "1,2,3,4,5,6,7,8,9,10,12,13,14,15,16",
                "create" => "1,2,3,4,5,6,7,8,9,10,12,13,14,15,16",
                "alter" => "1,2,3,4,5,6,7,8,9,10,12,13,14,15,16",
                "drop" => "1,2,3,4,5,6,7,8,9,10,12,13,14,15,16",
            ])
        ],
        [
            'guid' => 2,
            'gname' => 'admin',
            'roles' => json_encode([
                "view" => "1,2,3,4",
                "create" => "1,2,3,4",
                "alter" => "1,2,3,4",
                "drop" => "1,2,3,4"
            ])
        ]
        ]);
    }
}
