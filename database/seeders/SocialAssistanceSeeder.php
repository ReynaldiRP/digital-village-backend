<?php

namespace Database\Seeders;

use App\Models\SocialAssistance;
use Illuminate\Database\Seeder;

class SocialAssistanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            [
                'name' => 'Program Keluarga Harapan (PKH)',
                'category' => 'cash',
                'amount' => 750000,
                'provider' => 'Kementerian Sosial RI',
                'description' => 'Bantuan sosial bersyarat kepada keluarga dan/atau seseorang miskin dan rentan yang terdaftar dalam Data Terpadu Kesejahteraan Sosial (DTKS)',
                'is_available' => true,
            ],
            [
                'name' => 'Bantuan Pangan Non Tunai (BPNT)',
                'category' => 'staple',
                'amount' => 200000,
                'provider' => 'Kementerian Sosial RI',
                'description' => 'Bantuan dari pemerintah berupa non tunai yang diberikan kepada Keluarga Penerima Manfaat (KPM) setiap bulannya',
                'is_available' => true,
            ],
            [
                'name' => 'Subsidi Listrik PLN',
                'category' => 'subsidized',
                'amount' => 50000,
                'provider' => 'PT PLN (Persero)',
                'description' => 'Subsidi listrik untuk pelanggan dengan daya 450 VA dan 900 VA',
                'is_available' => true,
            ],
            [
                'name' => 'Kartu Indonesia Sehat (KIS)',
                'category' => 'health',
                'amount' => 0,
                'provider' => 'BPJS Kesehatan',
                'description' => 'Jaminan kesehatan yang dikelola oleh BPJS Kesehatan untuk masyarakat kurang mampu',
                'is_available' => true,
            ],
            [
                'name' => 'Kartu Indonesia Pintar (KIP)',
                'category' => 'health',
                'amount' => 450000,
                'provider' => 'Kementerian Pendidikan dan Kebudayaan',
                'description' => 'Bantuan biaya pendidikan untuk anak usia sekolah dari keluarga kurang mampu',
                'is_available' => true,
            ],
            [
                'name' => 'Bantuan Langsung Tunai (BLT)',
                'category' => 'cash',
                'amount' => 600000,
                'provider' => 'Pemerintah Daerah',
                'description' => 'Bantuan langsung tunai untuk masyarakat terdampak ekonomi',
                'is_available' => true,
            ],
            [
                'name' => 'Subsidi Pupuk Petani',
                'category' => 'subsidized',
                'amount' => 300000,
                'provider' => 'Kementerian Pertanian',
                'description' => 'Subsidi pupuk untuk petani terdaftar guna meningkatkan produktivitas',
                'is_available' => true,
            ],
            [
                'name' => 'Bantuan Produktif Usaha Mikro (BPUM)',
                'category' => 'cash',
                'amount' => 2400000,
                'provider' => 'Kementerian Koperasi dan UKM',
                'description' => 'Bantuan modal usaha untuk pelaku usaha mikro yang terdampak pandemi',
                'is_available' => false,
            ],
            [
                'name' => 'Subsidi Perumahan Rakyat',
                'category' => 'subsidized',
                'amount' => 4000000,
                'provider' => 'Kementerian PUPR',
                'description' => 'Subsidi uang muka untuk pembelian rumah bagi masyarakat berpenghasilan rendah',
                'is_available' => true,
            ],
            [
                'name' => 'Program Sembako Murah',
                'category' => 'staple',
                'amount' => 150000,
                'provider' => 'Pemerintah Daerah',
                'description' => 'Program penyediaan sembako dengan harga terjangkau untuk warga kurang mampu',
                'is_available' => true,
            ],
        ];

        $bansosImages = [
            'assets/data-seeder/thumbnails/kk-bansos-1.png',
            'assets/data-seeder/thumbnails/kk-bansos-2.png',
            'assets/data-seeder/thumbnails/kk-bansos-3.png',
            'assets/data-seeder/thumbnails/kk-bansos-4.png',
            'assets/data-seeder/thumbnails/kk-bansos-5.png',
            'assets/data-seeder/thumbnails/thumbnail-bansos-preview.svg',
        ];

        $index = 0;
        foreach ($programs as $program) {
            SocialAssistance::create([
                'thumbnail' => $bansosImages[$index % count($bansosImages)],
                'name' => $program['name'],
                'category' => $program['category'],
                'amount' => $program['amount'],
                'provider' => $program['provider'],
                'description' => $program['description'],
                'is_available' => $program['is_available'],
            ]);
            $index++;
        }
    }
}
