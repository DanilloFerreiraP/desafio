<?php

namespace Database\Seeders;

use App\Models\HealthUnit;
use Illuminate\Database\Seeder;

class HealthUnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            [
                'name' => 'UBS Centro',
                'city' => 'Juazeiro do Norte',
            ],
            [
                'name' => 'UBS Lagoa Seca',
                'city' => 'Juazeiro do Norte',
            ],
            [
                'name' => 'UBS Salesianos',
                'city' => 'Juazeiro do Norte',
            ],
            [
                'name' => 'UBS Betolândia',
                'city' => 'Juazeiro do Norte',
            ],
        ];

        // estou pesquisando primeiro para evitar inserir novamente
        foreach ($units as $unit) {
            HealthUnit::firstOrCreate($unit);
        }
    }
}
