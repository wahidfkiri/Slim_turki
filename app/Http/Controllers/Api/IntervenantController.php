<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIntervenantRequest;
use App\Http\Requests\UpdateIntervenantRequest;
use App\Http\Resources\IntervenantResource;
use App\Models\Intervenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\FormeSociale;
use App\Models\IntervenantFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class IntervenantController extends Controller
{
    public function index()
    {
        $formeSociales = FormeSociale::all();
        $intervenants = Intervenant::with(['formeSociale', 'dossiers'])->paginate(10);
        return view('intervenants.index', compact('intervenants', 'formeSociales'));
    }

    public function create()
{
     $formeSociales = FormeSociale::all();
    $intervenants = Intervenant::where('id', '!=', request('id'))->get();
    return view('intervenants.create', compact('formeSociales','intervenants'));
}

 public function edit(Intervenant $intervenant)
{
    $intervenantsLies = $intervenant->intervenantsLies()->pluck('intervenant_lie_id')->toArray();
    $formeSociales = FormeSociale::all();
    $intervenants = Intervenant::where('id', '!=', $intervenant->id)->get();
    return view('intervenants.edit', compact('intervenant','formeSociales','intervenants','intervenantsLies'));
}

   public function store(StoreIntervenantRequest $request)
{
    // Récupérer les données validées (sans piece_jointe)
     $validatedData = $request->validated();
    unset($validatedData['piece_jointe']);
    
    // Créer l'intervenant avec les données validées
    $intervenant = Intervenant::create($validatedData);
    
    // Gestion des fichiers
    if ($request->hasFile('piece_jointe')) {
        $files = $request->file('piece_jointe');
        
        // Créer le dossier pour cet intervenant
        $intervenantFolder = 'intervenants/' . $intervenant->id;
        $storagePath = storage_path('app/public/' . $intervenantFolder);
        
        // S'assurer que le dossier existe
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }
        
        foreach ($files as $file) {
            if ($file->isValid()) {
                // Générer un nom de fichier unique
                $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                
                // Déplacer le fichier vers le dossier de l'intervenant
                $filePath = $file->storeAs($intervenantFolder, $fileName, 'public');
                
                // Enregistrer dans la table IntervenantFile
                IntervenantFile::create([
                    'intervenant_id' => $intervenant->id,
                    'file_path' => $filePath,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'file_type' => $file->getClientMimeType(),
                    'uploaded_at' => now(),
                ]);
            }
        }
    }
    
    // Gestion des intervenants liés
    if ($request->has('intervenants_lies')) {
        $intervenantsLies = [];
        foreach ($request->intervenants_lies as $intervenantLieId) {
            $intervenantsLies[$intervenantLieId] = [
                'relation' => 'représente'
            ];
        }
        $intervenant->intervenantsLies()->attach($intervenantsLies);
    }
    
    $intervenants = Intervenant::with(['formeSociale', 'dossiers'])->paginate(10);
    return redirect()->route('intervenants.index', compact('intervenants'))->with('success', 'Intervenant créé avec succès.');
}

    public function show(Intervenant $intervenant)
    {
        //return $intervenant->dossiers;
        return view('intervenants.show', compact('intervenant'));
    }

   public function update(UpdateIntervenantRequest $request, Intervenant $intervenant)
{
    // Récupérer les données validées (sans piece_jointe)
    $validatedData = $request->validated();
    unset($validatedData['piece_jointe']);
    
    // Mettre à jour l'intervenant avec les données validées
    $intervenant->update($validatedData);
    
    // Gestion des nouveaux fichiers
    if ($request->hasFile('piece_jointe')) {
        $files = $request->file('piece_jointe');
        
        // Créer le dossier pour cet intervenant s'il n'existe pas
        $intervenantFolder = 'intervenants/' . $intervenant->id;
        $storagePath = storage_path('app/public/' . $intervenantFolder);
        
        // S'assurer que le dossier existe
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }
        
        foreach ($files as $file) {
            if ($file->isValid()) {
                // Générer un nom de fichier unique
                $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                
                // Déplacer le fichier vers le dossier de l'intervenant
                $filePath = $file->storeAs($intervenantFolder, $fileName, 'public');
                
                // Enregistrer dans la table IntervenantFile
                IntervenantFile::create([
                    'intervenant_id' => $intervenant->id,
                    'file_path' => $filePath,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'file_type' => $file->getClientMimeType(),
                    'uploaded_at' => now(),
                ]);
            }
        }
    }
    
    // Gestion des intervenants liés
    if ($request->has('intervenants_lies')) {
        // Synchroniser les intervenants liés
        $intervenantsLies = [];
        foreach ($request->intervenants_lies as $intervenantLieId) {
            $intervenantsLies[$intervenantLieId] = [
                'relation' => 'représente' // Vous pouvez aussi récupérer la relation depuis le formulaire si nécessaire
            ];
        }
        $intervenant->intervenantsLies()->sync($intervenantsLies);
    } else {
        // Si aucun intervenant lié n'est sélectionné, supprimer toutes les relations
        $intervenant->intervenantsLies()->detach();
    }
    
    // Recharger les relations pour la réponse
    $intervenant->load(['formeSociale', 'dossiers', 'files', 'intervenantsLies']);
    
    return redirect()->route('intervenants.index')->with('success', 'Intervenant modifié avec succès.');
}

    public function destroy(Intervenant $intervenant): JsonResponse
    {
        // Vérifier si l'intervenant est utilisé dans des dossiers ou factures
        if ($intervenant->dossiers()->count() > 0 || $intervenant->factures()->count() > 0) {
            return response()->json([
                'message' => 'Impossible de supprimer cet intervenant car il est associé à des dossiers ou factures.'
            ], 422);
        }
        
        $intervenant->delete();
        
        return response()->json([
            'message' => 'Intervenant supprimé avec succès.'
        ], 200);
    }

    public function search(Request $request): AnonymousResourceCollection
    {
        $query = Intervenant::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('identite_fr', 'like', "%{$search}%")
                  ->orWhere('identite_ar', 'like', "%{$search}%")
                  ->orWhere('mail1', 'like', "%{$search}%");
        }
        
        if ($request->has('categorie')) {
            $query->where('categorie', $request->categorie);
        }
        
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('archive')) {
            $query->where('archive', $request->boolean('archive'));
        }
        
        $intervenants = $query->with(['formeSociale', 'dossiers'])->paginate(10);
        
        return IntervenantResource::collection($intervenants);
    }

    public function attachDossier(Request $request, Intervenant $intervenant): JsonResponse
    {
        $request->validate([
            'dossier_id' => 'required|exists:dossiers,id',
            'role' => 'required|in:client,avocat,avocat_secondaire,adversaire,huissier,notaire,expert,juridiction,administrateur_judiciaire,mandataire_judiciaire,autre'
        ]);
        
        $intervenant->dossiers()->attach($request->dossier_id, [
            'role' => $request->role
        ]);
        
        return response()->json([
            'message' => 'Intervenant attaché au dossier avec succès.'
        ], 200);
    }

    public function destroyFile(IntervenantFile $file)
{
    try {
        // Supprimer le fichier physique
        if (File::exists(storage_path('app/public/' . $file->file_path))) {
            File::delete(storage_path('app/public/' . $file->file_path));
        }
        
        // Supprimer l'enregistrement de la base de données
        $file->delete();

        return redirect()->back()->with(['success' => true, 'message' => 'Fichier supprimé avec succès.']);

    } catch (\Exception $e) {
        return redirect()->back()->with(['success' => false, 'message' => 'Erreur lors de la suppression du fichier.']);
    }
}
}