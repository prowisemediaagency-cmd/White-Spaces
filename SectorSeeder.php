<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    public const SECTORS = [
        'alimentos' => 'Alimentos & Bebidas',
        'ingredientes' => 'Ingredientes & Nutricao',
        'embalagem' => 'Embalagem & Processamento',
        'farma' => 'Farmaceutico',
        'saude-medtech' => 'Saude & Medtech',
        'estetica' => 'Estetica & Longevidade',
        'beleza' => 'Beleza & Cosmeticos',
        'hotelaria' => 'Hotelaria, Foodservice & Turismo',
        'franchising' => 'Franchising',
        'construcao' => 'Construcao',
        'real-estate' => 'Real Estate & Financas',
        'energia' => 'Energia & Renovaveis',
        'agro' => 'Agronegocio & Bio',
        'nautico' => 'Nautico',
        'pop-culture' => 'Pop Culture & Entretenimento',
        'arte' => 'Arte & Design de Consumo',
        'joias' => 'Joias & Luxo',
        'logistica' => 'Logistica & Intermodal',
        'aviacao' => 'Aviacao & Aeroespacial',
        'manufatura' => 'Manufatura Avancada',
        'tech' => 'Tecnologia & IA',
        'rh' => 'RH & Futuro do Trabalho',
        'materno' => 'Materno-Infantil',
        'moveis' => 'Moveis & Design',
        'maritimo' => 'Maritimo & Cruzeiros',
        'varejo' => 'Varejo & Conveniencia',
        'juridico' => 'Juridico & Compliance',
        'agua' => 'Agua & Saneamento',
        'residuos' => 'Residuos & Sustentabilidade',
        'moda' => 'Moda & Textil',
        'marketing' => 'Marketing & Midia',
    ];

    public function run(): void
    {
        foreach (self::SECTORS as $slug => $name) {
            Sector::updateOrCreate(['slug' => $slug], ['name' => $name]);
        }
    }
}
