<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('demos')->insert([
            'nip' => 002, 
            'nama' => 'Fulanf',
            'alamat' => 'alamat salah satu desa dan kota'
        ]);
    }
}
