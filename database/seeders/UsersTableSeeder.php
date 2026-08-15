<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Super Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('123456'),
                'role_id' => 1,
                'role_type' => 'super-admin',
                'status' => 1,
                'start_date' => Carbon::now()->toDateString(),
                'end_date' => Carbon::now()->addYears(5)->toDateString(),
            ]
        );

        $adminRole = Role::where('type', 'super-admin')->first();
        if ($admin && $adminRole) {
            $admin->syncRoles([$adminRole->id]);
        }

        // 2. Nutrition / Franchise User
        $nutritionUser = User::updateOrCreate(
            ['email' => 'nutrition@gmail.com'],
            [
                'name' => 'Nutrition Coach',
                'mobile_number' => '9876543210',
                'password' => bcrypt('123456'),
                'role_id' => 2,
                'role_type' => 'franchise',
                'status' => 1,
                'start_date' => Carbon::now()->toDateString(),
                'end_date' => Carbon::now()->addYears(1)->toDateString(),
                'created_by' => $admin ? $admin->id : 1,
            ]
        );

        $franchiseRole = Role::where('type', 'franchise')->first();
        if ($nutritionUser && $franchiseRole) {
            $nutritionUser->syncRoles([$franchiseRole->id]);
        }
    }
}
