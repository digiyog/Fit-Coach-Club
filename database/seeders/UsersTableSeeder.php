<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create(
            [
                'name' => 'Super Admin',
                'email' => 'admin@hellomyyoga.net',
                'password' => bcrypt('123456'),
                'role_id' => 1,
                'role_type' => 'super-admin',
            ]
        );

        // Get admin role
        $role = Role::where([
            'type' => config('constants.users.types.SUPER_ADMIN.value'),
            'status' => config('constants.statuses.ACTIVE.value')
        ])->first();
        //-----------------

        // Assign admin role to user
        if (! is_null($user) && ! is_null($role)) {
            $user->assignRole($role->id);
        }
        //--------------------------
    }
}
