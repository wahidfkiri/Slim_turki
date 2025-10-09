@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Nouveau Dossier</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dossiers.index') }}">Dossiers</a></li>
                        <li class="breadcrumb-item active">Nouveau Dossier</li>
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
                            <h3 class="card-title">Création d'un nouveau dossier</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ route('dossiers.store') }}" method="POST" id="dossierForm" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <!-- Alert Messages -->
                                @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <!-- Navigation par onglets -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <ul class="nav nav-tabs" id="dossierTabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="generale-tab" data-toggle="tab" href="#generale" role="tab" aria-controls="generale" aria-selected="true">
                                                    <i class="fas fa-info-circle"></i> Générale
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="juridique-tab" data-toggle="tab" href="#juridique" role="tab" aria-controls="juridique" aria-selected="false">
                                                    <i class="fas fa-gavel"></i> Information Juridique
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="intervenants-tab" data-toggle="tab" href="#intervenants" role="tab" aria-controls="intervenants" aria-selected="false">
                                                    <i class="fas fa-users"></i> Intervenants
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="dossiers-tab" data-toggle="tab" href="#dossiers" role="tab" aria-controls="dossiers" aria-selected="false">
                                                    <i class="fas fa-folder"></i> Dossiers Liés
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="equipe-tab" data-toggle="tab" href="#equipe" role="tab" aria-controls="equipe" aria-selected="false">
                                                    <i class="fas fa-user-shield"></i> Équipe
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="notes-tab" data-toggle="tab" href="#notes" role="tab" aria-controls="notes" aria-selected="false">
                                                    <i class="fas fa-sticky-note"></i> Notes
                                                </a>
                                            </li>
                                        </ul>
                                        
                                        <div class="tab-content" id="dossierTabsContent">
                                            <!-- Onglet Générale -->
                                            <div class="tab-pane fade show active" id="generale" role="tabpanel" aria-labelledby="generale-tab">
                                                <div class="p-3">
                                                    <!-- Informations de base -->
                                                    <h5 class="text-primary mb-3"><i class="fas fa-folder"></i> Informations de base</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="numero_dossier">Numéro du dossier *</label>
                                                                <input type="text" class="form-control @error('numero_dossier') is-invalid @enderror" 
                                                                       id="numero_dossier" name="numero_dossier" 
                                                                       value="{{ old('numero_dossier') }}" 
                                                                       placeholder="Ex: DOS-2024-001" required>
                                                                @error('numero_dossier')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="date_entree">Date d'entrée *</label>
                                                                <input type="date" class="form-control @error('date_entree') is-invalid @enderror" 
                                                                       id="date_entree" name="date_entree" 
                                                                       value="{{ old('date_entree', date('Y-m-d')) }}" required>
                                                                @error('date_entree')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="nom_dossier">Nom du dossier *</label>
                                                                <input type="text" class="form-control @error('nom_dossier') is-invalid @enderror" 
                                                                       id="nom_dossier" name="nom_dossier" 
                                                                       value="{{ old('nom_dossier') }}" 
                                                                       placeholder="Intitulé complet du dossier" required>
                                                                @error('nom_dossier')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="objet">Objet du dossier</label>
                                                                <textarea class="form-control @error('objet') is-invalid @enderror" 
                                                                          id="objet" name="objet" 
                                                                          rows="4" placeholder="Description détaillée de l'objet du dossier">{{ old('objet') }}</textarea>
                                                                @error('objet')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Domaines -->
                                                    <h5 class="text-primary mb-3 mt-4"><i class="fas fa-tags"></i> Classification</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="domaine_id">Domaine</label>
                                                                <select class="form-control @error('domaine_id') is-invalid @enderror" 
                                                                        id="domaine_id" name="domaine_id">
                                                                    <option value="">Sélectionnez un domaine</option>
                                                                    @foreach($domaines as $domaine)
                                                                        <option value="{{ $domaine->id }}" {{ old('domaine_id') == $domaine->id ? 'selected' : '' }}>
                                                                            {{ $domaine->nom }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('domaine_id')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="sous_domaine_id">Sous-domaine</label>
                                                                <select class="form-control @error('sous_domaine_id') is-invalid @enderror" 
                                                                        id="sous_domaine_id" name="sous_domaine_id">
                                                                    <option value="">Sélectionnez d'abord un domaine</option>
                                                                </select>
                                                                @error('sous_domaine_id')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Type de dossier -->
                                                    <h5 class="text-primary mb-3 mt-4"><i class="fas fa-balance-scale"></i> Type de dossier</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input class="custom-control-input" type="checkbox" 
                                                                           id="conseil" name="conseil" value="1" 
                                                                           {{ old('conseil') ? 'checked' : '' }}>
                                                                    <label for="conseil" class="custom-control-label">
                                                                        Dossier de conseil
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input class="custom-control-input" type="checkbox" 
                                                                           id="contentieux" name="contentieux" value="1" 
                                                                           {{ old('contentieux') ? 'checked' : '' }}>
                                                                    <label for="contentieux" class="custom-control-label">
                                                                        Dossier contentieux
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    

                                                    <!-- Archivage -->
                                                    <h5 class="text-primary mb-3 mt-4"><i class="fas fa-archive"></i> Archivage</h5>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input class="custom-control-input" type="checkbox" 
                                                                           id="archive" name="archive" value="1" 
                                                                           {{ old('archive') ? 'checked' : '' }}>
                                                                    <label for="archive" class="custom-control-label">
                                                                        Marquer comme archivé
                                                                    </label>
                                                                </div>
                                                                <small class="form-text text-muted">
                                                                    Si coché, ce dossier sera marqué comme archivé dès sa création.
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="alert alert-info">
                                                        <h5><i class="icon fas fa-info"></i> Informations</h5>
                                                        <p class="mb-0">
                                                            Utilisez cet espace pour noter toutes informations supplémentaires concernant ce dossier.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Onglet Information Juridique -->
                                            <div class="tab-pane fade" id="juridique" role="tabpanel" aria-labelledby="juridique-tab">
                                                <div class="p-3">
                                                    <!-- Informations juridiques -->
                                                    <h5 class="text-primary mb-3"><i class="fas fa-scale-balanced"></i> Informations sur la procédure</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="numero_role">Numéro de rôle</label>
                                                                <input type="text" class="form-control @error('numero_role') is-invalid @enderror" 
                                                                       id="numero_role" name="numero_role" 
                                                                       value="{{ old('numero_role') }}" 
                                                                       placeholder="Numéro attribué par la juridiction">
                                                                @error('numero_role')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="chambre">Chambre</label>
                                                                <select class="form-control @error('chambre') is-invalid @enderror" 
                                                                        id="chambre" name="chambre">
                                                                    <option value="">Sélectionnez une chambre</option>
                                                                    <option value="civil" {{ old('chambre') == 'civil' ? 'selected' : '' }}>Civil</option>
                                                                    <option value="commercial" {{ old('chambre') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                                                    <option value="social" {{ old('chambre') == 'social' ? 'selected' : '' }}>Social</option>
                                                                    <option value="pénal" {{ old('chambre') == 'pénal' ? 'selected' : '' }}>Pénal</option>
                                                                </select>
                                                                @error('chambre')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="numero_chambre">Numéro de chambre</label>
                                                                <input type="text" class="form-control @error('numero_chambre') is-invalid @enderror" 
                                                                       id="numero_chambre" name="numero_chambre" 
                                                                       value="{{ old('numero_chambre') }}" 
                                                                       placeholder="N° de chambre">
                                                                @error('numero_chambre')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="numero_parquet">Numéro de parquet</label>
                                                                <input type="text" class="form-control @error('numero_parquet') is-invalid @enderror" 
                                                                       id="numero_parquet" name="numero_parquet" 
                                                                       value="{{ old('numero_parquet') }}" 
                                                                       placeholder="N° de parquet">
                                                                @error('numero_parquet')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="numero_instruction">Numéro d'instruction</label>
                                                                <input type="text" class="form-control @error('numero_instruction') is-invalid @enderror" 
                                                                       id="numero_instruction" name="numero_instruction" 
                                                                       value="{{ old('numero_instruction') }}" 
                                                                       placeholder="N° d'instruction">
                                                                @error('numero_instruction')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="numero_plainte">Numéro de plainte</label>
                                                                <input type="text" class="form-control @error('numero_plainte') is-invalid @enderror" 
                                                                       id="numero_plainte" name="numero_plainte" 
                                                                       value="{{ old('numero_plainte') }}" 
                                                                       placeholder="N° de plainte">
                                                                @error('numero_plainte')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Onglet Intervenants -->
                                            <div class="tab-pane fade" id="intervenants" role="tabpanel" aria-labelledby="intervenants-tab">
                                                <div class="p-3">
                                                    <h5 class="text-primary mb-3"><i class="fas fa-handshake"></i> Gestion des intervenants</h5>
                                                    
                                                    <!-- Client principal -->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="client_id">Client principal</label>
                                                                <select class="form-control @error('client_id') is-invalid @enderror" 
                                                                        id="client_id" name="client_id">
                                                                    <option value="">Sélectionnez le client</option>
                                                                    @foreach($intervenants as $intervenant)
                                                                       
                                                                            <option value="{{ $intervenant->id }}" {{ old('client_id') == $intervenant->id ? 'selected' : '' }}>
                                                                                {{ $intervenant->identite_fr }}
                                                                            </option>
                                                                    
                                                                    @endforeach
                                                                </select>
                                                                @error('client_id')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Autres intervenants -->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Autres intervenants</label>
                                                                <select class="form-control" id="autres_intervenants" name="autres_intervenants[]" multiple>
                                                                    @foreach($intervenants as $intervenant)
                                                                       
                                                                            <option value="{{ $intervenant->id }}">
                                                                                {{ $intervenant->identite_fr }} ({{ $intervenant->categorie }})
                                                                            </option>
                                                                       
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Rôles des intervenants -->
                                                    <div class="row mt-3">
                                                        <div class="col-md-12">
                                                            <div class="alert alert-info">
                                                                <h6><i class="icon fas fa-info"></i> Information</h6>
                                                                <p class="mb-0">
                                                                    Sélectionnez le client principal et éventuellement d'autres intervenants (avocats adverses, experts, etc.).
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            
                                            <!-- Onglet Dossiers -->
                                            <div class="tab-pane fade" id="dossiers" role="tabpanel" aria-labelledby="dossiers-tab">
                                                <div class="p-3">
                                                    <h5 class="text-primary mb-3"><i class="fas fa-handshake"></i> Gestion des dossiers</h5>
                                                    

                                                    <!-- Autres dossiers -->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Autres dossiers</label>
                                                                <select class="form-control" id="autres_dossiers" name="autres_dossiers[]" multiple>
                                                                    @foreach($dossiers as $dossier)
                                                                        <option value="{{ $dossier->id }}">
                                                                            {{ $dossier->numero_dossier }} - {{ $dossier->nom_dossier }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Onglet Équipe -->
                                            <div class="tab-pane fade" id="equipe" role="tabpanel" aria-labelledby="equipe-tab">
                                                <div class="p-3">
                                                    <h5 class="text-primary mb-3"><i class="fas fa-users-cog"></i> Attribution de l'équipe</h5>
                                                    
                                                    <!-- Avocat responsable -->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="avocat_id">Avocat responsable</label>
                                                                <select class="form-control @error('avocat_id') is-invalid @enderror" 
                                                                        id="avocat_id" name="avocat_id">
                                                                    <option value="">Sélectionnez l'avocat responsable</option>
                                                                    @foreach($users as $user)
                                                                        @if($user->hasRole('avocat') || $user->hasRole('admin'))
                                                                            <option value="{{ $user->id }}" {{ old('avocat_id') == $user->id ? 'selected' : '' }}>
                                                                                {{ $user->name }} ({{ $user->fonction }})
                                                                            </option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                                @error('avocat_id')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="ordre">Ordre de priorité</label>
                                                                <select class="form-control @error('ordre') is-invalid @enderror" 
                                                                        id="ordre" name="ordre">
                                                                    <option value="1" {{ old('ordre', 1) == 1 ? 'selected' : '' }}>1 - Priorité haute</option>
                                                                    <option value="2" {{ old('ordre', 2) == 2 ? 'selected' : '' }}>2 - Priorité moyenne</option>
                                                                    <option value="3" {{ old('ordre', 3) == 3 ? 'selected' : '' }}>3 - Priorité basse</option>
                                                                </select>
                                                                @error('ordre')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Équipe supplémentaire -->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Membres supplémentaires de l'équipe</label>
                                                                <select class="form-control" id="equipe_supplementaire" name="equipe_supplementaire[]" multiple>
                                                                    @foreach($users as $user)
                                                                        <option value="{{ $user->id }}">
                                                                            {{ $user->name }} ({{ $user->fonction }})
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Onglet Facturation -->
                                            <div class="tab-pane fade d-none" id="facturation" role="tabpanel" aria-labelledby="facturation-tab">
                                                <div class="p-3">
                                                    <h5 class="text-primary mb-3"><i class="fas fa-money-bill-wave"></i> Informations de facturation</h5>
                                                    
                                                    <!-- Mode de facturation -->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="mode_facturation">Mode de facturation</label>
                                                                <select class="form-control @error('mode_facturation') is-invalid @enderror" 
                                                                        id="mode_facturation" name="mode_facturation">
                                                                    <option value="">Sélectionnez un mode</option>
                                                                    <option value="honoraires" {{ old('mode_facturation') == 'honoraires' ? 'selected' : '' }}>Honoraires</option>
                                                                    <option value="forfait" {{ old('mode_facturation') == 'forfait' ? 'selected' : '' }}>Forfait</option>
                                                                    <option value="dossier" {{ old('mode_facturation') == 'dossier' ? 'selected' : '' }}>Au dossier</option>
                                                                    <option value="provision" {{ old('mode_facturation') == 'provision' ? 'selected' : '' }}>Provision</option>
                                                                </select>
                                                                @error('mode_facturation')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="budget_estime">Budget estimé (DH)</label>
                                                                <input type="number" class="form-control @error('budget_estime') is-invalid @enderror" 
                                                                       id="budget_estime" name="budget_estime" 
                                                                       value="{{ old('budget_estime') }}" 
                                                                       placeholder="Montant estimé" step="0.01">
                                                                @error('budget_estime')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Informations complémentaires -->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="notes_facturation">Notes de facturation</label>
                                                                <textarea class="form-control @error('notes_facturation') is-invalid @enderror" 
                                                                          id="notes_facturation" name="notes_facturation" 
                                                                          rows="3" placeholder="Informations complémentaires sur la facturation">{{ old('notes_facturation') }}</textarea>
                                                                @error('notes_facturation')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Onglet Notes -->
                                            <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">
                                                <div class="p-3">
                                                    <!-- Notes générales -->
                                                    <div class="form-group">
                                                        <label for="notes">Notes et observations</label>
                                                        <textarea class="form-control @error('note') is-invalid @enderror" 
                                                                  id="notes" name="note" 
                                                                  rows="12" placeholder="Notes supplémentaires, observations, informations importantes...">{{ old('note') }}</textarea>
                                                        @error('note')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <button type="button" class="btn btn-default btn-previous" style="display: none;">
                                            <i class="fas fa-arrow-left"></i> Précédent
                                        </button>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-secondary btn-next">
                                            Suivant <i class="fas fa-arrow-right"></i>
                                        </button>
                                        <button type="submit" id="submitDossier" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Créer le dossier
                                        </button>
                                    </div>
                                </div>
                                <div class="text-center mt-2">
                                    <a href="{{ route('dossiers.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left"></i> Retour à la liste
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{asset('assets/custom/dossier-form.js')}}"></script>
@endsection


