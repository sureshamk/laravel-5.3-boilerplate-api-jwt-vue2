<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{

    public function run()
    {

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if (env('DB_CONNECTION') == 'mysql') {
            DB::table(config('access.roles_table'))->truncate();
        } elseif (env('DB_CONNECTION') == 'sqlite') {
            DB::statement("DELETE FROM " . config('access.roles_table'));
        } else //For PostgreSQL or anything else
        {
            DB::statement("TRUNCATE TABLE " . config('access.roles_table') . " CASCADE");
        }

        $roles = [
            [
                'name' => 'Admin',
                'all' => true,
                'sort' => 1,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'User',
                'all' => false,
                'sort' => 2,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ],
        ];

        config('access.role')::insert($roles);


        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
