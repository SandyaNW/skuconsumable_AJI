<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $user = User::create([
        //     'name' => 'Admin', 
        //     'email' => 'admin@gmail.com',
        //     'username' => 'admin',
        //     'password' => 'admin123'
        // ]);

        $user = User::where('id',4)->first();
        // buat query user where id=2, untuk update permissions
        // karena query ini hanya untuk create routes di awal, jika sudah ada, maka harus update    
    
        // $role = Role::create(['name' => 'admin']);
        $role = Role::where('name','admin')->first();
     
        $permissions = Permission::pluck('id','id')->all();
   
        $role->syncPermissions($permissions);
     
        $user->assignRole([$role->id]);
    }
}
