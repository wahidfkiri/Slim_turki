

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
                                                                            <option value="{{ $user->id }}" {{ auth()->user()->id == $user->id ? 'selected' : '' }}>
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
                                                                               
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <h5 class="text-primary mb-0"><i class="fas fa-users"></i> utilisateurs Liés</h5>
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#linkUtilisateurModal">
                                                            <i class="fas fa-link"></i> Lier un utilisateur
                                                        </button>
                                                    </div>


                                                    <!-- Tableau des intervenants liés -->
                                                    <div class="card">
                                                        <div class="card-header bg-light">
                                                            <h6 class="mb-0"><i class="fas fa-table"></i> Liste des utilisateurs liés</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped" id="linkedUtilisateursTable">
                                                                    <thead class="thead-dark">
                                                                        <tr>
                                                                            <th width="30%">Utilisateur Lié</th>
                                                                            <th width="30%">Role</th>
                                                                            <th width="10%">Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="linked-utilisateur-container">
                                                                        @if(old('linked_utilisateurs'))
                                                                            @foreach(old('linked_utilisateurs') as $index => $linkedUtilisateur)
                                                                            <tr class="linked-utilisateur-item">
                                                                                <td>
                                                                                    <strong>{{ $linkedUtilisateur['name'] ?? 'Utilisateur' }}</strong>
                                                                                    <input type="hidden" name="linked_utilisateurs[{{ $index }}][user_id]" 
                                                                                           value="{{ $linkedUtilisateur['user_id'] }}">
                                                                                    <input type="hidden" name="linked_utilisateurs[{{ $index }}][name]" 
                                                                                           value="{{ $linkedUtilisateur['name'] }}">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" class="form-control" 
                                                                                           name="linked_utilisateurs[{{ $index }}][role]" 
                                                                                           value="{{ $linkedUtilisateur['role'] ?? '' }}"
                                                                                           placeholder=""
                                                                                           required>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <button type="button" class="btn btn-danger btn-sm remove-linked-utilisateur">
                                                                                        <i class="fas fa-trash"></i>
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                            @endforeach
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            <!-- Message quand aucun intervenant n'est lié -->
                                                            <div id="no-linked-utilisateurs" class="text-center py-4" 
                                                                 style="{{ old('linked_utilisateurs') ? 'display: none;' : '' }}">
                                                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                                <p class="text-muted">Aucun utilisateur lié pour le moment</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            
                                            <!-- Modal pour lier un intervenant -->
<div class="modal fade" id="linkUtilisateurModal" tabindex="-1" role="dialog" aria-labelledby="linkUtilisateurModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="linkUtilisateurModalLabel">
                    <i class="fas fa-users"></i> Sélectionner un utilisateur à lier
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Filtre de recherche -->
                <div class="form-group d-none">
                    <label for="utilisateurFilter">Filtrer les utilisateurs</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="utilisateurFilter" 
                               placeholder="Tapez pour filtrer par nom...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="clearFilter">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <small class="form-text text-muted">
                        Tapez pour filtrer la liste des utilisateurs. {{ $users->count() }} utilisateur(s) disponible(s).
                    </small>
                </div>

                <!-- Liste des intervenants disponibles -->
                <div class="form-group w-100" style="display:grid;">
                    <label for="utilisateurList">Choisir un utilisateur</label>
                    <select class="form-control search_test1" id="utilisateurList" >
                        <option value="">-- Sélectionnez un utilisateur --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" 
                                    data-name="{{ $user->name }}"
                                    data-email="{{ $user->email ?? 'N/A' }}"
                                    class="utilisateur-option">
                                {{ $user->name }} 
                            </option>
                        @endforeach
                    </select>
                    <div id="noResults" class="alert alert-warning mt-2" style="display: none;">
                        <i class="fas fa-search"></i> Aucun dossier ne correspond à votre recherche.
                    </div>
                </div>

                <!-- Aperçu du dossier sélectionné -->
                <div id="utilisateurPreview" class="card mt-3" style="display: none;">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-eye"></i> Aperçu d'utilisateur</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <table class="table table-sm table-borderless d-none">
                                    <tr>
                                        <td width="30%"><strong>Nom :</strong></td>
                                        <td id="previewName"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email :</strong></td>
                                        <td id="previewEmail"></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4 text-right">
                                <button type="button" class="btn btn-success" id="confirmLinkUtilisateur">
                                    <i class="fas fa-link"></i> Lier cet utilisateur
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message si aucun intervenant disponible -->
                @if($users->isEmpty())
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Aucun utilisateur disponible pour le moment.
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{asset('assets/custom/dossier-form.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.0.2/jquery.sumoselect.min.js"></script>
<script>
    $('.search_test1').SumoSelect({search: true, searchText: 'Sélectionner un utilisateur...'});
       
// Gestion des intervenants liés
let linkedUtilisateursCount = {{ old('linked_utilisateurs') ? count(old('linked_utilisateurs')) : 0 }};

// Filtrage des intervenants
$('#utilisateurFilter').on('input', function() {
    const filterText = $(this).val().toLowerCase();
    const options = $('.utilisateur-option');
    let visibleCount = 0;
    
    options.each(function() {
        const optionText = $(this).text().toLowerCase();
        if (optionText.includes(filterText)) {
            $(this).show();
            visibleCount++;
        } else {
            $(this).hide();
        }
    });
    
    // Afficher/masquer le message "aucun résultat"
    if (visibleCount === 0 && filterText !== '') {
        $('#noResults').show();
    } else {
        $('#noResults').hide();
    }
    
    // Réinitialiser la sélection si l'option sélectionnée est masquée
    const selectedOption = $('#utilisateurList option:selected');
    if (selectedOption.length > 0 && selectedOption.is(':hidden')) {
        $('#utilisateurList').val('');
        $('#utilisateurPreview').hide();
    }
});

// Effacer le filtre
$('#clearFilter').click(function() {
    $('#utilisateurFilter').val('');
    $('.utilisateur-option').show();
    $('#noResults').hide();
});

// Sélection d'un intervenant dans la liste
$('#utilisateurList').change(function() {
    const selectedOption = $(this).find('option:selected');
    const intervenantId = selectedOption.val();
    
    if (!intervenantId) {
        $('#utilisateurPreview').hide();
        return;
    }

    // Afficher l'aperçu
    $('#previewName').text(selectedOption.data('name'));
    $('#previewEmail').text(selectedOption.data('email'));
    
    $('#utilisateurPreview').show();
});

// Confirmation du lien
$('#confirmLinkUtilisateur').click(function() {
    const selectedOption = $('#utilisateurList option:selected');
    const intervenantId = selectedOption.val();
    const intervenantName = selectedOption.data('name');

    if (!intervenantId) {
        alert('Veuillez sélectionner un utilisateur.');
        return;
    }

    // Vérifier si l'intervenant n'est pas déjà lié
    const existingLink = $(`input[value="${intervenantId}"]`).closest('.linked-utilisateur-item');
    if (existingLink.length > 0) {
        alert('Cet utilisateur est déjà lié.');
        return;
    }

    addlinkedUtilisateur(intervenantId, intervenantName);
    
    // Reset la modal
    $('#utilisateurList').val('');
    $('#utilisateurFilter').val('');
    $('.utilisateur-option').show();
    $('#noResults').hide();
    $('#utilisateurPreview').hide();
    $('#linkUtilisateurModal').modal('hide');
});

function addlinkedUtilisateur(userId, UserName) {
    const newIndex = linkedUtilisateursCount++;
    
    const linkedItem = `
        <tr class="linked-utilisateur-item">
            <td>
                <strong>${UserName}</strong>
                <input type="hidden" name="linked_utilisateurs[${newIndex}][user_id]" value="${userId}">
                <input type="hidden" name="linked_utilisateurs[${newIndex}][name]" value="${UserName}">
            </td>
            <td>
                <input type="text" class="form-control" 
                       name="linked_utilisateurs[${newIndex}][role]" 
                       placeholder=""
                       required>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-linked-utilisateur">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;

    $('#linked-utilisateur-container').append(linkedItem);
    $('#no-linked-utilisateurs').hide();

    // Ajouter l'événement de suppression
    $('.remove-linked-utilisateur').off('click').on('click', function() {
        $(this).closest('.linked-utilisateur-item').remove();
        linkedUtilisateursCount--;
        
        // Réindexer les éléments restants
        reindexlinkedUtilisateurs();
        
        // Afficher le message si plus d'intervenants liés
        if ($('#linked-utilisateur-container').children().length === 0) {
            $('#no-linked-utilisateurs').show();
        }
    });
}

function reindexlinkedUtilisateurs() {
    $('#linked-utilisateur-container .linked-utilisateur-item').each(function(index) {
        $(this).find('input').each(function() {
            const name = $(this).attr('name').replace(/\[\d+\]/, `[${index}]`);
            $(this).attr('name', name);
        });
    });
}

// Initialiser les boutons de suppression pour les intervenants existants
$(document).ready(function() {
    $('.remove-linked-utilisateur').click(function() {
        $(this).closest('.linked-utilisateur-item').remove();
        linkedUtilisateursCount--;
        
        reindexlinkedUtilisateurs();
        
        if ($('#linked-utilisateur-container').children().length === 0) {
            $('#no-linked-utilisateurs').show();
        }
    });

    // Reset de la modal quand elle se ferme
    $('#linkUtilisateurModal').on('hidden.bs.modal', function() {
        $('#utilisateurList').val('');
        $('#utilisateurFilter').val('');
        $('.utilisateur-option').show();
        $('#noResults').hide();
        $('#utilisateurPreview').hide();
    });

    // Focus sur le champ de filtre quand la modal s'ouvre
    $('#linkUtilisateurModal').on('shown.bs.modal', function() {
        $('#utilisateurFilter').focus();
    });
});
    </script>



