<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\User;
use App\Models\Dossier;
use App\Models\Intervenant;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;


class AgendaController extends Controller
{
    /**
     * Get agendas data for FullCalendar
     */
    public function getAgendasData(Request $request)
    {
        $this->authorize('view_agendas', Agenda::class);

        $query = Agenda::with([
            'dossier:id,numero_dossier',
            'intervenant:id,identite_fr',
            'user:id,name'
        ]);

        if(!auth()->user()->hasRole('admin')){
            $query->where('utilisateur_id', auth()->user()->id);
        }

        // Filtre par catégories
        if ($request->has('categories') && !empty($request->categories)) {
            $categories = explode(',', $request->categories);
            $query->whereIn('categorie', $categories);
        }

        // Filtre par utilisateur
        if ($request->has('utilisateur_id') && !empty($request->utilisateur_id)) {
            $query->where('utilisateur_id', $request->utilisateur_id);
        }

        // Filtre par dossier
        if ($request->has('dossier_id') && !empty($request->dossier_id)) {
            $query->where('dossier_id', $request->dossier_id);
        }

        // Filtre par période (pour FullCalendar)
        if ($request->has('start') && !empty($request->start)) {
            $start = Carbon::parse($request->start);
            $query->where(function($q) use ($start) {
                $q->where('date_fin', '>=', $start->format('Y-m-d'))
                  ->orWhereNull('date_fin');
            });
        }

        if ($request->has('end') && !empty($request->end)) {
            $end = Carbon::parse($request->end);
            $query->where('date_debut', '<=', $end->format('Y-m-d'));
        }

        $agendas = $query->get();

        $events = [];

        foreach ($agendas as $agenda) {
            $event = [
                'id' => $agenda->id,
                'title' => $agenda->titre,
                'start' => $this->formatEventDate($agenda, 'start'),
                'end' => $this->formatEventDate($agenda, 'end'),
                'allDay' => (bool)$agenda->all_day,
                'color' => $agenda->couleur,
                'textColor' => $this->getTextColor($agenda->couleur),
                'extendedProps' => [
                    'categorie' => $agenda->categorie,
                    'description' => $agenda->description,
                    'dossier' => $agenda->dossier ? $agenda->dossier->reference : null,
                    'intervenant' => $agenda->intervenant ? $agenda->intervenant->name : null,
                    'utilisateur' => $agenda->utilisateur ? $agenda->utilisateur->name : null,
                    'heure_debut' => $agenda->heure_debut,
                    'heure_fin' => $agenda->heure_fin,
                ]
            ];

            // Si pas de couleur définie, utiliser les couleurs par défaut par catégorie
            if (!$agenda->couleur || $agenda->couleur == '#3c8dbc') {
                $event['color'] = $this->getCategoryColor($agenda->categorie);
            }

            $events[] = $event;
        }

        return response()->json($events);
    }

    public function getAgendasDataByDossierId(Request $request, $dossierId)
    {
        $this->authorize('view_agendas', Agenda::class);

        $query = Agenda::with([
            'dossier:id,numero_dossier',
            'intervenant:id,identite_fr',
            'user:id,name'
        ])->where('dossier_id', $dossierId);

        if(!auth()->user()->hasRole('admin')){
            $query->where('utilisateur_id', auth()->user()->id);
        }

        // Filtre par catégories
        if ($request->has('categories') && !empty($request->categories)) {
            $categories = explode(',', $request->categories);
            $query->whereIn('categorie', $categories);
        }

        // Filtre par utilisateur
        if ($request->has('utilisateur_id') && !empty($request->utilisateur_id)) {
            $query->where('utilisateur_id', $request->utilisateur_id);
        }

        // Filtre par dossier
        if ($request->has('dossier_id') && !empty($request->dossier_id)) {
            $query->where('dossier_id', $request->dossier_id);
        }

        // Filtre par période (pour FullCalendar)
        if ($request->has('start') && !empty($request->start)) {
            $start = Carbon::parse($request->start);
            $query->where(function($q) use ($start) {
                $q->where('date_fin', '>=', $start->format('Y-m-d'))
                  ->orWhereNull('date_fin');
            });
        }

        if ($request->has('end') && !empty($request->end)) {
            $end = Carbon::parse($request->end);
            $query->where('date_debut', '<=', $end->format('Y-m-d'));
        }

        $agendas = $query->get();

        $events = [];

        foreach ($agendas as $agenda) {
            $event = [
                'id' => $agenda->id,
                'title' => $agenda->titre,
                'start' => $this->formatEventDate($agenda, 'start'),
                'end' => $this->formatEventDate($agenda, 'end'),
                'allDay' => (bool)$agenda->all_day,
                'color' => $agenda->couleur,
                'textColor' => $this->getTextColor($agenda->couleur),
                'extendedProps' => [
                    'categorie' => $agenda->categorie,
                    'description' => $agenda->description,
                    'dossier' => $agenda->dossier ? $agenda->dossier->reference : null,
                    'intervenant' => $agenda->intervenant ? $agenda->intervenant->name : null,
                    'utilisateur' => $agenda->utilisateur ? $agenda->utilisateur->name : null,
                    'heure_debut' => $agenda->heure_debut,
                    'heure_fin' => $agenda->heure_fin,
                ]
            ];

            // Si pas de couleur définie, utiliser les couleurs par défaut par catégorie
            if (!$agenda->couleur || $agenda->couleur == '#3c8dbc') {
                $event['color'] = $this->getCategoryColor($agenda->categorie);
            }

            $events[] = $event;
        }

        return response()->json($events);
    }

    /**
     * Format event date for FullCalendar
     */
    private function formatEventDate(Agenda $agenda, string $type)
    {
        if ($type === 'start') {
            $date = $agenda->date_debut;
            $time = $agenda->heure_debut;
        } else {
            $date = $agenda->date_fin ?: $agenda->date_debut;
            $time = $agenda->heure_fin;
        }

        // Si all_day, retourner seulement la date
        if ($agenda->all_day) {
            return $date->format('Y-m-d');
        }

        // Si pas de time, utiliser minuit ou 23:59:59
        if (!$time) {
            if ($type === 'start') {
                $time = '00:00:00';
            } else {
                $time = '23:59:59';
                // Si c'est la fin et pas de date_fin, ajouter 1 heure à la date_debut
                if (!$agenda->date_fin) {
                    $date = $agenda->date_debut->copy()->addHour();
                    $time = $date->format('H:i:s');
                }
            }
        }

        return $date->format('Y-m-d') . 'T' . $time;
    }

    /**
     * Get category color
     */
    private function getCategoryColor($categorie)
    {
        $colors = [
            'rdv' => '#3c8dbc',      // Blue
            'audience' => '#f39c12', // Orange
            'delai' => '#00a65a',    // Green
            'tache' => '#dd4b39',    // Red
            'autre' => '#605ca8',    // Purple
        ];

        return $colors[$categorie] ?? '#3c8dbc';
    }

    /**
     * Get text color based on background color
     */
    private function getTextColor($backgroundColor)
    {
        // Convert hex to RGB
        $hex = str_replace('#', '', $backgroundColor);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // Calculate luminance
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

        // Return black or white based on luminance
        return $luminance > 0.5 ? '#000000' : '#ffffff';
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view_agendas', Agenda::class);

        $users = User::where('is_active', true)->get();
            $intervenants = Intervenant::all();
        if(auth()->user()->hasRole('admin')) {
            $dossiers = Dossier::all();
        }else{
            $dossiers = Dossier::whereHas('users', function($q){
            $q->where('user_id', auth()->id());
        })->get();
        }

            return view('agendas.index', compact('users', 'dossiers','intervenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_agendas', Agenda::class);
        
        $users = User::where('is_active', true)->get();
        $dossiers = Dossier::all();
        $intervenants = Intervenant::all();
        
        // Pré-remplir la date si fournie en paramètre
        $defaultDate = request()->get('date');
        
        return view('agendas.create', compact('users', 'dossiers', 'intervenants', 'defaultDate'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create_agendas', Agenda::class);
        
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'heure_debut' => 'nullable|date_format:H:i',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'heure_fin' => 'nullable|date_format:H:i',
            'all_day' => 'boolean',
            'dossier_id' => 'nullable|exists:dossiers,id',
            'intervenant_id' => 'nullable|exists:intervenants,id',
            'utilisateur_id' => 'required|exists:users,id',
            'categorie' => 'required|in:rdv,audience,delai,tache,autre',
            'couleur' => 'nullable|string|max:20',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048', // max 2MB
        ]);

        // Si all_day est coché, vider les heures
        if ($request->has('all_day') && $request->all_day) {
            $validated['heure_debut'] = null;
            $validated['heure_fin'] = null;
        }

        // Si pas de couleur fournie, utiliser la couleur par défaut de la catégorie
        if (empty($validated['couleur'])) {
            $validated['couleur'] = $this->getCategoryColor($validated['categorie']);
        }

        if($request->hasFile('file')){
            $file = $request->file('file');
            $path = $file->store('agenda_files', 'public');
            $validated['file_path'] = $path;
            $validated['file_name'] = $file->getClientOriginalName();
        }

        Agenda::create($validated);

        return redirect()->route('agendas.index')
            ->with('success', 'Événement créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Agenda $agenda)
    {
        $this->authorize('view', $agenda);
        
        return view('agendas.show', compact('agenda'));
    }

    /**
     * Show the form for editing the specified resource.
     */
   /**
 * Show the form for editing the specified resource (for AJAX)
 */
public function edit(Agenda $agenda)
{
    $this->authorize('edit_agendas', $agenda);
    
    return response()->json([
        'id' => $agenda->id,
        'titre' => $agenda->titre,
        'description' => $agenda->description,
        'date_debut' => $agenda->date_debut->format('Y-m-d'),
        'heure_debut' => $agenda->heure_debut,
        'date_fin' => $agenda->date_fin ? $agenda->date_fin->format('Y-m-d') : null,
        'heure_fin' => $agenda->heure_fin,
        'all_day' => $agenda->all_day,
        'dossier_id' => $agenda->dossier_id,
        'intervenant_id' => $agenda->intervenant_id,
        'utilisateur_id' => $agenda->utilisateur_id,
        'categorie' => $agenda->categorie,
        'couleur' => $agenda->couleur,
    ]);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Agenda $agenda)
    {
        $this->authorize('edit_agendas', $agenda);
        
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'heure_debut' => 'nullable|date_format:H:i',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'heure_fin' => 'nullable|date_format:H:i',
            'all_day' => 'boolean',
            'dossier_id' => 'nullable|exists:dossiers,id',
            'intervenant_id' => 'nullable|exists:intervenants,id',
            'utilisateur_id' => 'required|exists:users,id',
            'categorie' => 'required|in:rdv,audience,delai,tache,autre',
            'couleur' => 'nullable|string|max:20',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048', // max 2MB
        ]);


        // Si all_day est coché, vider les heures
        if ($request->has('all_day') && $request->all_day) {
            $validated['heure_debut'] = null;
            $validated['heure_fin'] = null;
        }

        if($request->hasFile('file')){
            $file = $request->file('file');
            $path = $file->store('agenda_files', 'public');
            $validated['file_path'] = $path;
            $validated['file_name'] = $file->getClientOriginalName();
        }

        $agenda->update($validated);

        return redirect()->route('agendas.index')
            ->with('success', 'Événement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agenda $agenda)
    {
        $this->authorize('delete_agendas', $agenda);
        
        $agenda->delete();

        // Delete File
        if ($agenda->file_path) {
            Storage::disk('public')->delete($agenda->file_path);
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Événement supprimé avec succès.'
            ]);
        }

        return redirect()->route('agendas.index')
            ->with('success', 'Événement supprimé avec succès.');
    }
}