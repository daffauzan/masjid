<?php

namespace Database\Seeders;

use App\Models\RekeningTujuan;
use Illuminate\Database\Seeder;

class RekeningTujuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = [
            [
                'bank' => 'BSI',
                'no_rekening' => '7123456789',
                'nama_pemilik' => 'Masjid Abaabil',
                'is_active' => true,
            ],
            [
                'bank' => 'BCA',
                'no_rekening' => '1234567890',
                'nama_pemilik' => 'Masjid Abaabil',
                'is_active' => true,
            ],
        ];

        foreach ($rows as $row) {
            RekeningTujuan::updateOrCreate(
                ['no_rekening' => $row['no_rekening']],
                $row
            );
        }
    }
}