<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'name' => 'Peringatan HUT RI Ke-81',
                'description' => 'Perayaan kemerdekaan Indonesia dengan berbagai lomba dan hiburan untuk warga desa',
                'price' => 0,
                'date' => '2026-08-17',
                'time' => '08:00:00',
                'is_active' => true,
            ],
            [
                'name' => 'Bersih Desa Gotong Royong',
                'description' => 'Kegiatan kerja bakti membersihkan lingkungan desa bersama seluruh warga',
                'price' => 0,
                'date' => '2026-02-15',
                'time' => '07:00:00',
                'is_active' => true,
            ],
            [
                'name' => 'Pelatihan Keterampilan Usaha Mikro',
                'description' => 'Workshop pengembangan usaha mikro dan kewirausahaan untuk warga desa',
                'price' => 50000,
                'date' => '2026-03-20',
                'time' => '09:00:00',
                'is_active' => true,
            ],
            [
                'name' => 'Posyandu Balita',
                'description' => 'Pemeriksaan kesehatan dan imunisasi untuk balita dan ibu hamil',
                'price' => 0,
                'date' => '2026-01-25',
                'time' => '08:30:00',
                'is_active' => true,
            ],
            [
                'name' => 'Festival Kuliner Desa',
                'description' => 'Pameran dan penjualan makanan khas desa untuk promosi UMKM lokal',
                'price' => 25000,
                'date' => '2026-04-10',
                'time' => '16:00:00',
                'is_active' => true,
            ],
            [
                'name' => 'Musyawarah Rencana Pembangunan (Musrenbang)',
                'description' => 'Forum perencanaan pembangunan desa dengan partisipasi warga',
                'price' => 0,
                'date' => '2026-01-30',
                'time' => '13:00:00',
                'is_active' => true,
            ],
            [
                'name' => 'Senam Sehat Bersama',
                'description' => 'Kegiatan olahraga bersama untuk meningkatkan kesehatan warga',
                'price' => 10000,
                'date' => '2025-12-20',
                'time' => '06:00:00',
                'is_active' => false,
            ],
            [
                'name' => 'Pasar Murah Ramadan',
                'description' => 'Penjualan sembako dengan harga terjangkau menjelang Ramadan',
                'price' => 0,
                'date' => '2026-02-25',
                'time' => '10:00:00',
                'is_active' => true,
            ],
        ];

        $eventThumbnails = [
            'assets/data-seeder/thumbnails/kk-event-desa-1.png',
            'assets/data-seeder/thumbnails/kk-event-desa-2.png',
            'assets/data-seeder/thumbnails/kk-event-desa-3.png',
            'assets/data-seeder/thumbnails/kk-event-desa-4.png',
            'assets/data-seeder/thumbnails/kk-event-desa-5.png',
            'assets/data-seeder/thumbnails/event-image-1.png',
        ];

        $index = 0;
        foreach ($events as $event) {
            Event::create([
                'thumbnail' => $eventThumbnails[$index % count($eventThumbnails)],
                'name' => $event['name'],
                'description' => $event['description'],
                'price' => $event['price'],
                'date' => $event['date'],
                'time' => $event['time'],
                'is_active' => $event['is_active'],
            ]);
            $index++;
        }
    }
}
