<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Event;
use App\Models\Opportunity;
use App\Models\Sector;
use App\Models\WeightSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Motor de analise de portfolio BR x MX.
 *
 * Honestidade metodologica: o score e uma media ponderada de julgamentos (1-5)
 * definidos pelo time na Matriz. Ele nao "descobre" mercado — ele torna o
 * julgamento comparavel, auditavel e consistente. Pesos e limiares sao
 * configuraveis em WeightSetting.
 */
class PortfolioAnalysisService
{
    public const TARGETS = ['BR', 'MX'];

    /**
     * Reexecuta a analise completa e materializa as oportunidades.
     * Retorna o numero de oportunidades geradas.
     */
    public function run(): int
    {
        $weights = WeightSetting::current();
        $sectors = Sector::with('events')->orderBy('name')->get();
        $assessments = Assessment::all()->keyBy(fn ($a) => $a->sector_id.'|'.$a->country);

        Opportunity::query()->delete();
        $generated = 0;

        foreach ($sectors as $sector) {
            foreach (self::TARGETS as $target) {
                $assessment = $assessments->get($sector->id.'|'.$target);
                if (! $assessment) {
                    continue; // setor ainda nao avaliado para este pais — aparece como pendencia na Matriz
                }

                [$score, $breakdown] = $this->score($assessment, $weights);
                $classification = $this->classify($sector, $target, $assessment);

                Opportunity::create([
                    'sector_id' => $sector->id,
                    'country' => $target,
                    'opportunity_type' => $classification['type'],
                    'verdict' => $this->verdict($score, $weights),
                    'score' => $score,
                    'rationale' => $classification['rationale']
                        .($assessment->notes ? ' Nota da avaliacao: '.$assessment->notes : ''),
                    'score_breakdown' => $breakdown,
                    'anchor_event_id' => $classification['anchor']?->id,
                ]);
                $generated++;
            }
        }

        return $generated;
    }

    /**
     * Score 0-100. Concorrencia e invertida (6 - intensidade): quanto mais
     * intensa a concorrencia, menor a contribuicao.
     *
     * @return array{0: float, 1: string}
     */
    public function score(Assessment $a, WeightSetting $w): array
    {
        $competitionValue = 6 - $a->competition_intensity;

        $sum = $w->w_potential * $a->market_potential
            + $w->w_competition * $competitionValue
            + $w->w_audience * $a->audience_access
            + $w->w_internal * $a->internal_strength;

        $max = 5 * $w->totalWeight();
        $score = $max > 0 ? round($sum / $max * 100, 1) : 0.0;

        $breakdown = sprintf(
            'Potencial %d x %d | Concorrencia invertida (6-%d=%d) x %d | Publico %d x %d | Forca interna %d x %d | Total %d / %d = %s',
            $a->market_potential, $w->w_potential,
            $a->competition_intensity, $competitionValue, $w->w_competition,
            $a->audience_access, $w->w_audience,
            $a->internal_strength, $w->w_internal,
            $sum, $max, number_format($score, 1)
        );

        return [$score, $breakdown];
    }

    public function verdict(float $score, WeightSetting $w): string
    {
        return match (true) {
            $score >= $w->go_threshold => 'GO',
            $score >= $w->review_threshold => 'REVIEW',
            default => 'NO-GO',
        };
    }

    /**
     * Classifica o tipo de oportunidade a partir da cobertura do portfolio:
     * - sem evento local + irmao LATAM cobre  -> geo_clone
     * - sem evento local + marca global cobre -> brand_import
     * - sem evento em lugar nenhum            -> new_event (greenfield)
     * - coberto local + marca externa forte   -> co_location
     * - coberto local, 2+ eventos soltos      -> co_location interna
     * - coberto local, cluster ja existente   -> portfolio_adjustment
     *
     * @return array{type: string, rationale: string, anchor: ?Event}
     */
    public function classify(Sector $sector, string $target, Assessment $assessment): array
    {
        $events = $sector->events;
        $local = $events->where('country', $target)->values();
        $sibling = $events->where('country', $target === 'BR' ? 'MX' : 'BR')->values();
        $external = $events->whereNotIn('country', self::TARGETS)->values();
        $countryName = $target === 'BR' ? 'Brasil' : 'Mexico';
        $siblingName = $target === 'BR' ? 'Mexico' : 'Brasil';

        if ($local->isEmpty()) {
            if ($sibling->isNotEmpty()) {
                $anchor = $sibling->first();
                return [
                    'type' => 'geo_clone',
                    'anchor' => $anchor,
                    'rationale' => "White space: nenhum evento do setor {$sector->name} no portfolio {$countryName}. "
                        ."O setor ja e coberto no {$siblingName} por \"{$anchor->name}\" — candidato natural a geo-clone, "
                        .'aproveitando marca, conteudo e base de expositores da casa.',
                ];
            }

            if ($external->isNotEmpty()) {
                $anchor = $this->strongestBrand($external);
                $family = Str::before($anchor->name, ' (');
                return [
                    'type' => 'brand_import',
                    'anchor' => $anchor,
                    'rationale' => "White space: setor {$sector->name} sem cobertura no {$countryName}, mas a casa opera a marca "
                        ."\"{$family}\" em outras geografias ({$this->geographies($external)}). "
                        .'Importar a marca reduz risco de lancamento versus criar do zero.',
                ];
            }

            return [
                'type' => 'new_event',
                'anchor' => null,
                'rationale' => "Setor {$sector->name} sem ancora interna em nenhuma geografia do portfolio carregado. "
                    .'Lancamento greenfield ou aquisicao: maior risco, depende inteiramente do potencial de mercado avaliado.',
            ];
        }

        // Setor coberto localmente.
        $localAnchor = $local->first();

        if ($external->isNotEmpty()) {
            $candidate = $this->strongestBrand($external);
            $candidateFamily = Str::before($candidate->name, ' (');
            $localFamilies = $local->map(fn ($e) => Str::lower(Str::before($e->name, ' ')))->all();

            if (! in_array(Str::lower(Str::before($candidate->name, ' ')), $localFamilies, true)) {
                return [
                    'type' => 'co_location',
                    'anchor' => $localAnchor,
                    'rationale' => "Setor {$sector->name} ja coberto no {$countryName} por \"{$localAnchor->name}\". "
                        ."Sinergia: co-locar a marca global \"{$candidateFamily}\" (presente em {$this->geographies($external)}) "
                        .'junto ao evento local para ampliar o cluster sem custo integral de lancamento.',
                ];
            }
        }

        if ($local->count() >= 2) {
            [$a, $b] = [$local[0], $local[1]];
            if ($this->alreadyClustered($a, $b)) {
                return [
                    'type' => 'portfolio_adjustment',
                    'anchor' => $a,
                    'rationale' => "\"{$a->name}\" e \"{$b->name}\" ja operam co-locados ({$a->facility}). "
                        .'Cluster consolidado: a alavanca aqui e expansao de pavilhao, verticais adjacentes ou pricing — nao novo evento.',
                ];
            }

            return [
                'type' => 'co_location',
                'anchor' => $a,
                'rationale' => "O {$countryName} tem {$local->count()} eventos do setor {$sector->name} em datas/locais distintos "
                    ."(ex.: \"{$a->name}\" e \"{$b->name}\"). Avaliar co-location para concentrar publico e reduzir custo de operacao.",
            ];
        }

        return [
            'type' => 'portfolio_adjustment',
            'anchor' => $localAnchor,
            'rationale' => "Setor {$sector->name} coberto no {$countryName} apenas por \"{$localAnchor->name}\". "
                .'Decisao e de fortalecimento: expandir o evento existente, criar edicao regional ou manter como esta.',
        ];
    }

    /**
     * Heuristica de marca mais forte: familia de nome (primeira palavra)
     * com mais ocorrencias no portfolio externo.
     */
    private function strongestBrand(Collection $external): Event
    {
        return $external
            ->groupBy(fn (Event $e) => Str::lower(Str::before($e->name, ' ')))
            ->sortByDesc(fn (Collection $group) => $group->count())
            ->first()
            ->sortBy('start_date')
            ->first();
    }

    private function geographies(Collection $events): string
    {
        return $events->pluck('country')->unique()->sort()->implode(', ');
    }

    private function alreadyClustered(Event $a, Event $b): bool
    {
        if (! $a->facility || ! $b->facility || ! $a->start_date || ! $b->start_date) {
            return false;
        }

        $sameVenue = Str::lower(trim($a->facility)) === Str::lower(trim($b->facility));
        $overlap = $a->start_date->lte($b->end_date ?? $b->start_date)
            && $b->start_date->lte($a->end_date ?? $a->start_date);

        return $sameVenue && $overlap;
    }
}
