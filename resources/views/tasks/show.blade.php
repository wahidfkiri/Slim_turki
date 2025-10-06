@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Détails de la Tâche</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}">Tâches</a></li>
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
                            <h3 class="card-title">Informations de la tâche</h3>
                            <div class="card-tools">
                                @can('edit_tasks')
                                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Titre -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="titre">Titre</label>
                                        <p class="form-control-plaintext bg-light p-2 rounded">{{ $task->titre }}</p>
                                    </div>
                                </div>

                                <!-- Priorité -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="priorite">Priorité</label>
                                        @php
                                            $priorityColors = [
                                                'basse' => 'success',
                                                'normale' => 'info',
                                                'haute' => 'warning',
                                                'urgente' => 'danger'
                                            ];
                                            $priorityClass = $priorityColors[$task->priorite] ?? 'secondary';
                                        @endphp
                                        <p class="form-control-plaintext bg-light p-2 rounded">
                                            <span class="badge badge-{{ $priorityClass }} text-uppercase">
                                                {{ $task->priorite }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Statut -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="statut">Statut</label>
                                        @php
                                            $statusColors = [
                                                'a_faire' => 'secondary',
                                                'en_cours' => 'primary',
                                                'terminee' => 'success',
                                                'en_retard' => 'danger'
                                            ];
                                            $statusClass = $statusColors[$task->statut] ?? 'secondary';
                                        @endphp
                                        <p class="form-control-plaintext bg-light p-2 rounded">
                                            <span class="badge badge-{{ $statusClass }} text-uppercase">
                                                {{ str_replace('_', ' ', $task->statut) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Utilisateur assigné -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="utilisateur_id">Assigné à</label>
                                        <p class="form-control-plaintext bg-light p-2 rounded">
                                            {{ $task->utilisateur->name ?? 'Non assigné' }}
                                            @if($task->utilisateur && $task->utilisateur->fonction)
                                                <small class="text-muted">({{ $task->utilisateur->fonction }})</small>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Date de début -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="date_debut">Date de début</label>
                                        <p class="form-control-plaintext bg-light p-2 rounded">
                                            {{ $task->date_debut ? $task->date_debut->format('d/m/Y') : 'Non définie' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Date de fin -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="date_fin">Date de fin</label>
                                        <p class="form-control-plaintext bg-light p-2 rounded">
                                            {{ $task->date_fin ? $task->date_fin->format('d/m/Y') : 'Non définie' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Dossier -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="dossier_id">Dossier</label>
                                        <p class="form-control-plaintext bg-light p-2 rounded">
                                            {{ $task->dossier->numero_dossier ?? 'Non assigné' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Intervenant -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="intervenant_id">Intervenant</label>
                                        <p class="form-control-plaintext bg-light p-2 rounded">
                                            {{ $task->intervenant->identite_fr ?? 'Non assigné' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="form-group">
                                <label for="description">Description</label>
                                <div class="bg-light p-3 rounded" style="min-height: 100px;">
                                    @if($task->description)
                                        {!! nl2br(e($task->description)) !!}
                                    @else
                                        <span class="text-muted">Aucune description fournie</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Note -->
                            <div class="form-group">
                                <label for="note">Notes supplémentaires</label>
                                <div class="bg-light p-3 rounded" style="min-height: 80px;">
                                    @if($task->note)
                                        {!! nl2br(e($task->note)) !!}
                                    @else
                                        <span class="text-muted">Aucune note supplémentaire</span>
                                    @endif
                                </div>
                                <small class="form-text text-muted">
                                    Ces notes sont internes et ne sont pas visibles par le client.
                                </small>
                            </div>

                            <!-- Informations de suivi -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Informations de suivi</label>
                                        <div class="alert alert-info text-black" style="color:black;">
                                            <small>
                                                <strong>Créé le:</strong> {{ $task->created_at->format('d/m/Y H:i') }}<br>
                                                <strong>Modifié le:</strong> {{ $task->updated_at->format('d/m/Y H:i') }}
                                                @if($task->dossier)
                                                    <br><strong>Dossier:</strong> {{ $task->dossier->numero_dossier }} - {{ $task->dossier->nom_dossier }}
                                                @endif
                                                @if($task->intervenant)
                                                    <br><strong>Intervenant:</strong> {{ $task->intervenant->identite_fr }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <a href="{{ route('tasks.index') }}" class="btn btn-default btn-lg">
                                <i class="fas fa-arrow-left"></i> Retour à la liste
                            </a>

                            @can('edit_tasks')
                                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning btn-lg">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                            @endcan

                            @can('delete_tasks')
                                <button type="button" class="btn btn-danger btn-lg float-right delete-task-btn" data-id="{{ $task->id }}">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            @endcan
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
@can('delete_tasks')
    <form id="delete-form-{{ $task->id }}" 
          action="{{ route('tasks.destroy', $task) }}" 
          method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endcan
<!-- Confirmation Modal -->
<div class="modal fade" id="deleteTaskModal" tabindex="-1" aria-labelledby="deleteTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTaskModalLabel">Confirmation de suppression</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer la tâche <strong id="task-title"></strong> ?</p>
                <p class="text-danger"><small>Cette action est irréversible.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirm-task-delete">Supprimer</button>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script>
    // Fonction de confirmation de suppression
    function confirmDelete(taskId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette tâche ? Cette action est irréversible.')) {
            document.getElementById('delete-form-' + taskId).submit();
        }
    }

    $(document).ready(function() {
        // Ajouter un style pour les badges de statut et priorité
        $('.badge').css({
            'font-size': '0.9em',
            'padding': '0.4em 0.8em'
        });
    });
</script>
<script>
    // Delete button click handler
    $(document).on('click', '.delete-task-btn', function() {
        const taskId = $(this).data('id');
        const taskTitle = $(this).data('title') || 'cette tâche';
        
        taskToDelete = taskId;
        taskRowToDelete = $(this).closest('tr');
        $('#task-title').text(taskTitle);
        $('#deleteTaskModal').modal('show');
    });
    
    // Confirm delete button handler
    $('#confirm-task-delete').on('click', function() {
        if (!taskToDelete) return;
        
        const deleteButton = $(this);
        const originalText = deleteButton.html();
        
        // Show loading state
        deleteButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Suppression...');
        
        $.ajax({
            url: '/tasks/' + taskToDelete,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteTaskModal').modal('hide');
                
                if (response.success) {
                    window.location.href = '{{ route("tasks.index") }}';
                    // Show success message
                    showAlert('success', response.message || 'Tâche supprimée avec succès!');
                } else {
                    showAlert('danger', response.message || 'Erreur lors de la suppression.');
                }
            },
            error: function(xhr) {
                $('#deleteTaskModal').modal('hide');
                
                let errorMessage = 'Une erreur est survenue lors de la suppression de la tâche.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                showAlert('danger', errorMessage);
            },
            complete: function() {
                // Reset button state
                deleteButton.prop('disabled', false).html(originalText);
                taskToDelete = null;
                taskRowToDelete = null;
            }
        });
    });

    // Function to show alert messages
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'fa-check' : 'fa-ban';
        const title = type === 'success' ? 'Succès!' : 'Erreur!';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas ${iconClass}"></i> ${title}</h5>
                ${message}
            </div>
        `;
        
        // Remove any existing alerts
        $('.alert-dismissible').remove();
        
        // Prepend the new alert
        $('.card').before(alertHtml);
        
        // Auto-remove alert after 5 seconds
        setTimeout(function() {
            $('.alert-dismissible').fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);
    }
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
</style>
@endsection