<?php

namespace Database\Seeders;

use App\Models\Arsip;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class ArsipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for ($i = 1; $i <= 20; $i++) {
            Arsip::create([
                'title' => $faker->name(),
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(10),
                'description' => 'Contoh'
            ]);
        }
    }
}
