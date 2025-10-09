@extends('layouts.app')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Gestion des Dossiers</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
              <li class="breadcrumb-item active">Dossiers</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            
            <!-- Alert Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Succès!</h5>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> Erreur!</h5>
                {{ session('error') }}
            </div>
            @endif

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Liste des Dossiers</h3>
                @if(auth()->user()->hasPermission('create_dossiers'))
                <div class="card-tools">
                  <a href="{{ route('dossiers.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nouveau Dossier
                  </a>
                </div>
                @endif
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <!-- Search and Filter Form -->
                <div class="row mb-3">
                  <div class="col-md-6">
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Rechercher par numéro ou nom..." id="searchInput">
                      <div class="input-group-append">
                        <button type="button" class="btn btn-secondary" id="resetSearch">
                          <i class="fas fa-times"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-md-6">
                        <select class="form-control" id="domaineFilter">
                          <option value="">Tous les domaines</option>
                          @foreach($domaines as $domaine)
                            <option value="{{ $domaine->nom }}">{{ $domaine->nom }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-6">
                        <select class="form-control" id="statutFilter">
                          <option value="">Tous les statuts</option>
                          <option value="conseil">Conseil</option>
                          <option value="contentieux">Contentieux</option>
                          <option value="archive">Archivé</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>

                <table id="dossiersTable" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Numéro</th>
                    <th>Nom du Dossier</th>
                    <th>Objet du Dossier</th>
                    <!-- <th>Domaine</th>
                    <th>Sous-domaine</th> -->
                    <th>Date Entrée</th>
                    <th>Type</th>
                    <th>Statut</th>
                    <th>Archivé</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($dossiers as $dossier)
                    <tr id="dossier-row-{{ $dossier->id }}">
                      <td>{{ $dossier->numero_dossier }}</td>
                      <td>{{ $dossier->nom_dossier }}</td>
                      <td>{{ $dossier->objet }}</td>
                      <!-- <td>{{ $dossier->domaine->nom ?? 'N/A' }}</td>
                      <td>{{ $dossier->sousDomaine->nom ?? 'N/A' }}</td> -->
                      <td>{{ $dossier->date_entree->format('d/m/Y') }}</td>
                      <td>
                        @if($dossier->conseil && $dossier->contentieux)
                          <span class="badge badge-warning">Mixte</span>
                        @elseif($dossier->conseil)
                          <span class="badge badge-info">Conseil</span>
                        @elseif($dossier->contentieux)
                          <span class="badge badge-primary">Contentieux</span>
                        @else
                          <span class="badge badge-secondary">Non défini</span>
                        @endif
                      </td>
                      <td>
                        @if($dossier->numero_role)
                          <span class="badge badge-success">En cours</span>
                        @else
                          <span class="badge badge-secondary">En préparation</span>
                        @endif
                      </td>
                      <td>{{ $dossier->archive ? 'Oui' : 'Non' }}</td>
                      <td>
                        <div class="btn-group btn-group-sm">
                         @if(auth()->user()->hasPermission('view_dossiers'))
                          <a href="{{ route('dossiers.show', $dossier->id) }}" 
                             class="btn btn-info" title="Voir">
                            <i class="fas fa-eye"></i>
                          </a>
                          @endif
                          @if(auth()->user()->hasPermission('edit_dossiers'))
                          <a href="{{ route('dossiers.edit', $dossier->id) }}" 
                             class="btn btn-warning" title="Modifier">
                            <i class="fas fa-edit"></i>
                          </a>
                          @endif
                          @if(auth()->user()->hasPermission('delete_dossiers'))
                          <button type="button" class="btn btn-danger delete-dossier-btn" 
                                  title="Supprimer" 
                                  data-id="{{ $dossier->id }}"
                                  data-numero="{{ $dossier->numero_dossier }}"
                                  data-nom="{{ $dossier->nom_dossier }}">
                            <i class="fas fa-trash"></i>
                          </button>
                          @endif
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  <!-- Confirmation Modal -->
  <div class="modal fade" id="deleteDossierModal" tabindex="-1" aria-labelledby="deleteDossierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteDossierModalLabel">Confirmation de suppression</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Êtes-vous sûr de vouloir supprimer le dossier <strong id="dossier-numero"></strong> - <strong id="dossier-nom"></strong> ?</p>
          <p class="text-danger"><small>Cette action est irréversible. Toutes les données associées à ce dossier seront perdues.</small></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
          <button type="button" class="btn btn-danger" id="confirm-dossier-delete">Supprimer</button>
        </div>
      </div>
    </div>
  </div>
<style>
.badge {
    font-size: 0.8em;
}
.btn-group .btn {
    margin-right: 2px;
}
.dataTables_wrapper {
    padding: 0;
}
/* Style pour la pagination */
.dataTables_paginate .paginate_button {
    margin: 0 2px;
    padding: 6px 12px;
}
/* Réduire l'espacement des cellules */
.table td, .table th {
    padding: 0.5rem;
}
.delete-dossier-btn {
    transition: all 0.3s ease;
}
.delete-dossier-btn:hover {
    transform: scale(1.05);
}
</style>
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script>
$(document).ready(function() {
    let dossierToDelete = null;
    
    // Delete button click handler
    $('.delete-dossier-btn').on('click', function() {
        const dossierId = $(this).data('id');
        const dossierNumero = $(this).data('numero');
        const dossierNom = $(this).data('nom');
        
        dossierToDelete = dossierId;
        $('#dossier-numero').text(dossierNumero);
        $('#dossier-nom').text(dossierNom);
        $('#deleteDossierModal').modal('show');
    });
    
    // Confirm delete button handler
    $('#confirm-dossier-delete').on('click', function() {
        if (!dossierToDelete) return;
        
        const deleteButton = $(this);
        const originalText = deleteButton.html();
        
        // Show loading state
        deleteButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Suppression...');
        
        $.ajax({
            url: '{{ route("dossiers.destroy", "") }}/' + dossierToDelete,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteDossierModal').modal('hide');
                
                // Remove the row from the table
                $('#dossier-row-' + dossierToDelete).fadeOut(300, function() {
                    $(this).remove();
                    // Show message if no more rows
                    if ($('#dossiersTable tbody tr').length === 0) {
                        $('#dossiersTable tbody').html(
                            '<tr><td colspan="10" class="text-center">Aucun dossier trouvé</td></tr>'
                        );
                    }
                });
                
                // Show success message
                showAlert('success', 'Dossier supprimé avec succès!');
            },
            error: function(xhr) {
                $('#deleteDossierModal').modal('hide');
                
                let errorMessage = 'Une erreur est survenue lors de la suppression du dossier.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                showAlert('danger', errorMessage);
            },
            complete: function() {
                // Reset button state
                deleteButton.prop('disabled', false).html(originalText);
                dossierToDelete = null;
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
    
    // Close modal when clicking the X button
    $('#deleteDossierModal .close, #deleteDossierModal [data-dismiss="modal"]').on('click', function() {
        dossierToDelete = null;
    });
    
    // Handle escape key to close modal
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#deleteDossierModal').is(':visible')) {
            dossierToDelete = null;
        }
    });
});
</script>
@endsection