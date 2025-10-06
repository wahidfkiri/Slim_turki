<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\Facture;
use App\Models\Intervenant;
use App\Models\Agenda;
use App\Models\Task;
use App\Models\TimeSheet;
use App\Models\Domaine;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->hasRole('admin')){
        $stats = $this->getStats();
        $ca_mois = $this->getChiffreAffairesMois();
        $domaines = $this->getDossiersParDomaine();
        $dossiers_recents = $this->getDossiersRecents();
        $evenements = $this->getEvenementsProchains();
        }else{
            return redirect()->route('dossiers.index');
        }

        return view('home', compact('stats', 'ca_mois', 'domaines', 'dossiers_recents', 'evenements'));
    }

    private function getStats()
    {
        return [
            'total_dossiers' => Dossier::where('archive', false)->count(),
            'dossiers_contentieux' => Dossier::where('contentieux', true)->where('archive', false)->count(),
            'chiffre_affaires' => Facture::sum('montant'),
            'factures_impayees' => Facture::where('statut', 'non_payé')->count(),
            'taches_en_cours' => Task::whereIn('statut', ['a_faire', 'en_cours'])->count(),
            'evenements_semaine' => Agenda::whereBetween('date_debut', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'clients_actifs' => Intervenant::where('categorie', 'client')->where('archive', false)->count(),
            'heures_mois' => TimeSheet::whereMonth('date_timesheet', now()->month)
                ->sum('quantite')
        ];
    }

    private function getChiffreAffairesMois()
    {
        $months = [];
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->locale('fr')->translatedFormat('M Y');
            $months[] = $monthName;
            
            $total = Facture::whereYear('date_emission', $date->year)
                ->whereMonth('date_emission', $date->month)
                ->sum('montant');
            
            $data[] = floatval($total); // Assurer que c'est un float pour Chart.js
        }

        return [
            'labels' => $months,
            'data' => $data
        ];
    }

    private function getDossiersParDomaine()
    {
        $domaines = Domaine::withCount(['dossiers' => function($query) {
            $query->where('archive', false);
        }])
        ->having('dossiers_count', '>', 0)
        ->get();

        // Assigner des couleurs
        $colors = [
            '#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', 
            '#d2d6de', '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
            '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF', '#7AC29A',
            '#6d4c41', '#3949ab', '#5e35b1', '#8e24aa', '#d81b60'
        ];
        
        foreach ($domaines as $index => $domaine) {
            $domaine->color = $colors[$index % count($colors)];
        }

        return $domaines;
    }

    private function getDossiersRecents()
    {
        return Dossier::with('domaine')
            ->where('archive', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    private function getEvenementsProchains()
    {
        return Agenda::with('dossier')
            ->where('date_debut', '>=', now()->startOfDay())
            ->orderBy('date_debut')
            ->limit(5)
            ->get();
    }
}