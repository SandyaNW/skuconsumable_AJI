<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class AdminSkuRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Ensure the AdminSKU role exists
        $role = Role::firstOrCreate(['name' => 'AdminSKU']);

        // 2. Assign the role to Maya Lestari (maya.l)
        $maya = User::where('username', 'maya.l')->first();
        if ($maya) {
            $maya->assignRole($role);
            $this->command->info("Role AdminSKU assigned to user: maya.l (Maya Lestari)");
        } else {
            $this->command->warn("User maya.l not found");
        }

        // 3. Assign the role to Admin Portal AJI (ajisatu) for testing
        $admin = User::where('username', 'ajisatu')->first();
        if ($admin) {
            $admin->assignRole($role);
            $this->command->info("Role AdminSKU assigned to user: ajisatu (Admin Portal AJI)");
        } else {
            $this->command->warn("User ajisatu not found");
        }
    }
}
