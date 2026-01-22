<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profile::create([
            'thumbnail' => 'assets/data-seeder/thumbnails/desa-gallery-1.png',
            'name' => 'Desa Maju Sejahtera',
            'about' => 'Desa Maju Sejahtera merupakan desa yang terletak di kaki gunung dengan pemandangan alam yang indah. Desa kami memiliki potensi besar di bidang pertanian, perkebunan, dan pariwisata. Dengan semangat gotong royong yang tinggi, warga desa terus berupaya membangun desa menuju kemajuan yang berkelanjutan. Kami berkomitmen untuk meningkatkan kesejahteraan masyarakat melalui berbagai program pembangunan dan pemberdayaan masyarakat.',
            'headman' => 'H. Sudirman, S.Sos., M.M.',
            'people' => 3250,
            'agricultural_area' => 450.75,
            'total_area' => 875.50,
        ]);
    }
}
