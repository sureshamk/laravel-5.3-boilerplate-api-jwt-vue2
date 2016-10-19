<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class PermissionDependencyTableSeeder
 */
class PermissionDependencyTableSeeder extends Seeder
{

    /**
     *
     */
    public function run()
    {

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if (env('DB_CONNECTION') == 'mysql') {
            DB::table(config('access.permission_dependencies_table'))->truncate();
        } elseif (env('DB_CONNECTION') == 'sqlite') {
            DB::statement("DELETE FROM " . config('access.permission_dependencies_table'));
        } else { //For PostgreSQL or anything else
            DB::statement("TRUNCATE TABLE " . config('access.permission_dependencies_table') . " CASCADE");
        }

        //View access management needs view backend
        DB::table(config('access.permission_dependencies_table'))->insert([
            'permission_id' => 2,
            'dependency_id' => 1,
        ]);

        //All of the access permissions need view access management and view backend
        for ($i = 3; $i <= 23; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert(
                [
                    'permission_id' => $i,
                    'dependency_id' => 1,
                ]
            );

            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => 2,
            ]);
        }

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
