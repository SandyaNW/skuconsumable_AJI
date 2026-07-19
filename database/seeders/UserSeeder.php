<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 1. Tambahkan kolom 'username' agar tidak dianggap duplikat kosong
        User::updateOrCreate(
            ['email' => 'pic@aji.co.id'],
            [
                'username' => 'pic_user', // Tambahkan username unik
                'name' => 'PIC User SKU',
                'npk'  => '123456',
                'password' => Hash::make('password'),
                'dept_id' => 1,
                'departement' => 'MIS',
            ]
        );

        User::updateOrCreate(
            ['email' => 'fa@aji.co.id'],
            [
                'username' => 'finance_admin', // Tambahkan username unik
                'name' => 'Finance Admin',
                'npk'  => '333333',
                'password' => Hash::make('password'),
                'dept_id' => 2,
                'departement' => 'FINANCE',
            ]
        );

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
