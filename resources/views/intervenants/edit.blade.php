@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Modifier l'Intervenant</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('intervenants.index') }}">Intervenants</a></li>
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
                            <h3 class="card-title">Modification de l'intervenant</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ route('intervenants.update', $intervenant->id) }}" method="POST" id="intervenantForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
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
                                        <ul class="nav nav-tabs" id="intervenantTabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">
                                                    <i class="fas fa-info-circle"></i> Général
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="coordonnees-tab" data-toggle="tab" href="#coordonnees" role="tab" aria-controls="coordonnees" aria-selected="false">
                                                    <i class="fas fa-address-book"></i> Coordonnées
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="fichiers-tab" data-toggle="tab" href="#fichiers" role="tab" aria-controls="fichiers" aria-selected="false">
                                                    <i class="fas fa-file"></i> Fichiers
                                                    @if($intervenant->files && count($intervenant->files) > 0)
                                                        <span class="badge badge-primary ml-1">{{ count($intervenant->files) }}</span>
                                                    @endif
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="intervenants-lies-tab" data-toggle="tab" href="#intervenants-lies" role="tab" aria-controls="intervenants-lies" aria-selected="false">
                                                    <i class="fas fa-users"></i> Intervenants Liés
                                                    @if($intervenant->intervenantsLies && count($intervenant->intervenantsLies) > 0)
                                                        <span class="badge badge-primary ml-1">{{ count($intervenant->intervenantsLies) }}</span>
                                                    @endif
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="notes-tab" data-toggle="tab" href="#notes" role="tab" aria-controls="notes" aria-selected="false">
                                                    <i class="fas fa-sticky-note"></i> Notes
                                                </a>
                                            </li>
                                        </ul>
                                        
                                        <div class="tab-content" id="intervenantTabsContent">
                                            <!-- Onglet Général -->
                                            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                                <div class="p-3">
                                                    <!-- Identité -->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="identite_fr">Identité (Français) *</label>
                                                                <input type="text" class="form-control @error('identite_fr') is-invalid @enderror" 
                                                                       id="identite_fr" name="identite_fr" 
                                                                       value="{{ old('identite_fr', $intervenant->identite_fr) }}" 
                                                                       placeholder="Nom et prénom ou raison sociale" required>
                                                                @error('identite_fr')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="identite_ar">Identité (Arabe)</label>
                                                                <input type="text" class="form-control @error('identite_ar') is-invalid @enderror" 
                                                                       id="identite_ar" name="identite_ar" 
                                                                       value="{{ old('identite_ar', $intervenant->identite_ar) }}" 
                                                                       placeholder="الاسم الكامل أو التسمية الاجتماعية">
                                                                @error('identite_ar')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Type et Catégorie -->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="type">Type *</label>
                                                                <select class="form-control @error('type') is-invalid @enderror" 
                                                                        id="type" name="type" required>
                                                                    <option value="">Sélectionnez un type</option>
                                                                    <option value="personne physique" {{ old('type', $intervenant->type) == 'personne physique' ? 'selected' : '' }}>Personne Physique</option>
                                                                    <option value="personne morale" {{ old('type', $intervenant->type) == 'personne morale' ? 'selected' : '' }}>Personne Morale</option>
                                                                    <option value="entreprise individuelle" {{ old('type', $intervenant->type) == 'entreprise individuelle' ? 'selected' : '' }}>Entreprise Individuelle</option>
                                                                </select>
                                                                @error('type')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="categorie">Catégorie *</label>
                                                                <select class="form-control @error('categorie') is-invalid @enderror" 
                                                                        id="categorie" name="categorie" required>
                                                                    <option value="">Sélectionnez une catégorie</option>
                                                                    <option value="client" {{ old('categorie', $intervenant->categorie) == 'client' ? 'selected' : '' }}>Client</option>
                                                                    <option value="avocat" {{ old('categorie', $intervenant->categorie) == 'avocat' ? 'selected' : '' }}>Avocat</option>
                                                                    <option value="adversaire" {{ old('categorie', $intervenant->categorie) == 'adversaire' ? 'selected' : '' }}>Adversaire</option>
                                                                    <option value="notaire" {{ old('categorie', $intervenant->categorie) == 'notaire' ? 'selected' : '' }}>Notaire</option>
                                                                    <option value="huissier" {{ old('categorie', $intervenant->categorie) == 'huissier' ? 'selected' : '' }}>Huissier</option>
                                                                    <option value="juridiction" {{ old('categorie', $intervenant->categorie) == 'juridiction' ? 'selected' : '' }}>Juridiction</option>
                                                                    <option value="administrateur_judiciaire" {{ old('categorie', $intervenant->categorie) == 'administrateur_judiciaire' ? 'selected' : '' }}>Administrateur Judiciaire</option>
                                                                    <option value="mandataire_judiciaire" {{ old('categorie', $intervenant->categorie) == 'mandataire_judiciaire' ? 'selected' : '' }}>Mandataire Judiciaire</option>
                                                                    <option value="expert_judiciaire" {{ old('categorie', $intervenant->categorie) == 'expert_judiciaire' ? 'selected' : '' }}>Expert Judiciaire</option>
                                                                    <option value="contact" {{ old('categorie', $intervenant->categorie) == 'contact' ? 'selected' : '' }}>Contact</option>
                                                                </select>
                                                                @error('categorie')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Informations professionnelles -->
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="fonction">Fonction</label>
                                                                <input type="text" class="form-control @error('fonction') is-invalid @enderror" 
                                                                       id="fonction" name="fonction" 
                                                                       value="{{ old('fonction', $intervenant->fonction) }}" 
                                                                       placeholder="Fonction ou profession">
                                                                @error('fonction')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="forme_sociale_id">Forme Sociale</label>
                                                                <select class="form-control @error('forme_sociale_id') is-invalid @enderror" 
                                                                        id="forme_sociale_id" name="forme_sociale_id">
                                                                    <option value="">Sélectionnez une forme sociale</option>
                                                                    @foreach($formeSociales as $formeSociale)
                                                                        <option value="{{ $formeSociale->id }}" {{ old('forme_sociale_id', $intervenant->forme_sociale_id) == $formeSociale->id ? 'selected' : '' }}>
                                                                            {{ $formeSociale->nom }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('forme_sociale_id')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="numero_cni">Numéro CNI</label>
                                                                <input type="text" class="form-control @error('numero_cni') is-invalid @enderror" 
                                                                       id="numero_cni" name="numero_cni" 
                                                                       value="{{ old('numero_cni', $intervenant->numero_cni) }}" 
                                                                       placeholder="Numéro de carte d'identité">
                                                                @error('numero_cni')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Numéros d'identification -->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="rne">RNE (Registre National des Entreprises)</label>
                                                                <input type="text" class="form-control @error('rne') is-invalid @enderror" 
                                                                       id="rne" name="rne" 
                                                                       value="{{ old('rne', $intervenant->rne) }}" 
                                                                       placeholder="Numéro RNE">
                                                                @error('rne')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="numero_cnss">Numéro CNSS</label>
                                                                <input type="text" class="form-control @error('numero_cnss') is-invalid @enderror" 
                                                                       id="numero_cnss" name="numero_cnss" 
                                                                       value="{{ old('numero_cnss', $intervenant->numero_cnss) }}" 
                                                                       placeholder="Numéro de sécurité sociale">
                                                                @error('numero_cnss')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Statut Archive -->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input class="custom-control-input" type="checkbox" 
                                                                           id="archive" name="archive" value="1" 
                                                                           {{ old('archive', $intervenant->archive) ? 'checked' : '' }}>
                                                                    <label for="archive" class="custom-control-label">
                                                                        Marquer comme archivé
                                                                    </label>
                                                                </div>
                                                                <small class="form-text text-muted">
                                                                    Si coché, cet intervenant sera marqué comme archivé et n'apparaîtra pas dans les listes par défaut.
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Onglet Coordonnées -->
                                            <div class="tab-pane fade" id="coordonnees" role="tabpanel" aria-labelledby="coordonnees-tab">
                                                <div class="p-3">
                                                    <!-- Coordonnées téléphoniques -->
                                                    <h5 class="text-primary mb-3"><i class="fas fa-phone"></i> Coordonnées Téléphoniques</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="portable1">Portable 1 *</label>
                                                                <input type="text" class="form-control @error('portable1') is-invalid @enderror" 
                                                                       id="portable1" name="portable1" 
                                                                       value="{{ old('portable1', $intervenant->portable1) }}" 
                                                                       placeholder="Numéro de portable principal" required>
                                                                @error('portable1')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="portable2">Portable 2</label>
                                                                <input type="text" class="form-control @error('portable2') is-invalid @enderror" 
                                                                       id="portable2" name="portable2" 
                                                                       value="{{ old('portable2', $intervenant->portable2) }}" 
                                                                       placeholder="Numéro de portable secondaire">
                                                                @error('portable2')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Téléphones fixes -->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="fixe1">Téléphone Fixe 1</label>
                                                                <input type="text" class="form-control @error('fixe1') is-invalid @enderror" 
                                                                       id="fixe1" name="fixe1" 
                                                                       value="{{ old('fixe1', $intervenant->fixe1) }}" 
                                                                       placeholder="Numéro de fixe principal">
                                                                @error('fixe1')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="fixe2">Téléphone Fixe 2</label>
                                                                <input type="text" class="form-control @error('fixe2') is-invalid @enderror" 
                                                                       id="fixe2" name="fixe2" 
                                                                       value="{{ old('fixe2', $intervenant->fixe2) }}" 
                                                                       placeholder="Numéro de fixe secondaire">
                                                                @error('fixe2')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Emails -->
                                                    <h5 class="text-primary mb-3 mt-4"><i class="fas fa-envelope"></i> Adresses Email</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="mail1">Email 1 *</label>
                                                                <input type="email" class="form-control @error('mail1') is-invalid @enderror" 
                                                                       id="mail1" name="mail1" 
                                                                       value="{{ old('mail1', $intervenant->mail1) }}" 
                                                                       placeholder="Adresse email principale" required>
                                                                @error('mail1')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="mail2">Email 2</label>
                                                                <input type="email" class="form-control @error('mail2') is-invalid @enderror" 
                                                                       id="mail2" name="mail2" 
                                                                       value="{{ old('mail2', $intervenant->mail2) }}" 
                                                                       placeholder="Adresse email secondaire">
                                                                @error('mail2')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Adresses -->
                                                    <h5 class="text-primary mb-3 mt-4"><i class="fas fa-map-marker-alt"></i> Adresses</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="adresse1">Adresse 1 *</label>
                                                                <textarea class="form-control @error('adresse1') is-invalid @enderror" 
                                                                          id="adresse1" name="adresse1" 
                                                                          rows="3" placeholder="Adresse principale" required>{{ old('adresse1', $intervenant->adresse1) }}</textarea>
                                                                @error('adresse1')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="adresse2">Adresse 2</label>
                                                                <textarea class="form-control @error('adresse2') is-invalid @enderror" 
                                                                          id="adresse2" name="adresse2" 
                                                                          rows="3" placeholder="Adresse secondaire (complément)">{{ old('adresse2', $intervenant->adresse2) }}</textarea>
                                                                @error('adresse2')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Site web et Fax -->
                                                    <h5 class="text-primary mb-3 mt-4"><i class="fas fa-globe"></i> Autres Coordonnées</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="site_internet">Site Internet</label>
                                                                <input type="url" class="form-control @error('site_internet') is-invalid @enderror" 
                                                                       id="site_internet" name="site_internet" 
                                                                       value="{{ old('site_internet', $intervenant->site_internet) }}" 
                                                                       placeholder="https://example.com">
                                                                @error('site_internet')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="fax">Fax</label>
                                                                <input type="text" class="form-control @error('fax') is-invalid @enderror" 
                                                                       id="fax" name="fax" 
                                                                       value="{{ old('fax', $intervenant->fax) }}" 
                                                                       placeholder="Numéro de fax">
                                                                @error('fax')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Onglet Fichiers -->
                                            <div class="tab-pane fade" id="fichiers" role="tabpanel" aria-labelledby="fichiers-tab">
                                                <div class="p-3">
                                                    <h5 class="text-primary mb-3"><i class="fas fa-file-upload"></i> Gestion des fichiers</h5>
                                                    
                                                    <!-- Upload de nouveaux fichiers -->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="piece_jointe">Nouvelles pièces jointes</label>
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input @error('piece_jointe') is-invalid @enderror" 
                                                                           id="piece_jointe" name="piece_jointe[]" 
                                                                           multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.txt,.zip,.rar">
                                                                    <label class="custom-file-label" for="piece_jointe" id="piece_jointe_label">
                                                                        Choisir des fichiers (PDF, images, Word, Excel) - Max 10MB par fichier
                                                                    </label>
                                                                    @error('piece_jointe')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                    @error('piece_jointe.*')
                                                                        <span class="invalid-feedback d-block" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                                <small class="form-text text-muted">
                                                                    Formats acceptés: PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX, TXT, ZIP, RAR - Taille max: 10MB par fichier
                                                                </small>
                                                                
                                                                <!-- Aperçu des nouveaux fichiers -->
                                                                <div id="files_preview" class="mt-3" style="display: none;">
                                                                    <h6 class="text-info">Nouveaux fichiers sélectionnés :</h6>
                                                                    <div id="files_list" class="list-group"></div>
                                                                    <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="clearFileInput()">
                                                                        <i class="fas fa-times"></i> Effacer tous les nouveaux fichiers
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Liste des fichiers existants -->
                                                    <div class="row mt-4">
                                                        <div class="col-md-12">
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    <h5 class="card-title mb-0">
                                                                        <i class="fas fa-files-o mr-1"></i>
                                                                        Fichiers existants
                                                                        <small class="text-muted ml-1">({{ $intervenant->files->count() }} fichiers)</small>
                                                                    </h5>
                                                                </div>
                                                                <div class="card-body">
                                                                    @if($intervenant->files->count() > 0)
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered table-hover">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Nom du fichier</th>
                                                                                        <th>Date d'upload</th>
                                                                                        <th>Actions</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($intervenant->files as $file)
                                                                                        <tr>
                                                                                            <td>
                                                                                                <span class="badge badge-info">{{ pathinfo($file->file_path, PATHINFO_EXTENSION) }}</span> {{ $file->file_path }}
                                                                                            </td>
                                                                                            <td>{{ $file->created_at->format('d/m/Y H:i') }}</td>
                                                                                            <td>
                                                                                                <div class="btn-group btn-group-sm">
                                                                                                    <a href="{{ $file->file_url }}" target="_blank" class="btn btn-info" title="Voir">
                                                                                                        <i class="fas fa-eye"></i>
                                                                                                    </a>
                                                                                                    <a href="{{ $file->file_url }}" download class="btn btn-success" title="Télécharger">
                                                                                                        <i class="fas fa-download"></i>
                                                                                                    </a>
                                                                                                    <button type="button" class="btn btn-danger delete-file-btn" 
                                                                                                            data-file-id="{{ $file->id }}" 
                                                                                                            data-file-name="{{ $file->file_name }}"
                                                                                                            title="Supprimer">
                                                                                                        <i class="fas fa-trash"></i>
                                                                                                    </button>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    @else
                                                                        <div class="text-center py-4">
                                                                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                                                            <p class="text-muted">Aucun fichier attaché à cet intervenant</p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Onglet Intervenants Liés -->
                                            <div class="tab-pane fade" id="intervenants-lies" role="tabpanel" aria-labelledby="intervenants-lies-tab">
                                                <div class="p-3">
                                                    <div class="alert alert-info">
                                                        <h5><i class="icon fas fa-info"></i> Gestion des relations</h5>
                                                        <p class="mb-0">
                                                            Vous pouvez associer cet intervenant à d'autres intervenants existants et définir le type de relation.
                                                        </p>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="form-group">
                                                                        <label for="intervenants_lies">Intervenants liés</label>
                                                                        <select class="form-control @error('intervenants_lies') is-invalid @enderror" 
                                                                                id="intervenants_lies" name="intervenants_lies[]" 
                                                                                multiple>
                                                                            @foreach($intervenants as $intervenantItem)
                                                                                <option value="{{ $intervenantItem->id }}" 
                                                                                    {{ (collect(old('intervenants_lies', $intervenant->intervenantsLies->pluck('id')))->contains($intervenantItem->id)) ? 'selected':'' }}>
                                                                                    {{ $intervenantItem->identite_fr }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        @error('intervenants_lies')
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                        <small class="form-text text-muted">
                                                                            Maintenez la touche Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs intervenants.
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Onglet Notes -->
                                            <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">
                                                <div class="p-3">
                                                    <!-- Notes -->
                                                    <div class="form-group">
                                                        <label for="notes">Notes et Observations</label>
                                                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                                                  id="notes" name="notes" 
                                                                  rows="12" placeholder="Notes supplémentaires, observations, informations importantes...">{{ old('notes', $intervenant->notes) }}</textarea>
                                                        @error('notes')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="alert alert-info">
                                                        <h5><i class="icon fas fa-info"></i> Informations</h5>
                                                        <p class="mb-0">
                                                            Utilisez cet espace pour noter toutes informations supplémentaires concernant cet intervenant 
                                                            qui pourraient être utiles pour le suivi des dossiers.
                                                        </p>
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
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Mettre à jour
                                        </button>
                                    </div>
                                </div>
                                <div class="text-center mt-2">
                                    <a href="{{ route('intervenants.show', $intervenant->id) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left"></i> Retour aux détails
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

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteFileModal" tabindex="-1" role="dialog" aria-labelledby="deleteFileModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteFileModalLabel">Confirmation de suppression</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer le fichier <strong id="fileNameToDelete"></strong> ?</p>
                <p class="text-danger"><small>Cette action est irréversible.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <form id="deleteFileForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- jquery-validation -->
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<script>
// Fonction pour effacer l'input file
function clearFileInput() {
    $('#piece_jointe').val('');
    $('#piece_jointe_label').text('Choisir des fichiers (PDF, images, Word, Excel) - Max 10MB par fichier');
    $('#files_preview').hide();
    $('#files_list').empty();
}

// Fonction pour formater la taille des fichiers
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Fonction pour obtenir l'icône du fichier selon son type
function getFileIcon(filename) {
    const ext = filename.split('.').pop().toLowerCase();
    const iconMap = {
        'pdf': 'fa-file-pdf text-danger',
        'jpg': 'fa-file-image text-success',
        'jpeg': 'fa-file-image text-success',
        'png': 'fa-file-image text-success',
        'doc': 'fa-file-word text-primary',
        'docx': 'fa-file-word text-primary',
        'xls': 'fa-file-excel text-success',
        'xlsx': 'fa-file-excel text-success',
        'txt': 'fa-file-alt text-secondary',
        'zip': 'fa-file-archive text-warning',
        'rar': 'fa-file-archive text-warning'
    };
    return iconMap[ext] || 'fa-file text-secondary';
}

$(document).ready(function() {

    // Gestion de la navigation par onglets
    let currentTab = 0;
    const tabs = $('#intervenantTabs .nav-link');
    const tabPanes = $('.tab-pane');

    // Afficher/masquer les boutons de navigation
    function updateNavigationButtons() {
        if (currentTab === 0) {
            $('.btn-previous').hide();
            $('.btn-next').show();
        } else if (currentTab === tabs.length - 1) {
            $('.btn-previous').show();
            $('.btn-next').hide();
        } else {
            $('.btn-previous').show();
            $('.btn-next').show();
        }
    }

    // Aller à l'onglet suivant
    $('.btn-next').click(function() {
        if (currentTab < tabs.length - 1) {
            currentTab++;
            $(tabs[currentTab]).tab('show');
            updateNavigationButtons();
        }
    });

    // Aller à l'onglet précédent
    $('.btn-previous').click(function() {
        if (currentTab > 0) {
            currentTab--;
            $(tabs[currentTab]).tab('show');
            updateNavigationButtons();
        }
    });

    // Mettre à jour la navigation quand on clique directement sur un onglet
    tabs.on('shown.bs.tab', function(e) {
        currentTab = tabs.index(e.target);
        updateNavigationButtons();
    });

    // Gestion de l'input file multiple
    $('#piece_jointe').on('change', function(e) {
        const files = e.target.files;
        
        if (files.length > 0) {
            let fileNames = [];
            let totalSize = 0;
            let hasOversizedFile = false;
            
            // Vider la liste précédente
            $('#files_list').empty();
            
            // Parcourir tous les fichiers
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                fileNames.push(file.name);
                totalSize += file.size;
                
                // Vérifier la taille du fichier
                if (file.size > 10 * 1024 * 1024) { // 10MB en bytes
                    hasOversizedFile = true;
                }
                
                // Ajouter à l'aperçu
                const fileItem = `
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas ${getFileIcon(file.name)} mr-2"></i>
                            <span class="file-name">${file.name}</span>
                        </div>
                        <div>
                            <span class="badge badge-info badge-pill">${formatFileSize(file.size)}</span>
                            ${file.size > 10 * 1024 * 1024 ? '<span class="badge badge-danger badge-pill ml-1">Trop volumineux</span>' : ''}
                        </div>
                    </div>
                `;
                $('#files_list').append(fileItem);
            }
            
            // Mettre à jour le label
            if (files.length === 1) {
                $('#piece_jointe_label').text(fileNames[0]);
            } else {
                $('#piece_jointe_label').text(files.length + ' fichiers sélectionnés');
            }
            
            // Afficher l'aperçu
            $('#files_preview').show();
            
            // Afficher un avertissement si des fichiers sont trop volumineux
            if (hasOversizedFile) {
                alert('Certains fichiers dépassent la taille maximale de 10MB. Ils ne pourront pas être uploadés.');
            }
            
            // Afficher la taille totale
            const totalSizeItem = `
                <div class="list-group-item list-group-item-secondary d-flex justify-content-between align-items-center">
                    <strong>Total</strong>
                    <strong>${formatFileSize(totalSize)}</strong>
                </div>
            `;
            $('#files_list').append(totalSizeItem);
            
        } else {
            clearFileInput();
        }
    });

    // Gestion de la suppression des fichiers
    $('.delete-file-btn').click(function() {
        const fileId = $(this).data('file-id');
        const fileName = $(this).data('file-name');
        
        $('#fileNameToDelete').text(fileName);
        $('#deleteFileForm').attr('action', '{{ url("intervenant-files") }}/' + fileId);
        $('#deleteFileModal').modal('show');
    });

    // Validation du formulaire
    $('#intervenantForm').validate({
        rules: {
            identite_fr: {
                required: true,
                minlength: 2
            },
            type: {
                required: true
            },
            categorie: {
                required: true
            },
            portable1: {
                required: true
            },
            mail1: {
                required: true,
                email: true
            },
            adresse1: {
                required: true
            },
            mail2: {
                email: true
            },
            site_internet: {
                url: true
            },
            'piece_jointe[]': {
                accept: "application/pdf,image/jpeg,image/jpg,image/png,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain,application/zip,application/x-rar-compressed",
                filesize: 10485760 // 10MB in bytes
            }
        },
        messages: {
            identite_fr: {
                required: "L'identité en français est obligatoire",
                minlength: "L'identité doit contenir au moins 2 caractères"
            },
            type: {
                required: "Le type d'intervenant est obligatoire"
            },
            categorie: {
                required: "La catégorie est obligatoire"
            },
            portable1: {
                required: "Le numéro de portable est obligatoire"
            },
            mail1: {
                required: "L'adresse email est obligatoire",
                email: "Veuillez entrer une adresse email valide"
            },
            adresse1: {
                required: "L'adresse est obligatoire"
            },
            mail2: {
                email: "Veuillez entrer une adresse email valide"
            },
            site_internet: {
                url: "Veuillez entrer une URL valide"
            },
            'piece_jointe[]': {
                accept: "Veuillez choisir des fichiers de type PDF, image, Word, Excel, texte ou archive",
                filesize: "La taille d'un fichier ne doit pas dépasser 10MB"
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(event, validator) {
            // Aller à l'onglet contenant la première erreur
            const firstError = validator.errorList[0];
            if (firstError) {
                const errorElement = $(firstError.element);
                const tabPane = errorElement.closest('.tab-pane');
                const tabId = tabPane.attr('id');
                const tabLink = $(`[href="#${tabId}"]`);
                
                tabLink.tab('show');
                currentTab = tabs.index(tabLink[0]);
                updateNavigationButtons();
                
                // Scroll vers l'erreur
                $('html, body').animate({
                    scrollTop: errorElement.offset().top - 100
                }, 500);
            }
        }
    });

   
    updateNavigationButtons();
});
</script>

<style>
.nav-tabs .nav-link {
    font-weight: 500;
    padding: 0.75rem 1.5rem;
}

.nav-tabs .nav-link.active {
    border-bottom: 3px solid #007bff;
    font-weight: 600;
}

.tab-content {
    border: 1px solid #dee2e6;
    border-top: none;
    border-radius: 0 0 0.25rem 0.25rem;
}

.tab-pane {
    min-height: 400px;
}

.btn-previous, .btn-next {
    min-width: 120px;
}

/* Amélioration de l'apparence des sections */
h5.text-primary {
    border-bottom: 2px solid #007bff;
    padding-bottom: 0.5rem;
    margin-bottom: 1.5rem !important;
}


/* Styles pour l'aperçu des fichiers */
#files_preview .list-group-item {
    border-left: 4px solid #007bff;
}

.file-name {
    word-break: break-all;
}

.fa-file-pdf { color: #dc3545; }
.fa-file-image { color: #28a745; }
.fa-file-word { color: #007bff; }
.fa-file-excel { color: #28a745; }
.fa-file-alt { color: #6c757d; }
.fa-file-archive { color: #ffc107; }
</style>
@endsection