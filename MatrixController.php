<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Sector;
use App\Models\WeightSetting;
use App\Services\PortfolioAnalysisService;
use Illuminate\Http\Request;

class MatrixController extends Controller
{
    public function index(Request $request, PortfolioAnalysisService $analysis)
    {
        $country = strtoupper($request->string('country', 'MX'));
        if (! in_array($country, PortfolioAnalysisService::TARGETS, true)) {
            $country = 'MX';
        }

        $sectors = Sector::withCount([
            'events as local_count' => fn ($q) => $q->where('country', $country),
            'events as external_count' => fn ($q) => $q->where('country', '!=', $country),
        ])->orderBy('name')->get();

        // Garante uma avaliacao (default neutro 3/3/3/3) para cada setor neste pais.
        foreach ($sectors as $sector) {
            Assessment::firstOrCreate(['sector_id' => $sector->id, 'country' => $country]);
        }

        $weights = WeightSetting::current();
        $assessments = Assessment::where('country', $country)->get()->keyBy('sector_id');

        return view('matrix.index', [
            'country' => $country,
            'sectors' => $sectors,
            'assessments' => $assessments,
            'weights' => $weights,
            'analysis' => $analysis,
        ]);
    }

    public function saveAssessments(Request $request)
    {
        $data = $request->validate([
            'country' => ['required', 'in:BR,MX'],
            'rows' => ['required', 'array'],
            'rows.*.market_potential' => ['required', 'integer', 'between:1,5'],
            'rows.*.competition_intensity' => ['required', 'integer', 'between:1,5'],
            'rows.*.audience_access' => ['required', 'integer', 'between:1,5'],
            'rows.*.internal_strength' => ['required', 'integer', 'between:1,5'],
            'rows.*.notes' => ['nullable', 'string', 'max:2000'],
        ]);

        foreach ($data['rows'] as $sectorId => $row) {
            Assessment::updateOrCreate(
                ['sector_id' => (int) $sectorId, 'country' => $data['country']],
                $row
            );
        }

        return redirect()->route('matrix.index', ['country' => $data['country']])
            ->with('ok', 'Avaliacoes salvas. Reexecute a analise para atualizar os vereditos.');
    }

    public function saveWeights(Request $request)
    {
        $data = $request->validate([
            'w_potential' => ['required', 'integer', 'between:0,100'],
            'w_competition' => ['required', 'integer', 'between:0,100'],
            'w_audience' => ['required', 'integer', 'between:0,100'],
            'w_internal' => ['required', 'integer', 'between:0,100'],
            'go_threshold' => ['required', 'integer', 'between:1,100'],
            'review_threshold' => ['required', 'integer', 'between:0,99', 'lt:go_threshold'],
        ]);

        if ($data['w_potential'] + $data['w_competition'] + $data['w_audience'] + $data['w_internal'] === 0) {
            return back()->withErrors(['w_potential' => 'A soma dos pesos nao pode ser zero.']);
        }

        WeightSetting::current()->update($data);

        return back()->with('ok', 'Pesos e limiares atualizados. Reexecute a analise.');
    }
}
