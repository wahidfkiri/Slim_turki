@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Détails de la Facture</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('factures.index') }}">Factures</a></li>
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
                            <h3 class="card-title">Informations de la facture</h3>
                            <div class="card-tools">
                                @if(auth()->user()->hasPermission('edit_factures'))
                                    <a href="{{ route('factures.edit', $facture) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Type de pièce -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="type_piece">Type de pièce</label>
                                        @php
                                            $typeColors = [
                                                'facture' => 'primary',
                                                'note_frais' => 'info',
                                                'note_provision' => 'warning',
                                                'avoir' => 'success'
                                            ];
                                            $typeClass = $typeColors[$facture->type_piece] ?? 'secondary';
                                        @endphp
                                        <p class="form-control-plaintext bg-light p-2 rounded">
                                            <span class="badge badge-{{ $typeClass }} text-uppercase">
                                                {{ str_replace('_', ' ', $facture->type_piece) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Numéro -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="numero">Numéro</label>
                                        <p class="form-control-plaintext bg-light p-2 rounded font-weight-bold">
                                            {{ $facture->numero }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Date d'émission -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date_emission">Date d'émission</label>
                                        <p class="form-control-plaintext bg-light p-2 rounded">
                                            {{ $facture->date_emission->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Dossier -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dossier_id">Dossier</label>
                                        <p class="form-control-plaintext bg-light p-2 rounded">
                                            @if($facture->dossier)
                                                <a href="{{ route('dossiers.show', $facture->dossier) }}" class="text-primary">
                                                    {{ $facture->dossier->numero_dossier }}
                                                </a>
                                                @if($facture->dossier->nom_dossier)
                                                    <br><small class="text-muted">{{ $facture->dossier->nom_dossier }}</small>
                                                @endif
                                            @else
                                                <span class="text-muted">Non assigné</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Client -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="client_id">Client</label>
                                        <p class="form-control-plaintext bg-light p-2 rounded">
                                            @if($facture->client)
                                                {{ $facture->client->identite_fr ?? $facture->client->identite_ar }}
                                                @if($facture->client->email)
                                                    <br><small class="text-muted">{{ $facture->client->email }}</small>
                                                @endif
                                            @else
                                                <span class="text-muted">Non assigné</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Montants -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="montant_ht">Montant HT (DT)</label>
                                        <p class="form-control-plaintext bg-light p-2 rounded">
                                            {{ number_format($facture->montant_ht, 2, ',', ' ') }} DT
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="montant_tva">Montant TVA (DT)</label>
                                        <p class="form-control-plaintext bg-light p-2 rounded">
                                            {{ number_format($facture->montant_tva, 2, ',', ' ') }} DT
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="montant">Montant TTC (DT)</label>
                                        <p class="form-control-plaintext bg-light p-2 rounded font-weight-bold text-success">
                                            {{ number_format($facture->montant, 2, ',', ' ') }} DT
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Statut -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="statut">Statut</label>
                                        <p class="form-control-plaintext bg-light p-2 rounded">
                                            @if($facture->statut == 'payé')
                                                <span class="badge badge-success text-uppercase">
                                                    <i class="fas fa-check-circle"></i> Payé
                                                </span>
                                            @else
                                                <span class="badge badge-danger text-uppercase">
                                                    <i class="fas fa-clock"></i> Non payé
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Pièce jointe -->
                            <div class="form-group">
                                <label for="piece_jointe">Pièce jointe</label>
                                @if($facture->piece_jointe)
                                    <div class="alert alert-success">
                                        <i class="fas 
                                            @if($facture->piece_jointe_extension == 'pdf') fa-file-pdf
                                            @elseif(in_array($facture->piece_jointe_extension, ['doc', 'docx'])) fa-file-word
                                            @elseif(in_array($facture->piece_jointe_extension, ['xls', 'xlsx'])) fa-file-excel
                                            @elseif(in_array($facture->piece_jointe_extension, ['jpg', 'jpeg', 'png', 'gif'])) fa-file-image
                                            @else fa-file
                                            @endif
                                        "></i>
                                        <a href="{{ $facture->piece_jointe_url }}" target="_blank" class="ml-2 font-weight-bold">
                                            {{ $facture->piece_jointe }}
                                        </a>
                                        <span class="badge badge-info ml-2">
                                            {{ strtoupper($facture->piece_jointe_extension) }}
                                        </span>
                                        <small class="d-block mt-1 text-muted">
                                            Téléchargé le {{ $facture->updated_at->format('d/m/Y à H:i') }}
                                        </small>
                                    </div>
                                @else
                                    <p class="form-control-plaintext bg-light p-2 rounded text-muted">
                                        <i class="fas fa-file"></i> Aucune pièce jointe
                                    </p>
                                @endif
                            </div>

                            <!-- Commentaires -->
                            <div class="form-group">
                                <label for="commentaires">Commentaires</label>
                                <div class="bg-light p-3 rounded" style="min-height: 100px;">
                                    @if($facture->commentaires)
                                        {!! nl2br(e($facture->commentaires)) !!}
                                    @else
                                        <span class="text-muted">Aucun commentaire</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Informations de suivi -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Informations de suivi</label>
                                        <div class="alert alert-info" style="color:black;">
                                            <small>
                                                <strong>Créé le:</strong> {{ $facture->created_at->format('d/m/Y à H:i') }}<br>
                                                <strong>Modifié le:</strong> {{ $facture->updated_at->format('d/m/Y à H:i') }}
                                                @if($facture->dossier)
                                                    <br><strong>Dossier:</strong> {{ $facture->dossier->numero_dossier }}
                                                    @if($facture->dossier->nom_dossier)
                                                        - {{ $facture->dossier->nom_dossier }}
                                                    @endif
                                                @endif
                                                @if($facture->client)
                                                    <br><strong>Client:</strong> {{ $facture->client->identite_fr ?? $facture->client->identite_ar }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <a href="{{ route('factures.index') }}" class="btn btn-default btn-lg">
                                <i class="fas fa-arrow-left"></i> Retour à la liste
                            </a>

                            @if(auth()->user()->hasPermission('edit_factures'))
                                <a href="{{ route('factures.edit', $facture) }}" class="btn btn-warning btn-lg">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                            @endif

                            @if(auth()->user()->hasPermission('delete_factures'))
                                <button type="button" class="btn btn-danger btn-lg float-right" 
                                        onclick="confirmDelete({{ $facture->id }}, '{{ addslashes($facture->numero) }}')">
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
@if(auth()->user()->hasPermission('delete_factures'))
    <form id="delete-form-{{ $facture->id }}" 
          action="{{ route('factures.destroy', $facture) }}" 
          method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endif

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script>
    // Fonction de confirmation de suppression
    function confirmDelete(factureId, factureNumero = 'cette facture') {
        if (confirm('Êtes-vous sûr de vouloir supprimer la facture "' + factureNumero + '" ? Cette action est irréversible.')) {
            // Afficher un indicateur de chargement
            const deleteButton = document.querySelector('.btn-danger');
            const originalText = deleteButton.innerHTML;
            deleteButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Suppression...';
            deleteButton.disabled = true;

            // Soumettre le formulaire de suppression
            document.getElementById('delete-form-' + factureId).submit();
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
    .badge {
        font-size: 0.9em;
        padding: 0.4em 0.8em;
    }
</style>
@endsection