<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Domaine;
use Illuminate\Support\Facades\DB;

class DomaineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Désactiver les contraintes de clé étrangère temporairement
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Domaine::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $domaines = [
            ['nom' => 'Droit Civil', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Droit Commercial', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Droit Pénal', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Droit Social', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Droit Administratif', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Droit Fiscal', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Droit Immobilier', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Droit de la Famille', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Droit des Successions', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Droit des Contrats', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Droit des Sociétés', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Droit de la Consommation', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Droit Bancaire et Financier', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Droit de la Propriété Intellectuelle', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Droit International', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Droit Maritime', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Droit de l\'Environnement', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Droit des Assurances', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Droit des Technologies de l\'Information', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Droit Médical', 'created_at' => now(), 'updated_at' => now()],
        ];

        // Insérer les données
        Domaine::insert($domaines);

        $this->command->info('Table Domaine peuplée avec ' . count($domaines) . ' enregistrements.');
    }
}