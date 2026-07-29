<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Role;

class AdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get admin user
        $user = User::where('role_name', config('constants.users.roles.SUPER_ADMIN.type'))->first();

        // Assign permission to admin user
        $permissions = Permission::where('panel_permissions', config('constants.users.roles.SUPER_ADMIN.type'))->get()->toArray();

        if (! is_null($user) && ! is_null($permissions)) {
            foreach($permissions as $permission)
            {
                if(!($user->hasPermissionTo($permission['name']))){
                    $user->givePermissionTo($permission['name']);
                }
            }
        }
        //----
    }
}
