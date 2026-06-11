<?php

namespace App\Http\Controllers;

use App\Services\PortfolioAnalysisService;

class AnalysisController extends Controller
{
    public function run(PortfolioAnalysisService $analysis)
    {
        $count = $analysis->run();

        return redirect()->route('opportunities.index')
            ->with('ok', "Analise reexecutada: {$count} recomendacoes geradas.");
    }
}
