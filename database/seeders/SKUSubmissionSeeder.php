<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SKUSubmission;
use App\Models\SKUDetail;
use App\Models\User; // Import model User
use Illuminate\Support\Facades\DB;

class SKUSubmissionSeeder extends Seeder
{
    public function run()
    {
        // 1. Cari user dengan username 'maya.l'
        $user = User::where('username', 'maya.l')->first();

        // Cek dulu usernya ada gak, biar gak error pas running
        if (!$user) {
            $this->command->error("User maya.l tidak ditemukan! Pastikan username di tabel users benar.");
            return;
        }

        $items = ['Baut M8', 'Oli Mesin Shell', 'Kabel Awg 18', 'Gasket CVT', 'Bearing 6201'];

        for ($i = 1; $i <= 10; $i++) {
            DB::beginTransaction();
            try {
                // 2. Create Header pake data asli dari objek $user
                $submission = SKUSubmission::create([
                    'nama' => $user->name,       // Ambil nama lengkap asli
                    'npk' => $user->npk,         // Ambil NPK asli (ID 28)
                    'departement' => $user->dept_name ?? 'IT & Digital', // Pake dept user
                    'dept_id' => $user->dept_id, // Biar sinkron sama filter dashboard
                    'section' => 'Development',
                    'issue_date' => now()->subDays($i)->format('Y-m-d'),
                    'status' => ($i % 4) + 1,    // Status variasi 1-4
                    'remarks' => 'Testing data Maya ke-' . $i
                ]);

                // 3. Create Detail
                SKUDetail::create([
                    'sku_submission_id' => $submission->id,
                    'item_name' => $items[array_rand($items)] . ' - Batch ' . $i,
                    'specification' => 'Spec Standard V.' . $i,
                    'qty' => rand(10, 50),
                    'uom' => 'PCS',
                    'category' => 'Consumable',
                    'usage' => rand(5, 20),
                    'keperluan' => 'Kebutuhan maintenance project maya',
                    'due_date' => now()->addDays(14)->format('Y-m-d'),
                    'product_code' => ($submission->status == 4) ? 'PN-' . rand(100, 999) : null,
                    'sku' => ($submission->status == 4) ? 'SKU-' . rand(1000, 9999) : null,
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                $this->command->error("Gagal di data ke-$i: " . $e->getMessage());
            }
        }

        $this->command->info("Berhasil membuat 10 data SKU untuk user: " . $user->username);
    }
}
