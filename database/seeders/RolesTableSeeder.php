<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = config('constants.users.roles');

        foreach($roles as $key => $value){
            $arrData = [
                'name' => $value['caption'],
                'guard_name' => 'web',
                'is_default' => config('constants.is_default.YES.value'),
                'type' => $value['type'],
                'status' => config('constants.statuses.ACTIVE.value'),
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ];

            Role::updateOrCreate(
                [
                    'name' => $value['caption'],
                    'guard_name' => 'web',
                    'type' => $value['type'],
                ],
                $arrData
            );
        }
    }
}
