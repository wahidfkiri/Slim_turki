@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Détails du Dossier</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dossiers.index') }}">Dossiers</a></li>
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
                            <h3 class="card-title">Informations du dossier</h3>
                            <div class="card-tools">
                                @if(auth()->user()->hasPermission('edit_dossiers'))
                                    <a href="{{ route('dossiers.edit', $dossier) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
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
                                        <li class="nav-item">
                                            <a class="nav-link" id="agenda-tab" data-toggle="tab" href="#agenda" role="tab" aria-controls="agenda" aria-selected="false">
                                                <i class="fas fa-calendar-alt"></i> Agenda
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="tasks-tab" data-toggle="tab" href="#tasks" role="tab" aria-controls="tasks" aria-selected="false">
                                                <i class="fas fa-tasks"></i> Tâches
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="facturation-tab" data-toggle="tab" href="#facturation" role="tab" aria-controls="facturation" aria-selected="false">
                                                <i class="fas fa-file-invoice-dollar"></i> Factures
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="timesheet-tab" data-toggle="tab" href="#timesheet" role="tab" aria-controls="timesheet" aria-selected="false">
                                                <i class="fas fa-clock"></i> Timesheet
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
                                                            <label for="numero_dossier">Numéro du dossier</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded">{{ $dossier->numero_dossier }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="date_entree">Date d'entrée</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded">{{ $dossier->date_entree->format('d/m/Y') }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="nom_dossier">Nom du dossier</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded">{{ $dossier->nom_dossier }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="objet">Objet du dossier</label>
                                                            <div class="bg-light p-3 rounded" style="min-height: 100px;">
                                                                @if($dossier->objet)
                                                                    {!! nl2br(e($dossier->objet)) !!}
                                                                @else
                                                                    <span class="text-muted">Aucun objet défini</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Domaines -->
                                                <h5 class="text-primary mb-3 mt-4"><i class="fas fa-tags"></i> Classification</h5>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="domaine_id">Domaine</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded">
                                                                {{ $dossier->domaine->nom ?? 'Non défini' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="sous_domaine_id">Sous-domaine</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded">
                                                                {{ $dossier->sousDomaine->nom ?? 'Non défini' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Type de dossier -->
                                                <h5 class="text-primary mb-3 mt-4"><i class="fas fa-balance-scale"></i> Type de dossier</h5>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Dossier de conseil</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded">
                                                                @if($dossier->conseil)
                                                                    <span class="badge badge-success">Oui</span>
                                                                @else
                                                                    <span class="badge badge-secondary">Non</span>
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Dossier contentieux</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded">
                                                                @if($dossier->contentieux)
                                                                    <span class="badge badge-success">Oui</span>
                                                                @else
                                                                    <span class="badge badge-secondary">Non</span>
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Statut -->
                                                <h5 class="text-primary mb-3 mt-4"><i class="fas fa-info-circle"></i> Statut</h5>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Archivé</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded">
                                                                @if($dossier->archive)
                                                                    <span class="badge badge-warning">Archivé</span>
                                                                @else
                                                                    <span class="badge badge-info">Actif</span>
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
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
                                                            <p class="form-control-plaintext bg-light p-2 rounded">
                                                                {{ $dossier->numero_role ?? 'Non défini' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="chambre">Chambre</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded">
                                                                {{ $dossier->chambre ? ucfirst($dossier->chambre) : 'Non définie' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="numero_chambre">Numéro de chambre</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded">
                                                                {{ $dossier->numero_chambre ?? 'Non défini' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="numero_parquet">Numéro de parquet</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded">
                                                                {{ $dossier->numero_parquet ?? 'Non défini' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="numero_instruction">Numéro d'instruction</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded">
                                                                {{ $dossier->numero_instruction ?? 'Non défini' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="numero_plainte">Numéro de plainte</label>
                                                            <p class="form-control-plaintext bg-light p-2 rounded">
                                                                {{ $dossier->numero_plainte ?? 'Non défini' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Onglet Intervenants -->
                                        <div class="tab-pane fade" id="intervenants" role="tabpanel" aria-labelledby="intervenants-tab">
                                            <div class="p-3">
                                                <h5 class="text-primary mb-3"><i class="fas fa-handshake"></i> Intervenants du dossier</h5>
                                                
                                                <!-- Client principal -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Client principal</label>
                                                            @php
                                                                $clientPrincipal = $dossier->intervenants()->wherePivot('role', 'client')->first();
                                                            @endphp
                                                            @if($clientPrincipal)
                                                                <div class="bg-light p-3 rounded">
                                                                    <h6 class="mb-1">{{ $clientPrincipal->identite_fr }}</h6>
                                                                    <small class="text-muted">
                                                                        {{ $clientPrincipal->categorie }} • 
                                                                        {{ $clientPrincipal->email ?? 'Email non disponible' }} • 
                                                                        {{ $clientPrincipal->telephone ?? 'Téléphone non disponible' }}
                                                                    </small>
                                                                </div>
                                                            @else
                                                                <p class="form-control-plaintext bg-light p-2 rounded text-muted">
                                                                    Aucun client principal défini
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Autres intervenants -->
                                                <div class="row mt-4">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Autres intervenants</label>
                                                            <div class="p-3">
                                                @if($dossier->intervenants && count($dossier->intervenants) > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Intervenant</th>
                                                                    <th>Role</th>
                                                                    <th>Type</th>
                                                                    <th>Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($dossier->intervenants as $intervenantLie)
                                                                @if($intervenantLie->id !== $clientPrincipal->id)
                                                                <tr>
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
                                                                    <td> {{ $intervenantLie->pivot->role ?? 'N/A' }} </td>
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
                                                                @endif
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                @else
                                                    <div class="text-center py-5">
                                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                        <h4 class="text-muted">Aucun intervenant lié</h4>
                                                        <p class="text-muted">Cet dossier n'est pas encore lié à d'autres intervenants.</p>
                                                        <a href="{{ route('dossiers.edit', $dossier->id) }}#intervenants-lies" 
                                                           class="btn btn-primary mt-2">
                                                            <i class="fas fa-link mr-1"></i> Ajouter des liens
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Onglet Équipe -->
                                        <div class="tab-pane fade" id="equipe" role="tabpanel" aria-labelledby="equipe-tab">
                                            <div class="p-3">
                                                <h5 class="text-primary mb-3"><i class="fas fa-users-cog"></i> Équipe en charge</h5>
                                                
                                                <!-- Avocat responsable -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Avocat responsable</label>
                                                            @php
                                                                $avocatResponsable = $dossier->users()->wherePivot('role', 'avocat')->first();
                                                            @endphp
                                                            @if($avocatResponsable)
                                                                <div class="bg-light p-3 rounded">
                                                                    <h6 class="mb-1">{{ $avocatResponsable->name }}</h6>
                                                                    <small class="text-muted">
                                                                        {{ $avocatResponsable->fonction }} • 
                                                                        {{ $avocatResponsable->email }} • 
                                                                        Priorité: {{ $avocatResponsable->pivot->ordre ?? 1 }}
                                                                    </small>
                                                                </div>
                                                            @else
                                                                <p class="form-control-plaintext bg-light p-2 rounded text-muted">
                                                                    Aucun avocat responsable défini
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Équipe supplémentaire -->
                                                <div class="row mt-4">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Membres supplémentaires de l'équipe</label>
                                                            @php
                                                                $equipeSupplementaire = $dossier->users()->wherePivot('role', '!=', 'avocat')->get();
                                                            @endphp
                                                            @if($equipeSupplementaire->count() > 0)
                                                                <div class="bg-light p-3 rounded">
                                                                    @foreach($equipeSupplementaire as $membre)
                                                                        <div class="mb-2 pb-2 border-bottom">
                                                                            <h6 class="mb-1">{{ $membre->name }}</h6>
                                                                            <small class="text-muted">
                                                                                Rôle: {{ $membre->pivot->role }} • 
                                                                                {{ $membre->fonction }} • 
                                                                                {{ $membre->email }}
                                                                            </small>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <p class="form-control-plaintext bg-light p-2 rounded text-muted">
                                                                    Aucun membre supplémentaire dans l'équipe
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Onglet Dossiers -->
                                        <div class="tab-pane fade" id="dossiers" role="tabpanel" aria-labelledby="dossiers-tab">
                                            <div class="p-3">
                                                <h5 class="text-primary mb-3"><i class="fas fa-money-bill-wave"></i> Informations de dossiers liés</h5>

                                                 @if($dossier->dossiersLies && $dossier->dossiersLies->count() > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Numéro Dossier</th>
                                                                    <th>Nom Dossier</th>
                                                                    <th>Date Entrée</th>
                                                                    <th>Domaine</th>
                                                                    <th>Sous Domaine</th>
                                                                    <th>Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($dossier->dossiersLies as $dossierLie)
                                                                <tr>
                                                                    <td>
                                                                        {{ $dossierLie->numero_dossier }}
                                                                    </td>
                                                                    <td>{{ $dossierLie->nom_dossier }}</td>
                                                                    <td>{{ $dossierLie->date_entree->format('d/m/Y') }}</td>
                                                                    <td>{{ $dossierLie->domaine->nom }}</td>
                                                                    <td>{{ $dossierLie->sousDomaine->nom ?? null}}</td>
                                                                    <td>
                                                                        <a href="{{route('dossiers.show', $dossierLie)}}" class="btn btn-sm btn-info" title="Voir">
                                                                            <i class="fas fa-eye"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <div class="alert alert-info" style="color:black;">
                                                        <h6><i class="icon fas fa-info"></i> Information</h6>
                                                        <p class="mb-0">
                                                            Aucune facture n'a été ajoutée à ce dossier.
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- Onglet Notes -->
                                        <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">
                                            <div class="p-3">
                                                <div class="form-group">
                                                    <label>Notes et observations</label>
                                                    <div class="bg-light p-3 rounded" style="min-height: 200px;">
                                                        @if($dossier->note)
                                                            {!! nl2br(e($dossier->note)) !!}
                                                        @else
                                                            <span class="text-muted">Aucune note ou observation</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Informations de suivi -->
                                                <h5 class="text-primary mb-3 mt-4"><i class="fas fa-history"></i> Informations de suivi</h5>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="alert alert-info" style="color:black;">
                                                            <small>
                                                                <strong>Créé le:</strong> {{ $dossier->created_at->format('d/m/Y à H:i') }}<br>
                                                                <strong>Modifié le:</strong> {{ $dossier->updated_at->format('d/m/Y à H:i') }}<br>
                                                                @if($dossier->domaine)
                                                                    <strong>Domaine:</strong> {{ $dossier->domaine->nom }}<br>
                                                                @endif
                                                                @if($dossier->sousDomaine)
                                                                    <strong>Sous-domaine:</strong> {{ $dossier->sousDomaine->nom ?? N/A}}
                                                                @endif
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <x-dossier.agenda.list :dossier="$dossier" :users="$users" :intervenants="$intervenants" :categories="$categories" :types="$types" />
                                        <x-dossier.task.liste :dossier="$dossier" />
                                        <x-dossier.facturation.list :dossier="$dossier" />
                                        <x-dossier.timesheet.liste :dossier="$dossier" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <a href="{{ route('dossiers.index') }}" class="btn btn-default btn-lg">
                                <i class="fas fa-arrow-left"></i> Retour à la liste
                            </a>

                            @if(auth()->user()->hasPermission('edit_dossiers'))
                                <a href="{{ route('dossiers.edit', $dossier) }}" class="btn btn-warning btn-lg">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                            @endif

                            @if(auth()->user()->hasPermission('delete_dossiers'))
                                <button type="button" class="btn btn-danger btn-lg float-right" 
                                        onclick="confirmDelete({{ $dossier->id }}, '{{ addslashes($dossier->nom_dossier) }}')">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            @endif
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- Formulaire de suppression -->
@can('delete_dossiers')
    <form id="delete-form-{{ $dossier->id }}" 
          action="{{ route('dossiers.destroy', $dossier) }}" 
          method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endcan

@section('scripts')
<script>
// Fonction de confirmation de suppression
function confirmDelete(dossierId, dossierTitle = 'ce dossier') {
    if (confirm('Êtes-vous sûr de vouloir supprimer le dossier "' + dossierTitle + '" ? Cette action est irréversible.')) {
        // Afficher un indicateur de chargement
        const deleteButton = document.querySelector('.btn-danger');
        const originalText = deleteButton.innerHTML;
        deleteButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Suppression...';
        deleteButton.disabled = true;

        // Soumettre le formulaire de suppression
        document.getElementById('delete-form-' + dossierId).submit();
    }
}

$(document).ready(function() {
    // Ajouter un style pour les badges
    $('.badge').css({
        'font-size': '0.9em',
        'padding': '0.4em 0.8em'
    });
});
</script>
@endsection

<style>
.form-control-plaintext {
    min-height: 38px;
    border: 1px solid #ced4da;
}
.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
}
.alert-info {
    background-color: #e8f4fd;
    border-color: #b6e0fe;
}
.bg-light {
    background-color: #f8f9fa !important;
}
</style>
@endsection