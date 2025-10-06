<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Domaine;
use App\Models\SousDomaine;
use Illuminate\Support\Facades\DB;

class SousDomaineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Désactiver les contraintes de clé étrangère temporairement
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        SousDomaine::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Récupérer les domaines
        $droitCivil = Domaine::where('nom', 'Droit Civil')->first();
        $droitCommercial = Domaine::where('nom', 'Droit Commercial')->first();
        $droitPenal = Domaine::where('nom', 'Droit Pénal')->first();
        $droitSocial = Domaine::where('nom', 'Droit Social')->first();
        $droitAdministratif = Domaine::where('nom', 'Droit Administratif')->first();
        $droitFiscal = Domaine::where('nom', 'Droit Fiscal')->first();
        $droitImmobilier = Domaine::where('nom', 'Droit Immobilier')->first();
        $droitFamille = Domaine::where('nom', 'Droit de la Famille')->first();
        $droitSuccessions = Domaine::where('nom', 'Droit des Successions')->first();
        $droitContrats = Domaine::where('nom', 'Droit des Contrats')->first();
        $droitSocietes = Domaine::where('nom', 'Droit des Sociétés')->first();

        $sousDomaines = [
            // Droit Civil
            ['domaine_id' => $droitCivil->id, 'nom' => 'Responsabilité civile', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitCivil->id, 'nom' => 'Droit des obligations', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitCivil->id, 'nom' => 'Droit des biens', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitCivil->id, 'nom' => 'Droit des personnes', 'created_at' => now(), 'updated_at' => now()],
            
            // Droit Commercial
            ['domaine_id' => $droitCommercial->id, 'nom' => 'Droit commercial général', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitCommercial->id, 'nom' => 'Procédures collectives', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitCommercial->id, 'nom' => 'Concurrence et distribution', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitCommercial->id, 'nom' => 'Transport et logistique', 'created_at' => now(), 'updated_at' => now()],
            
            // Droit Pénal
            ['domaine_id' => $droitPenal->id, 'nom' => 'Droit pénal général', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitPenal->id, 'nom' => 'Droit pénal des affaires', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitPenal->id, 'nom' => 'Procédure pénale', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitPenal->id, 'nom' => 'Droit pénal international', 'created_at' => now(), 'updated_at' => now()],
            
            // Droit Social
            ['domaine_id' => $droitSocial->id, 'nom' => 'Droit du travail', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitSocial->id, 'nom' => 'Droit de la sécurité sociale', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitSocial->id, 'nom' => 'Droit de la protection sociale', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitSocial->id, 'nom' => 'Relations collectives du travail', 'created_at' => now(), 'updated_at' => now()],
            
            // Droit Administratif
            ['domaine_id' => $droitAdministratif->id, 'nom' => 'Contentieux administratif', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitAdministratif->id, 'nom' => 'Droit des marchés publics', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitAdministratif->id, 'nom' => 'Droit de l\'urbanisme', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitAdministratif->id, 'nom' => 'Droit de la fonction publique', 'created_at' => now(), 'updated_at' => now()],
            
            // Droit Fiscal
            ['domaine_id' => $droitFiscal->id, 'nom' => 'Fiscalité des entreprises', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitFiscal->id, 'nom' => 'Fiscalité des particuliers', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitFiscal->id, 'nom' => 'Fiscalité internationale', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitFiscal->id, 'nom' => 'Contentieux fiscal', 'created_at' => now(), 'updated_at' => now()],
            
            // Droit Immobilier
            ['domaine_id' => $droitImmobilier->id, 'nom' => 'Transaction immobilière', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitImmobilier->id, 'nom' => 'Promotion immobilière', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitImmobilier->id, 'nom' => 'Construction et urbanisme', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitImmobilier->id, 'nom' => 'Copropriété', 'created_at' => now(), 'updated_at' => now()],
            
            // Droit de la Famille
            ['domaine_id' => $droitFamille->id, 'nom' => 'Mariage et divorce', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitFamille->id, 'nom' => 'Filiation et adoption', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitFamille->id, 'nom' => 'Autorité parentale', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitFamille->id, 'nom' => 'Obligation alimentaire', 'created_at' => now(), 'updated_at' => now()],
            
            // Droit des Successions
            ['domaine_id' => $droitSuccessions->id, 'nom' => 'Successions légales', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitSuccessions->id, 'nom' => 'Testaments et donations', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitSuccessions->id, 'nom' => 'Liquidation de succession', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitSuccessions->id, 'nom' => 'Règlement des indivisions', 'created_at' => now(), 'updated_at' => now()],
            
            // Droit des Contrats
            ['domaine_id' => $droitContrats->id, 'nom' => 'Contrats civils', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitContrats->id, 'nom' => 'Contrats commerciaux', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitContrats->id, 'nom' => 'Contrats internationaux', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitContrats->id, 'nom' => 'Résolution des litiges contractuels', 'created_at' => now(), 'updated_at' => now()],
            
            // Droit des Sociétés
            ['domaine_id' => $droitSocietes->id, 'nom' => 'Création de sociétés', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitSocietes->id, 'nom' => 'Fusion et acquisition', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitSocietes->id, 'nom' => 'Droit des groupes de sociétés', 'created_at' => now(), 'updated_at' => now()],
            ['domaine_id' => $droitSocietes->id, 'nom' => 'Conseil aux dirigeants', 'created_at' => now(), 'updated_at' => now()],
        ];

        // Insérer les données
        SousDomaine::insert($sousDomaines);

        $this->command->info('Table SousDomaine peuplée avec ' . count($sousDomaines) . ' enregistrements.');
    }
}