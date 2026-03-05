<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = config('constants.admin_permissions');

        foreach($permissions as $key => $value){
            $arrData = [
                'name' => $value['name'],
                'slug' => $value['slug'],
                'module_name' => $value['module_name'],
                'panel_permissions' => config('constants.users.roles.SUPER_ADMIN.type'),
                'is_default' => config('constants.statuses.ACTIVE.value'),
                'created_at' => $value['created_at'],
                'updated_at' => $value['updated_at'],
                'guard_name' => 'web',
            ];

            Permission::updateOrCreate(
                [
                    'name' => $value['name'],
                    'slug' => $value['slug'],
                    'module_name' => $value['module_name'],
                    'panel_permissions' => config('constants.users.roles.SUPER_ADMIN.type'),
                ],
                $arrData
            );
        }

        // Salon Permissions
        $salonPermissions = config('constants.salon_permissions');

        foreach($salonPermissions as $key => $value){
            $arrData = [
                'name' => $value['name'],
                'slug' => $value['slug'],
                'module_name' => $value['module_name'],
                'panel_permissions' => config('constants.users.roles.SALON.type'),
                'is_default' => config('constants.statuses.ACTIVE.value'),
                'created_at' => $value['created_at'],
                'updated_at' => $value['updated_at'],
                'guard_name' => 'web',
            ];

            Permission::updateOrCreate(
                [
                    'name' => $value['name'],
                    'slug' => $value['slug'],
                    'module_name' => $value['module_name'],
                    'panel_permissions' => config('constants.users.roles.SALON.type'),
                ],
                $arrData
            );
        }

        // Premium Salon Permissions
        $premiumSalonPermissions = config('constants.premium_salon_permissions');

        foreach($premiumSalonPermissions as $key => $value){
            $arrData = [
                'name' => $value['name'],
                'slug' => $value['slug'],
                'module_name' => $value['module_name'],
                'panel_permissions' => config('constants.users.roles.PREMIUM_SALON.type'),
                'is_default' => config('constants.statuses.ACTIVE.value'),
                'created_at' => $value['created_at'],
                'updated_at' => $value['updated_at'],
                'guard_name' => 'web',
            ];

            Permission::updateOrCreate(
                [
                    'name' => $value['name'],
                    'slug' => $value['slug'],
                    'module_name' => $value['module_name'],
                    'panel_permissions' => config('constants.users.roles.PREMIUM_SALON.type'),
                ],
                $arrData
            );
        }
    }
}
