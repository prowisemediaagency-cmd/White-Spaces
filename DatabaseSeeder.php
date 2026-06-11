<?php

namespace Database\Seeders;

use App\Models\WeightSetting;
use App\Services\PortfolioAnalysisService;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SectorSeeder::class,
            EventSeeder::class,
            AssessmentSeeder::class,
        ]);

        WeightSetting::current();

        // Materializa as recomendacoes ja no primeiro seed.
        app(PortfolioAnalysisService::class)->run();
    }
}
