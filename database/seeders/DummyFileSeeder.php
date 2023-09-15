<?php

namespace Database\Seeders;

use App\Models\FileArsips;
use Illuminate\Database\Seeder;

class DummyFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 100; $i++) {
            FileArsips::create([
                'arsip_id' => 7,
                'users_id' => 7,
                'tgl'      => '2023-06-27',
                'file'     => 'Daftar1BulanAgustus2023/270823 04:17:37.pdf'
            ]);
        }
    }
}
