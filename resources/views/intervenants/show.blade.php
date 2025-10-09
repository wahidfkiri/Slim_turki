{{-- resources/views/intervenants/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Détails de l'Intervenant</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('intervenants.index') }}">Intervenants</a></li>
                        <li class="breadcrumb-item active">Détails</li>
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
                            <h3 class="card-title">
                                <i class="fas fa-user mr-2"></i>
                                {{ $intervenant->identite_fr }}
                                @if($intervenant->archive)
                                    <span class="badge badge-warning ml-2">Archivé</span>
                                @endif
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('intervenants.edit', $intervenant->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                                <a href="{{ route('intervenants.index') }}" class="btn btn-secondary btn-sm ml-1">
                                    <i class="fas fa-arrow-left"></i> Retour
                                </a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
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
                                            <a class="nav-link" id="dossiers-lies-tab" data-toggle="tab" href="#dossiers-lies" role="tab" aria-controls="dossiers-lies" aria-selected="false">
                                                <i class="fas fa-folder-open"></i> Dossiers Liés
                                                @if($intervenant->dossiers && count($intervenant->dossiers) > 0)
                                                    <span class="badge badge-primary ml-1">{{ count($intervenant->dossiers) }}</span>
                                                @endif
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="notes-tab" data-toggle="tab" href="#notes" role="tab" aria-controls="notes" aria-selected="false">
                                                <i class="fas fa-sticky-note"></i> Notes
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="activites-tab" data-toggle="tab" href="#activites" role="tab" aria-controls="activites" aria-selected="false">
                                                <i class="fas fa-calendar-alt"></i> Activités
                                                @if(($intervenant->agendas && count($intervenant->agendas) > 0) || ($intervenant->tasks && count($intervenant->tasks) > 0))
                                                    <span class="badge badge-info ml-1">
                                                        {{ ($intervenant->agendas ? count($intervenant->agendas) : 0) + ($intervenant->tasks ? count($intervenant->tasks) : 0) }}
                                                    </span>
                                                @endif
                                            </a>
                                        </li>
                                    </ul>
                                    
                                    <div class="tab-content" id="intervenantTabsContent">
                                        <!-- Onglet Général -->
                                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                            <div class="p-3">
                                                <!-- Identité -->
                                                <div class="row mb-4">
                                                    <div class="col-md-12">
                                                        <h5 class="text-primary mb-3">
                                                            <i class="fas fa-id-card"></i> Identité
                                                        </h5>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info-group">
                                                            <label class="font-weight-bold">Identité (Français)</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded text-muted">{{ $intervenant->identite_fr }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info-group">
                                                            <label class="font-weight-bold">Identité (Arabe)</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded text-muted">{{ $intervenant->identite_ar ?? 'Non renseigné' }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Type et Catégorie -->
                                                <div class="row mb-4">
                                                    <div class="col-md-12">
                                                        <h5 class="text-primary mb-3">
                                                            <i class="fas fa-tags"></i> Classification
                                                        </h5>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info-group">
                                                            <label class="font-weight-bold">Type</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded text-muted">
                                                                <span class="badge badge-info">
                                                                    {{ ucfirst($intervenant->type) }}
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info-group">
                                                            <label class="font-weight-bold">Catégorie</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded text-muted">
                                                                <span class="badge badge-primary">
                                                                    {{ ucfirst(str_replace('_', ' ', $intervenant->categorie)) }}
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Informations professionnelles -->
                                                <div class="row mb-4">
                                                    <div class="col-md-12">
                                                        <h5 class="text-primary mb-3">
                                                            <i class="fas fa-briefcase"></i> Informations Professionnelles
                                                        </h5>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="info-group">
                                                            <label class="font-weight-bold">Fonction</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded text-muted">{{ $intervenant->fonction ?? 'Non renseigné' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="info-group">
                                                            <label class="font-weight-bold">Forme Sociale</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded text-muted">
                                                                {{ $intervenant->forme_sociale->nom ?? 'Non renseigné' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="info-group">
                                                            <label class="font-weight-bold">Numéro CNI</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded text-muted">{{ $intervenant->numero_cni ?? 'Non renseigné' }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Numéros d'identification -->
                                                <div class="row mb-4">
                                                    <div class="col-md-12">
                                                        <h5 class="text-primary mb-3">
                                                            <i class="fas fa-fingerprint"></i> Numéros d'Identification
                                                        </h5>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info-group">
                                                            <label class="font-weight-bold">RNE</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded text-muted">{{ $intervenant->rne ?? 'Non renseigné' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info-group">
                                                            <label class="font-weight-bold">Numéro CNSS</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded text-muted">{{ $intervenant->numero_cnss ?? 'Non renseigné' }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Statut -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="info-group">
                                                            <label class="font-weight-bold">Statut</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded text-muted">
                                                                @if($intervenant->archive)
                                                                    <span class="badge badge-warning">Archivé</span>
                                                                @else
                                                                    <span class="badge badge-success">Actif</span>
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Onglet Coordonnées -->
                                        <div class="tab-pane fade" id="coordonnees" role="tabpanel" aria-labelledby="coordonnees-tab">
                                            <div class="p-3">
                                                <!-- Coordonnées téléphoniques -->
                                                <h5 class="text-primary mb-3">
                                                    <i class="fas fa-phone"></i> Coordonnées Téléphoniques
                                                </h5>
                                                <div class="row mb-4">
                                                    <div class="col-md-6">
                                                        <div class="info-group">
                                                            <label class="font-weight-bold">Portable 1</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded text-muted">
                                                                @if($intervenant->portable1)
                                                                    <a href="tel:{{ $intervenant->portable1 }}" class="text-decoration-none">
                                                                        <i class="fas fa-phone-alt mr-2 text-success"></i>{{ $intervenant->portable1 }}
                                                                    </a>
                                                                @else
                                                                    Non renseigné
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info-group">
                                                            <label class="font-weight-bold">Portable 2</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded text-muted">
                                                                @if($intervenant->portable2)
                                                                    <a href="tel:{{ $intervenant->portable2 }}" class="text-decoration-none">
                                                                        <i class="fas fa-phone-alt mr-2 text-success"></i>{{ $intervenant->portable2 }}
                                                                    </a>
                                                                @else
                                                                    Non renseigné
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Téléphones fixes -->
                                                <div class="row mb-4">
                                                    <div class="col-md-6">
                                                        <div class="info-group">
                                                            <label class="font-weight-bold">Téléphone Fixe 1</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded text-muted">
                                                                @if($intervenant->fixe1)
                                                                    <a href="tel:{{ $intervenant->fixe1 }}" class="text-decoration-none">
                                                                        <i class="fas fa-phone mr-2 text-primary"></i>{{ $intervenant->fixe1 }}
                                                                    </a>
                                                                @else
                                                                    Non renseigné
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info-group">
                                                            <label class="font-weight-bold">Téléphone Fixe 2</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded text-muted">
                                                                @if($intervenant->fixe2)
                                                                    <a href="tel:{{ $intervenant->fixe2 }}" class="text-decoration-none">
                                                                        <i class="fas fa-phone mr-2 text-primary"></i>{{ $intervenant->fixe2 }}
                                                                    </a>
                                                                @else
                                                                    Non renseigné
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Emails -->
                                                <h5 class="text-primary mb-3">
                                                    <i class="fas fa-envelope"></i> Adresses Email
                                                </h5>
                                                <div class="row mb-4">
                                                    <div class="col-md-6">
                                                        <div class="info-group">
                                                            <label class="font-weight-bold">Email 1</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded text-muted">
                                                                @if($intervenant->mail1)
                                                                    <a href="mailto:{{ $intervenant->mail1 }}" class="text-decoration-none">
                                                                        <i class="fas fa-envelope mr-2 text-info"></i>{{ $intervenant->mail1 }}
                                                                    </a>
                                                                @else
                                                                    Non renseigné
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info-group">
                                                            <label class="font-weight-bold">Email 2</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded text-muted">
                                                                @if($intervenant->mail2)
                                                                    <a href="mailto:{{ $intervenant->mail2 }}" class="text-decoration-none">
                                                                        <i class="fas fa-envelope mr-2 text-info"></i>{{ $intervenant->mail2 }}
                                                                    </a>
                                                                @else
                                                                    Non renseigné
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Adresses -->
                                                <h5 class="text-primary mb-3">
                                                    <i class="fas fa-map-marker-alt"></i> Adresses
                                                </h5>
                                                <div class="row mb-4">
                                                    <div class="col-md-6">
                                                        <div class="info-group">
                                                            <label class="font-weight-bold">Adresse 1</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded text-muted">
                                                                @if($intervenant->adresse1)
                                                                    <i class="fas fa-map-marker-alt mr-2 text-danger"></i>{{ $intervenant->adresse1 }}
                                                                @else
                                                                    Non renseigné
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info-group">
                                                            <label class="font-weight-bold">Adresse 2</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded text-muted">
                                                                @if($intervenant->adresse2)
                                                                    <i class="fas fa-map-marker-alt mr-2 text-danger"></i>{{ $intervenant->adresse2 }}
                                                                @else
                                                                    Non renseigné
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Site web et Fax -->
                                                <h5 class="text-primary mb-3">
                                                    <i class="fas fa-globe"></i> Autres Coordonnées
                                                </h5>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="info-group">
                                                            <label class="font-weight-bold">Site Internet</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded text-muted">
                                                                @if($intervenant->site_internet)
                                                                    <a href="{{ $intervenant->site_internet }}" target="_blank" class="text-decoration-none">
                                                                        <i class="fas fa-external-link-alt mr-2 text-warning"></i>{{ $intervenant->site_internet }}
                                                                    </a>
                                                                @else
                                                                    Non renseigné
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info-group">
                                                            <label class="font-weight-bold">Fax</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded text-muted">{{ $intervenant->fax ?? 'Non renseigné' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Onglet Fichiers -->
                                        <div class="tab-pane fade" id="fichiers" role="tabpanel" aria-labelledby="fichiers-tab">
                                            <div class="p-3">
                                                <h5 class="text-primary mb-3">
                                                    <i class="fas fa-files-o"></i> Fichiers Attachés
                                                </h5>
                                                
                                                @if($intervenant->files && count($intervenant->files) > 0)
                                                    <div class="alert alert-info">
                                                        <h6><i class="icon fas fa-info"></i> Informations</h6>
                                                        <p class="mb-0">
                                                            Cet intervenant a {{ count($intervenant->files) }} fichier(s) attaché(s). 
                                                            Vous pouvez visualiser ou télécharger chaque fichier.
                                                        </p>
                                                    </div>

                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped">
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
                                                                            <span class="badge badge-info">{{ pathinfo($file->file_path, PATHINFO_EXTENSION) }}</span> {{$file->file_path ?? $file->file_name }}
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
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <div class="text-center py-5">
                                                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                                        <h4 class="text-muted">Aucun fichier attaché</h4>
                                                        <p class="text-muted">Aucun fichier n'a été attaché à cet intervenant.</p>
                                                        <a href="{{ route('intervenants.edit', $intervenant->id) }}#fichiers" 
                                                           class="btn btn-primary mt-2">
                                                            <i class="fas fa-upload mr-1"></i> Ajouter des fichiers
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Onglet Intervenants Liés -->
                                        <div class="tab-pane fade" id="intervenants-lies" role="tabpanel" aria-labelledby="intervenants-lies-tab">
                                            <div class="p-3">
                                                @if($intervenant->intervenantsLies && count($intervenant->intervenantsLies) > 0)
                                                    <div class="alert alert-info">
                                                        <h5><i class="icon fas fa-info"></i> Relations établies</h5>
                                                        <p class="mb-0">
                                                            Cet intervenant est lié à {{ count($intervenant->intervenantsLies) }} autre(s) intervenant(s) avec les relations suivantes.
                                                        </p>
                                                    </div>

                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>Intervenant</th>
                                                                    <th>Relation</th>
                                                                    <th>Type</th>
                                                                    <th>Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($intervenant->intervenantsLies as $intervenantLie)
                                                                <tr>
                                                                    <td>{{ $intervenantLie->id }}</td>
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="mr-3">
                                                                                <i class="fas fa-user-circle fa-2x text-primary"></i>
                                                                            </div>
                                                                            <div>
                                                                                <strong>{{ $intervenantLie->identite_fr }}</strong>
                                                                                @if($intervenantLie->identite_ar)
                                                                                    <br>
                                                                                    <small class="text-muted">{{ $intervenantLie->identite_ar }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td> {{ $intervenantLie->pivot->relation ?? 'N/A' }} </td>
                                                                    <td>
                                                                        {{ $intervenantLie->type }}
                                                                    </td>
                                                                    <td>
                                                                        <div class="btn-group btn-group-sm">
                                                                            <a href="{{ route('intervenants.show', $intervenantLie->id) }}" 
                                                                               class="btn btn-info" title="Voir">
                                                                                <i class="fas fa-eye"></i>
                                                                            </a>
                                                                            <a href="{{ route('intervenants.edit', $intervenantLie->id) }}" 
                                                                               class="btn btn-warning" title="Modifier">
                                                                                <i class="fas fa-edit"></i>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <!-- Statistiques des relations -->
                                                    <div class="row mt-4 d-none">
                                                        <div class="col-md-12">
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    <h6 class="card-title mb-0">
                                                                        <i class="fas fa-chart-pie mr-1"></i>
                                                                        Répartition des Relations
                                                                    </h6>
                                                                </div>
                                                                <div class="card-body">
                                                                    @php
                                                                        $relationsCount = [];
                                                                        foreach($intervenant->intervenantsLies as $intervenantLie) {
                                                                            $relation = $intervenantLie->pivot->relation;
                                                                            $relationsCount[$relation] = ($relationsCount[$relation] ?? 0) + 1;
                                                                        }
                                                                    @endphp
                                                                    <div class="row">
                                                                        @foreach($relationsCount as $relation => $count)
                                                                        <div class="col-md-4 mb-2">
                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                <span class="text-capitalize">{{ str_replace('_', ' ', $relation) }}</span>
                                                                                <span class="badge badge-primary">{{ $count }}</span>
                                                                            </div>
                                                                            <div class="progress" style="height: 8px;">
                                                                                <div class="progress-bar" 
                                                                                     style="width: {{ ($count / count($intervenant->intervenantsLies)) * 100 }}%">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="text-center py-5">
                                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                        <h4 class="text-muted">Aucun intervenant lié</h4>
                                                        <p class="text-muted">Cet intervenant n'est pas encore lié à d'autres intervenants.</p>
                                                        <a href="{{ route('intervenants.edit', $intervenant->id) }}#intervenants-lies" 
                                                           class="btn btn-primary mt-2">
                                                            <i class="fas fa-link mr-1"></i> Ajouter des liens
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Onglet Notes -->
                                        <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">
                                            <div class="p-3">
                                                <!-- Notes -->
                                                <div class="info-group">
                                                    <label class="font-weight-bold">Notes et Observations</label>
                                                    <div class="form-control-plaintext bg-light p-3 rounded" style="min-height: 200px;">
                                                        @if($intervenant->notes)
                                                            {!! nl2br(e($intervenant->notes)) !!}
                                                        @else
                                                            <p class="text-muted font-italic">Aucune note n'a été enregistrée pour cet intervenant.</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Onglet Activités -->
                                        <div class="tab-pane fade" id="activites" role="tabpanel" aria-labelledby="activites-tab">
                                            <div class="p-3">
                                                <!-- Agenda -->
                                                <h5 class="text-primary mb-3">
                                                    <i class="fas fa-calendar-check"></i> Événements Agenda
                                                </h5>
                                                @if($intervenant->agendas && count($intervenant->agendas) > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Titre</th>
                                                                    <th>Description</th>
                                                                    <th>Date Début</th>
                                                                    <th>Date Fin</th>
                                                                    <th>Catégorie</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($intervenant->agendas as $agenda)
                                                                <tr>
                                                                    <td>{{ $agenda->titre }}</td>
                                                                    <td>{{ Str::limit($agenda->description, 50) }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($agenda->date_debut)->format('d/m/Y') }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($agenda->date_fin)->format('d/m/Y') }}</td>
                                                                    <td>
                                                                        <span class="badge badge-info">{{ ucfirst($agenda->categorie) }}</span>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <div class="alert alert-info">
                                                        <i class="fas fa-info-circle mr-2"></i>
                                                        Aucun événement agenda associé à cet intervenant.
                                                    </div>
                                                @endif

                                                <!-- Tâches -->
                                                <h5 class="text-primary mb-3 mt-4">
                                                    <i class="fas fa-tasks"></i> Tâches
                                                </h5>
                                                @if($intervenant->tasks && count($intervenant->tasks) > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Titre</th>
                                                                    <th>Description</th>
                                                                    <th>Date Début</th>
                                                                    <th>Date Fin</th>
                                                                    <th>Priorité</th>
                                                                    <th>Statut</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($intervenant->tasks as $task)
                                                                <tr>
                                                                    <td>{{ $task->titre }}</td>
                                                                    <td>{{ Str::limit($task->description, 50) }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($task->date_debut)->format('d/m/Y') }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($task->date_fin)->format('d/m/Y') }}</td>
                                                                    <td>
                                                                        @php
                                                                            $priorityBadge = [
                                                                                'basse' => 'badge-secondary',
                                                                                'moyenne' => 'badge-info',
                                                                                'haute' => 'badge-warning',
                                                                                'urgente' => 'badge-danger'
                                                                            ][$task->priorite] ?? 'badge-secondary';
                                                                        @endphp
                                                                        <span class="badge {{ $priorityBadge }}">{{ ucfirst($task->priorite) }}</span>
                                                                    </td>
                                                                    <td>
                                                                        @php
                                                                            $statusBadge = [
                                                                                'en_attente' => 'badge-secondary',
                                                                                'en_cours' => 'badge-primary',
                                                                                'termine' => 'badge-success',
                                                                                'annule' => 'badge-danger'
                                                                            ][$task->statut] ?? 'badge-secondary';
                                                                        @endphp
                                                                        <span class="badge {{ $statusBadge }}">{{ ucfirst(str_replace('_', ' ', $task->statut)) }}</span>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <div class="alert alert-info">
                                                        <i class="fas fa-info-circle mr-2"></i>
                                                        Aucune tâche associée à cet intervenant.
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- Onglet Dossiers -->
                                        <div class="tab-pane fade" id="dossiers-lies" role="tabpanel" aria-labelledby="dossiers-tab">
                                            <div class="p-3">
                                                <!-- Dossiers -->
                                                <h5 class="text-primary mb-3">
                                                    <i class="fas fa-folder-open"></i> Dossiers Liés
                                                </h5>
                                                @if($intervenant->dossiers && count($intervenant->dossiers) > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Numéro Dossier</th>
                                                                    <th>Nom Dossier</th>
                                                                    <th>Objet</th>
                                                                    <th>Archivé</th>
                                                                    <th>Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($intervenant->dossiers as $dossier)
                                                                <tr>
                                                                    <td>{{ $dossier->numero_dossier }}</td>
                                                                    <td>{{ $dossier->nom_dossier }}</td>
                                                                    <td>{{ $dossier->objet }}</td>
                                                                    <td>{{ $dossier->archive ? 'Oui' : 'Non' }}</td>
                                                                    <td>
                                                                        <div class="btn-group btn-group-sm">
                          <a href="{{ route('dossiers.show', $dossier->id) }}" 
                             class="btn btn-info" title="Voir">
                            <i class="fas fa-eye"></i>
                          </a>
                          <a href="{{ route('dossiers.edit', $dossier->id) }}" 
                             class="btn btn-warning" title="Modifier">
                            <i class="fas fa-edit"></i>
                          </a>
                          <form action="{{ route('dossiers.destroy', $dossier->id) }}" 
                                method="POST" class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" title="Supprimer"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce dossier?')">
                              <i class="fas fa-trash"></i>
                            </button>
                          </form>
                        </div>
                      </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <div class="alert alert-info">
                                                        <i class="fas fa-info-circle mr-2"></i>
                                                        Aucun dossier lié à cet intervenant.
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-plus"></i> Créé le: {{ \Carbon\Carbon::parse($intervenant->created_at)->format('d/m/Y à H:i') }}
                                    </small>
                                    @if($intervenant->created_at != $intervenant->updated_at)
                                        <small class="text-muted ml-3">
                                            <i class="fas fa-edit"></i> Modifié le: {{ \Carbon\Carbon::parse($intervenant->updated_at)->format('d/m/Y à H:i') }}
                                        </small>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('intervenants.edit', $intervenant->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <a href="{{ route('intervenants.index') }}" class="btn btn-secondary ml-1">
                                        <i class="fas fa-arrow-left"></i> Retour à la liste
                                    </a>
                                </div>
                            </div>
                        </div>
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

.info-group {
    margin-bottom: 1.5rem;
}

.info-group label {
    color: #495057;
    margin-bottom: 0.5rem;
    display: block;
}

.info-group .form-control-plaintext {
    padding: 0.5rem 0;
    min-height: auto;
    /* border-bottom: 1px solid #e9ecef; */
}

/* h5.text-primary {
    border-bottom: 2px solid #007bff;
    padding-bottom: 0.5rem;
    margin-bottom: 1.5rem !important;
} */

.badge {
    font-size: 0.85em;
    padding: 0.4em 0.8em;
}

.table th {
    border-top: none;
    background-color: #f8f9fa;
}

.progress {
    background-color: #e9ecef;
}

.progress-bar {
    background-color: #007bff;
}
.form-control-plaintext {
    min-height: 38px;
    /* border: 1px solid #ced4da; */
}
.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
}
.alert-info {
    background-color: #e8f4fd;
    border-color: #b6e0fe;
    color:black;
}
.bg-light {
    background-color: #f8f9fa !important;
}
</style>
<script>
$(document).ready(function() {
    // Initialisation des onglets
    $('#intervenantTabs a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    // Affichage des tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endsection