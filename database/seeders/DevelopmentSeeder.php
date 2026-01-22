<?php

namespace Database\Seeders;

use App\Models\Development;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'name' => 'Pembangunan Jalan Desa Blok A',
                'description' => 'Pengaspalan jalan desa sepanjang 2 km untuk memudahkan akses transportasi warga',
                'person_in_charge' => 'Budi Santoso, S.T.',
                'start_date' => '2025-06-01',
                'end_date' => '2025-12-31',
                'amount' => 500000000,
                'status' => 'selesai',
            ],
            [
                'name' => 'Renovasi Balai Desa',
                'description' => 'Perbaikan dan modernisasi gedung balai desa untuk pelayanan yang lebih baik',
                'person_in_charge' => 'Siti Aminah, S.Sos.',
                'start_date' => '2026-01-15',
                'end_date' => '2026-06-30',
                'amount' => 250000000,
                'status' => 'berlangsung',
            ],
            [
                'name' => 'Pembangunan Saluran Irigasi',
                'description' => 'Pembuatan saluran irigasi untuk area persawahan seluas 50 hektar',
                'person_in_charge' => 'Ahmad Hidayat, S.T.',
                'start_date' => '2026-02-01',
                'end_date' => '2026-08-31',
                'amount' => 750000000,
                'status' => 'berlangsung',
            ],
            [
                'name' => 'Instalasi Penerangan Jalan Umum (PJU)',
                'description' => 'Pemasangan lampu jalan tenaga surya di seluruh wilayah desa',
                'person_in_charge' => 'Eko Prasetyo, S.T.',
                'start_date' => '2025-09-01',
                'end_date' => '2025-11-30',
                'amount' => 180000000,
                'status' => 'selesai',
            ],
            [
                'name' => 'Pembangunan Pos Kesehatan Desa',
                'description' => 'Pembangunan gedung posyandu dan puskesmas pembantu untuk layanan kesehatan',
                'person_in_charge' => 'Dr. Fitri Handayani',
                'start_date' => '2026-03-01',
                'end_date' => '2026-12-31',
                'amount' => 600000000,
                'status' => 'berlangsung',
            ],
            [
                'name' => 'Program Bantuan Sumur Bor',
                'description' => 'Pembuatan 10 sumur bor untuk memenuhi kebutuhan air bersih warga',
                'person_in_charge' => 'Joko Widodo, S.T.',
                'start_date' => '2026-01-20',
                'end_date' => '2026-05-30',
                'amount' => 150000000,
                'status' => 'berlangsung',
            ],
            [
                'name' => 'Pembangunan Taman Bermain Anak',
                'description' => 'Pembuatan taman bermain ramah anak di pusat desa',
                'person_in_charge' => 'Dewi Kusuma, S.Pd.',
                'start_date' => '2025-10-01',
                'end_date' => '2025-12-15',
                'amount' => 120000000,
                'status' => 'selesai',
            ],
            [
                'name' => 'Pengadaan Mobil Ambulance Desa',
                'description' => 'Pembelian mobil ambulance untuk layanan kesehatan darurat warga',
                'person_in_charge' => 'H. Abdullah Rahman',
                'start_date' => '2026-02-15',
                'end_date' => '2026-04-30',
                'amount' => 350000000,
                'status' => 'berlangsung',
            ],
        ];

        $developmentImages = [
            'assets/data-seeder/thumbnails/kk-pembangunan-desa-1.png',
            'assets/data-seeder/thumbnails/kk-pembangunan-desa-2.png',
            'assets/data-seeder/thumbnails/kk-pembangunan-desa-3.png',
            'assets/data-seeder/thumbnails/kk-pembangunan-desa-4.png',
            'assets/data-seeder/thumbnails/kk-pembangunan-desa-5.png',
        ];

        $index = 0;
        foreach ($projects as $project) {
            Development::create([
                'thumbnail' => $developmentImages[$index % count($developmentImages)],
                'name' => $project['name'],
                'description' => $project['description'],
                'person_in_charge' => $project['person_in_charge'],
                'start_date' => $project['start_date'],
                'end_date' => $project['end_date'],
                'amount' => $project['amount'],
                'status' => $project['status'],
            ]);
            $index++;
        }
    }
}
