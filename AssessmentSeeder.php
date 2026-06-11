<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Sector;
use Illuminate\Database\Seeder;

/**
 * IMPORTANTE: estes valores sao baseline de partida para o time editar na
 * Matriz — hipoteses de trabalho, nao inteligencia de mercado auditada.
 * Formato: [potencial, concorrencia, publico, forca_interna, nota].
 */
class AssessmentSeeder extends Seeder
{
    private const BASELINE = [
        'MX' => [
            'estetica' => [5, 3, 4, 4, 'AMWC LATAM (Medellin), A4M e The Aesthetic Show dao marca e conteudo da casa; sem evento proprio no MX. Validar congressos medicos locais.'],
            'hotelaria' => [4, 2, 5, 5, 'Abastur e ancora consolidada no MX — alavanca natural para co-locations e verticais adjacentes.'],
            'embalagem' => [4, 5, 4, 4, 'Expo Pack Mexico/Guadalajara (PMMI) domina o setor: entrada frontal e cara. Validar nicho ou parceria antes de qualquer GO.'],
            'farma' => [4, 3, 4, 5, 'Marca CPHI viaja bem entre geografias; mapear calendario de concorrentes locais antes de decidir formato.'],
            'energia' => [4, 3, 3, 4, 'Nearshoring e demanda solar/storage; marcas Solar & Storage Finance e Battery Asset Summit sao importaveis.'],
            'saude-medtech' => [4, 3, 4, 4, 'Familias WHX/MEDevice/MD&M dao plataforma; validar densidade de compradores hospitalares no MX.'],
            'ingredientes' => [4, 3, 4, 5, 'Familia Fi/SupplySide forte globalmente e Fi ja opera America do Sul — extensao natural.'],
            'alimentos' => [4, 4, 4, 4, 'Setor grande e concorrido no MX; checar calendario local antes de posicionar.'],
            'manufatura' => [5, 4, 4, 3, 'Nearshoring puxa manufatura. ATENCAO: portfolio MX real da casa nao esta na base fornecida — recarregar antes do comite.'],
            'pop-culture' => [4, 3, 4, 5, 'FAN EXPO escala bem em pracas novas; validar players locais de cultura pop.'],
            'construcao' => [3, 4, 3, 3, 'Concorrencia local relevante; sem ancora interna obvia para o MX.'],
            'nautico' => [2, 2, 2, 4, 'Mercado nautico MX restrito; marcas de boat show da casa nao encontram demanda comparavel.'],
            'varejo' => [3, 3, 3, 4, 'Familia CSP e forte nos EUA; aderencia ao varejo de conveniencia MX precisa de validacao primaria.'],
            'moda' => [3, 3, 3, 4, 'COTERIE como marca importavel; concorrencia local (Intermoda GDL) precisa ser mapeada.'],
        ],
        'BR' => [
            'estetica' => [5, 3, 4, 4, 'Brasil esta entre os maiores mercados de estetica do mundo; casa sem evento proprio no pais. Validar congressos medicos estabelecidos.'],
            'farma' => [4, 4, 4, 5, 'CPHI e lider global; concorrencia local estabelecida (validar FCE Pharma) — posicionamento precisa ser diferenciado.'],
            'materno' => [3, 3, 3, 4, 'CBME (China) e candidato classico a geo-clone; dimensionar varejo materno-infantil BR.'],
            'pop-culture' => [5, 5, 4, 5, 'CCXP domina o mercado BR — entrada frontal de FAN EXPO enfrentaria o lider global da categoria.'],
            'tech' => [4, 4, 4, 4, 'AI Summit e Black Hat sao importaveis; calendario tech BR ja e denso (validar Febraban Tech, Web Summit Rio).'],
            'hotelaria' => [4, 4, 4, 3, 'Setor coberto por players locais fortes; sem ancora da casa no BR na base atual.'],
            'energia' => [4, 3, 4, 5, 'Windpower + Energy Solutions dao cluster; decisao e de expansao, nao de lancamento.'],
            'ingredientes' => [4, 3, 4, 5, 'Fi South America ancora o setor; alavanca para co-location com marcas globais.'],
            'embalagem' => [4, 3, 4, 5, 'Fispal Tecnologia ancora processamento e embalagem; cluster ja consolidado em SP.'],
            'construcao' => [4, 3, 4, 5, 'Concrete Show e ancora; avaliar verticais adjacentes (infra, equipamentos).'],
            'joias' => [3, 4, 3, 4, 'LOUPE expande Asia/Europa; BR tem feiras locais fortes (validar Feninjer) — co-location pode ser atalho.'],
            'varejo' => [4, 4, 4, 3, 'Sem ancora local da casa; APAS Show domina conveniencia/supermercados no BR.'],
            'moveis' => [3, 4, 3, 4, 'Furniture China e marca forte da casa; BR tem polos regionais consolidados (validar ABIMOVEL/feiras locais).'],
            'rh' => [4, 3, 4, 4, 'HRSE e importavel; validar densidade de compradores corporativos e players locais (ex.: CONARH).'],
        ],
    ];

    public function run(): void
    {
        $sectors = Sector::pluck('id', 'slug');

        foreach (self::BASELINE as $country => $rows) {
            foreach ($rows as $slug => [$potential, $competition, $audience, $internal, $note]) {
                if (! isset($sectors[$slug])) {
                    continue;
                }
                Assessment::updateOrCreate(
                    ['sector_id' => $sectors[$slug], 'country' => $country],
                    [
                        'market_potential' => $potential,
                        'competition_intensity' => $competition,
                        'audience_access' => $audience,
                        'internal_strength' => $internal,
                        'notes' => $note.' [Baseline do seed — validar]',
                    ]
                );
            }
        }
    }
}
