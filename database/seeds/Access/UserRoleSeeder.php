<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{

    public function run()
    {

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if (env('DB_CONNECTION') == 'mysql') {
            DB::table(config('access.assigned_roles_table'))->truncate();
        } elseif (env('DB_CONNECTION') == 'sqlite') {
            DB::statement("DELETE FROM " . config('access.assigned_roles_table'));
        } else //For PostgreSQL or anything else
        {
            DB::statement("TRUNCATE TABLE " . config('access.assigned_roles_table') . " CASCADE");
        }

        //Attach admin role to admin user
        config('auth.model')::find(1)->roles()->sync([1]);

        //Attach user role to general user
        config('auth.model')::find(2)->roles()->sync([2]);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
