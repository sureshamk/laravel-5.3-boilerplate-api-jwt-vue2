<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PermissionGroupTableSeeder extends Seeder {

    public function run() {

        if(env('DB_CONNECTION') == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        if(env('DB_CONNECTION') == 'mysql')
        {
            DB::table(config('access.permission_group_table'))->truncate();
        } elseif(env('DB_CONNECTION') == 'sqlite') {
            DB::statement("DELETE FROM ".config('access.permission_group_table'));
        } else { //For PostgreSQL or anything else
            DB::statement("TRUNCATE TABLE ".config('access.permission_group_table')." CASCADE");
        }

        /**
         * Create the Access groups
         */

        $group_model = config('access.group');
        $access = new $group_model;
        $access->name = 'Access';
        $access->sort = 1;
        $access->save();

        $group_model = config('access.group');
        $user = new $group_model;
        $user->name = 'User';
        $user->sort = 1;
        $user->parent_id = $access->id;
        $user->save();

        $group_model = config('access.group');
        $role = new $group_model;
        $role->name = 'Role';
        $role->sort = 2;
        $role->parent_id = $access->id;
        $role->save();

        $group_model = config('access.group');
        $permission = new $group_model;
        $permission->name = 'Permission';
        $permission->sort = 3;
        $permission->parent_id = $access->id;
        $permission->save();

        if(env('DB_CONNECTION') == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
