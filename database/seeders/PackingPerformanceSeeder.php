<?php

namespace Database\Seeders;

use App\Models\PackingPerformance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackingPerformanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PackingPerformance::factory()->count(10)->pic1()->create();
        PackingPerformance::factory()->count(10)->pic2()->create();
        PackingPerformance::factory()->count(10)->pic3()->create();
    }
}
