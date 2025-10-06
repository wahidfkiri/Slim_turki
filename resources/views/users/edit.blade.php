@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Modifier l'utilisateur</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Utilisateurs</a></li>
                        <li class="breadcrumb-item active">Modifier</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Modifier les informations de l'utilisateur</h3>
                        </div>
                        <!-- form start -->
                        <form action="{{ route('users.update', $user) }}" method="POST" id="userForm">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <!-- Nom -->
                                <div class="form-group">
                                    <label for="name">Nom complet *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" 
                                           placeholder="Entrez le nom complet" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="form-group">
                                    <label for="email">Adresse email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" 
                                           placeholder="Entrez l'adresse email" required>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Mot de passe -->
                                <div class="form-group">
                                    <label for="password">Mot de passe</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" 
                                           placeholder="Laissez vide pour ne pas modifier">
                                    <small class="form-text text-muted">
                                        Laissez ce champ vide si vous ne souhaitez pas modifier le mot de passe.
                                    </small>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Confirmation mot de passe -->
                                <div class="form-group">
                                    <label for="password_confirmation">Confirmer le mot de passe</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" 
                                           placeholder="Confirmez le mot de passe">
                                </div>

                                <!-- Fonction -->
                                <div class="form-group">
                                    <label for="fonction">Fonction *</label>
                                    <select class="form-control @error('fonction') is-invalid @enderror" 
                                            id="fonction" name="fonction" required>
                                        <option value="">Sélectionnez une fonction</option>
                                        <option value="admin" {{ old('fonction', $user->fonction) == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                        <option value="avocat" {{ old('fonction', $user->fonction) == 'avocat' ? 'selected' : '' }}>Avocat</option>
                                        <option value="secrétaire" {{ old('fonction', $user->fonction) == 'secrétaire' ? 'selected' : '' }}>Secrétaire</option>
                                        <option value="clerc" {{ old('fonction', $user->fonction) == 'clerc' ? 'selected' : '' }}>Clerc</option>
                                        <option value="stagiaire" {{ old('fonction', $user->fonction) == 'stagiaire' ? 'selected' : '' }}>Stagiaire</option>
                                    </select>
                                    @error('fonction')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                               <!-- Rôles -->
<div class="form-group">
    <label for="roles">Rôle *</label>
    <select class="form-control @error('roles') is-invalid @enderror" 
            id="roles" name="roles"
            data-placeholder="Sélectionnez un rôle" style="width: 100%;" required>
        <option value="">Sélectionnez un rôle</option>
        @foreach($roles as $role)
            @php
                // Récupérer le premier rôle de l'utilisateur (puisqu'il n'en a qu'un)
                $userRole = $user->roles->first();
                $isSelected = old('roles', $userRole ? $userRole->name : '') == $role->name;
            @endphp
            <option value="{{ $role->name }}" {{ $isSelected ? 'selected' : '' }}>
                {{ $role->name }}
            </option>
        @endforeach
    </select>
    @error('roles')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

                               <!-- Permissions/Privilèges -->
<div class="form-group">
    <label>Privilèges/Permissions</label>
    <div class="permissions-container" style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
        <div class="row">
            @php
                // Récupérer toutes les permissions depuis la base de données
                $allPermissions = \Spatie\Permission\Models\Permission::all();
                
                // Grouper les permissions par module
                $permissionsByModule = [];
                $moduleLabels = [
                    'users' => 'Utilisateurs',
                    'dossiers' => 'Dossiers',
                    'intervenants' => 'Intervenants',
                    'factures' => 'Factures',
                    'agendas' => 'Agendas',
                    'tasks' => 'Tâches',
                    'timesheets' => 'Feuilles de temps',
                    'admin' => 'Administration'
                ];
                
                foreach ($allPermissions as $permission) {
                    $permissionName = $permission->name;
                    
                    // Extraire le module du nom de permission
                    $parts = explode('_', $permissionName);
                    $action = $parts[0];
                    $module = $parts[1] ?? '';
                    
                    // Gérer les cas spéciaux pour les permissions d'administration
                    if (in_array($permissionName, ['access_admin_panel', 'manage_settings', 'view_reports', 'export_data'])) {
                        $module = 'admin';
                    }
                    
                    if ($module && isset($moduleLabels[$module])) {
                        $moduleLabel = $moduleLabels[$module];
                        if (!isset($permissionsByModule[$moduleLabel])) {
                            $permissionsByModule[$moduleLabel] = [];
                        }
                        $permissionsByModule[$moduleLabel][] = $permission;
                    }
                }
                
                // Labels pour les actions
                $actionLabels = [
                    'view' => 'Voir',
                    'create' => 'Créer',
                    'edit' => 'Modifier',
                    'delete' => 'Supprimer',
                    'access' => 'Accéder',
                    'manage' => 'Gérer'
                ];
                
                // Labels spéciaux pour certaines permissions
                $specialLabels = [
                    'access_admin_panel' => 'Accès panel admin',
                    'manage_settings' => 'Gérer paramètres',
                    'view_reports' => 'Voir rapports',
                    'export_data' => 'Exporter données'
                ];
                
                $userPermissions = $user->permissions->pluck('name')->toArray();
                             
                $permissionsToCheck = old('permissions', $userPermissions);
            @endphp

            @foreach($permissionsByModule as $module => $modulePermissions)
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 font-weight-bold">{{ $module }}</h6>
                        </div>
                        <div class="card-body p-2">
                            @foreach($modulePermissions as $permission)
                                @php
                                    $permissionName = $permission->name;
                                    
                                    // Déterminer le label à afficher
                                    if (isset($specialLabels[$permissionName])) {
                                        $permissionDisplay = $specialLabels[$permissionName];
                                    } else {
                                        $parts = explode('_', $permissionName);
                                        $action = $parts[0];
                                        $resource = implode(' ', array_slice($parts, 1));
                                        
                                        $actionLabel = $actionLabels[$action] ?? ucfirst($action);
                                        $permissionDisplay = $actionLabel . ' ' . $resource;
                                    }
                                    
                                    // Vérifier si la permission doit être cochée
                                    $isChecked = in_array($permissionName, $permissionsToCheck);
                                @endphp
                                <div class="custom-control custom-checkbox mb-2">
                                    <input class="custom-control-input permission-checkbox" 
                                           type="checkbox" 
                                           id="permission_{{ $permission->id }}" 
                                           name="permissions[]" 
                                           value="{{ $permission->name }}"
                                           {{ $isChecked ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="permission_{{ $permission->id }}">
                                        {{ ucfirst($permissionDisplay) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @error('permissions')
        <span class="invalid-feedback d-block" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

                                <!-- Statut -->
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" 
                                               id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Utilisateur actif</label>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> Mettre à jour
                                </button>
                                <a href="{{ route('users.index') }}" class="btn btn-default btn-lg">
                                    <i class="fas fa-arrow-left"></i> Retour à la liste
                                </a>
                                
                                @can('delete_users')
                                    <button type="button" class="btn btn-danger btn-lg float-right" 
                                            onclick="confirmDelete({{ $user->id }})">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                @endcan
                            </div>
                        </form>
                        
                        <!-- Formulaire de suppression -->
                        @can('delete_users')
                            <form id="delete-form-{{ $user->id }}" 
                                  action="{{ route('users.destroy', $user) }}" 
                                  method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endcan
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<style>
    .select2-container .select2-selection--multiple {
        min-height: 38px;
    }
    .select2-container .select2-selection--single {
        height: 38px;
    }
    .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }
    .permissions-container .card-header {
        cursor: pointer;
        background-color: #f8f9fa !important;
        transition: background-color 0.3s;
    }
    .permissions-container .card-header:hover {
        background-color: #e9ecef !important;
    }
    .permissions-container .card {
        border: 1px solid #dee2e6;
    }
    .permissions-container .custom-control-label {
        font-size: 0.9rem;
    }
    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1.1rem;
    }
</style>
@endsection