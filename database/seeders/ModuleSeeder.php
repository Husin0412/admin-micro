<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('module')->insert([
            [
                'modid' => 1,
                'parent_id' => 0,
                'mod_name' => 'Master',
                'mod_alias' => 'Master',
                'mod_permalink' => null,
                'mod_icon' => 'mdi mdi-server',
                'mod_order' => 1,
                'published' => 'y'
            ],
            [
                'modid' => 10,
                'parent_id' => 1,
                'mod_name' => 'User Group',
                'mod_alias' => 'user-group',
                'mod_permalink' => '/user/group',
                'mod_icon' => null,
                'mod_order' => 2,
                'published' => 'y'
            ],
            [
                'modid' => 5,
                'parent_id' => 1,
                'mod_name' => 'Users',
                'mod_alias' => 'users',
                'mod_permalink' => '/users',
                'mod_icon' => null,
                'mod_order' => 3,
                'published' => 'y'
            ],
            [
                'modid' => 6,
                'parent_id' => 1,
                'mod_name' => 'Student',
                'mod_alias' => 'student',
                'mod_permalink' => '/student',
                'mod_icon' => null,
                'mod_order' => 4,
                'published' => 'y'
            ],
            [
                'modid' => 7,
                'parent_id' => 1,
                'mod_name' => 'Mentors',
                'mod_alias' => 'mentors',
                'mod_permalink' => '/mentors',
                'mod_icon' => null,
                'mod_order' => 5,
                'published' => 'y'
            ],
            [
                'modid' => 2,
                'parent_id' => 0,
                'mod_name' => 'Form',
                'mod_alias' => 'Form',
                'mod_permalink' => null,
                'mod_icon' => 'mdi mdi-library-books',
                'mod_order' => 1,
                'published' => 'y'
            ],
            [
                'modid' => 4,
                'parent_id' => 2,
                'mod_name' => 'Courses',
                'mod_alias' => 'courses',
                'mod_permalink' => '/courses',
                'mod_icon' => null,
                'mod_order' => 2,
                'published' => 'y'
            ],
            [
                'modid' => 8,
                'parent_id' => 2,
                'mod_name' => 'Chapters',
                'mod_alias' => 'chapters',
                'mod_permalink' => '/chapters',
                'mod_icon' => null,
                'mod_order' => 3,
                'published' => 'y'
            ],
            [
                'modid' => 9,
                'parent_id' => 2,
                'mod_name' => 'Lessons',
                'mod_alias' => 'lessons',
                'mod_permalink' => '/lessons',
                'mod_icon' => null,
                'mod_order' => 4,
                'published' => 'y'
            ],
            [
                'modid' => 3,
                'parent_id' => 0,
                'mod_name' => 'Summary',
                'mod_alias' => 'summary',
                'mod_permalink' => null,
                'mod_icon' => 'mdi mdi-receipt',
                'mod_order' => 1,
                'published' => 'y'
            ],
        ]);
    }
}
