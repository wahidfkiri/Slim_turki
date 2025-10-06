<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use DB;

class UserController extends Controller
{
    public function index()
    {
       $this->authorize('view_users', User::class);
        if(auth()->user()->hasRole('admin')){
        $users = User::with('roles')->where('id', '!=', auth()->id())->get();
        $roles = Role::all();
        return view('users.index', compact('users','roles'));
        }
        return redirect()->back();
        
    }

    public function create()
    {
       $this->authorize('create_users', User::class);

        $roles = Role::all();

        return view('users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
{
   $this->authorize('create_users', User::class);
    
    try {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        
        // Utiliser une transaction pour s'assurer de l'intégrité des données
        DB::transaction(function () use ($validated, $request) {
            $user = User::create($validated);
            
            // Synchroniser les rôles
            if ($request->has('roles')) {
                $user->syncRoles($request->roles);
            }

            // Synchroniser les permissions directes
            if ($request->has('permissions')) {
                $user->syncPermissions($request->permissions);
            }
        });

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé avec succès.');

    } catch (\Exception $e) {
        Log::error('Erreur lors de la création de l\'utilisateur: ' . $e->getMessage());
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Une erreur est survenue lors de la création de l\'utilisateur.');
    }
}

    public function show(User $user): UserResource
    {
        $this->authorize('view', $user);
        
        return new UserResource($user->load('roles', 'permissions', 'dossiers'));
    }

    public function edit(User $user)
{
    $this->authorize('edit_users', $user);
    
    $roles = Role::all();
    return view('users.edit', compact('user', 'roles'));
}

    public function update(UpdateUserRequest $request, User $user)
{
    $this->authorize('edit_users', $user);
    
    $validated = $request->validated();
    
    // Ne mettre à jour le mot de passe que s'il est fourni
    if ($request->filled('password')) {
        $validated['password'] = Hash::make($validated['password']);
    } else {
        unset($validated['password']);
    }
    
    $user->update($validated);
    
    // Synchroniser les rôles
    if ($request->has('roles')) {
        $user->syncRoles($request->roles);
    }

    // Synchroniser les permissions directes
    if ($request->has('permissions')) {
        $user->syncPermissions($request->permissions);
    } else {
        // Si aucune permission n'est sélectionnée, supprimer toutes les permissions directes
        $user->syncPermissions([]);
    }

    return redirect()->route('users.index')
        ->with('success', 'Utilisateur mis à jour avec succès.');
}

    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete_users', $user);
        
        // Empêcher la suppression de son propre compte
        if ($user->id === auth()->id()) {
            return response()->json([
                'message' => 'Vous ne pouvez pas supprimer votre propre compte.'
            ], 403);
        }
        
        $user->delete();
        
        return response()->json([
            'message' => 'Utilisateur supprimé avec succès.'
        ], 200);
    }

    public function search(Request $request): AnonymousResourceCollection
    {
        $query = User::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('fonction', 'like', "%{$search}%");
        }
        
        if ($request->has('fonction')) {
            $query->where('fonction', $request->fonction);
        }
        
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }
        
        $users = $query->with('roles')->paginate(10);
        
        return UserResource::collection($users);
    }
}