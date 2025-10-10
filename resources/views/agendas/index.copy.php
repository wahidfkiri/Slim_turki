@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Agenda</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item active">Agenda</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <!-- Filtres -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Filtres</h3>
                        </div>
                        <div class="card-body">
                            <!-- Filtre par date -->
                            <div class="form-group">
                                <label>Filtre par date</label>
                                
                                <!-- Année -->
                                <div class="form-group">
                                    <label for="filter_year">Année</label>
                                    <select class="form-control" id="filter_year">
                                        <option value="">Toutes les années</option>
                                        @php
                                            $currentYear = date('Y');
                                            $startYear = $currentYear - 5;
                                            $endYear = $currentYear + 5;
                                        @endphp
                                        @for($year = $startYear; $year <= $endYear; $year++)
                                            <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <!-- Mois -->
                                <div class="form-group">
                                    <label for="filter_month">Mois</label>
                                    <select class="form-control" id="filter_month">
                                        <option value="">Tous les mois</option>
                                        <option value="01">Janvier</option>
                                        <option value="02">Février</option>
                                        <option value="03">Mars</option>
                                        <option value="04">Avril</option>
                                        <option value="05">Mai</option>
                                        <option value="06">Juin</option>
                                        <option value="07">Juillet</option>
                                        <option value="08">Août</option>
                                        <option value="09">Septembre</option>
                                        <option value="10">Octobre</option>
                                        <option value="11">Novembre</option>
                                        <option value="12">Décembre</option>
                                    </select>
                                </div>

                                <!-- Jour -->
                                <div class="form-group">
                                    <label for="filter_day">Jour</label>
                                    <select class="form-control" id="filter_day">
                                        <option value="">Tous les jours</option>
                                        @for($day = 1; $day <= 31; $day++)
                                            <option value="{{ str_pad($day, 2, '0', STR_PAD_LEFT) }}">
                                                {{ $day }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <!-- Date spécifique -->
                                <div class="form-group">
                                    <label for="filter_specific_date">Date spécifique</label>
                                    <input type="date" class="form-control" id="filter_specific_date">
                                </div>
                            </div>

                            <!-- Filtre par catégorie -->
                            <div class="form-group">
                                <label>Catégories</label>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="filter_rdv" checked data-category="rdv">
                                    <label for="filter_rdv" class="custom-control-label">Rendez-vous</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="filter_audience" checked data-category="audience">
                                    <label for="filter_audience" class="custom-control-label">Audience</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="filter_delai" checked data-category="delai">
                                    <label for="filter_delai" class="custom-control-label">Délai</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="filter_tache" checked data-category="tache">
                                    <label for="filter_tache" class="custom-control-label">Tâche</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="filter_autre" checked data-category="autre">
                                    <label for="filter_autre" class="custom-control-label">Autre</label>
                                </div>
                            </div>

                            <!-- Filtre par utilisateur -->
                            <div class="form-group">
                                <label for="filter_utilisateur">Utilisateur</label>
                                <select class="form-control" id="filter_utilisateur">
                                    <option value="">Tous les utilisateurs</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtre par dossier -->
                            <div class="form-group">
                                <label for="filter_dossier">Dossier</label>
                                <select class="form-control" id="filter_dossier">
                                    <option value="">Tous les dossiers</option>
                                    @foreach($dossiers as $dossier)
                                        <option value="{{ $dossier->id }}">{{ $dossier->numero_dossier }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="form-group">
                                <button type="button" id="btn_today" class="btn btn-info btn-sm btn-block">
                                    Aujourd'hui
                                </button>
                                <button type="button" id="btn_reset_filters" class="btn btn-secondary btn-sm btn-block">
                                    Réinitialiser les filtres
                                </button>
                                <button type="button" id="btn_apply_date_filter" class="btn btn-primary btn-sm btn-block">
                                    Appliquer filtre date
                                </button>
                                @can('create_agendas')
                                    <button type="button" class="btn btn-success btn-sm btn-block" data-toggle="modal" data-target="#createEventModal">
                                        <i class="fas fa-plus"></i> Nouvel événement
                                    </button>
                                @endcan
                            </div>
                        </div>
                    </div>

                    <!-- Légende -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Légende</h3>
                        </div>
                        <div class="card-body">
                            <div class="legend-item">
                                <span class="legend-color" style="background-color: #3c8dbc"></span>
                                <span class="legend-text">Rendez-vous</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color" style="background-color: #f39c12"></span>
                                <span class="legend-text">Audience</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color" style="background-color: #00a65a"></span>
                                <span class="legend-text">Délai</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color" style="background-color: #dd4b39"></span>
                                <span class="legend-text">Tâche</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color" style="background-color: #605ca8"></span>
                                <span class="legend-text">Autre</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <!-- Calendar -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="card-title">Calendrier</h3>
                                </div>
                                <div class="col-md-6 text-right">
                                    <small class="text-muted" id="currentFilterInfo"></small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- Les modals restent identiques à votre code précédent -->
<!-- Modal pour les détails de l'événement -->
<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
    <!-- ... Votre code modal existant ... -->
</div>

<!-- Modal pour créer un événement -->
<div class="modal fade" id="createEventModal" tabindex="-1" role="dialog" aria-labelledby="createEventModalLabel" aria-hidden="true">
    <!-- ... Votre code modal existant ... -->
</div>

<!-- Modal pour modifier un événement -->
<div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="editEventModalLabel" aria-hidden="true">
    <!-- ... Votre code modal existant ... -->
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteEventModal" tabindex="-1" role="dialog" aria-labelledby="deleteEventModalLabel" aria-hidden="true">
    <!-- ... Votre code modal existant ... -->
</div>

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- FullCalendar -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/fr.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var currentEventId = null;
    var currentEventTitle = null;

    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    // Set today's date as default for new events
    $('#date_debut').val(new Date().toISOString().split('T')[0]);

    // Initialize Calendar
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'fr',
        timeZone: 'local',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        views: {
            dayGridMonth: { buttonText: 'Mois' },
            timeGridWeek: { buttonText: 'Semaine' },
            timeGridDay: { buttonText: 'Jour' },
            listWeek: { buttonText: 'Liste' }
        },
        buttonText: {
            today: 'Aujourd\'hui',
            month: 'Mois',
            week: 'Semaine',
            day: 'Jour',
            list: 'Liste'
        },
        navLinks: true,
        editable: false,
        selectable: true,
        nowIndicator: true,
        dayMaxEvents: true,
        events: {
            url: '{{ route("agendas.data") }}',
            method: 'GET',
            extraParams: function() {
                return {
                    categories: getSelectedCategories(),
                    utilisateur_id: $('#filter_utilisateur').val(),
                    dossier_id: $('#filter_dossier').val(),
                    year: $('#filter_year').val(),
                    month: $('#filter_month').val(),
                    day: $('#filter_day').val(),
                    specific_date: $('#filter_specific_date').val()
                };
            },
            failure: function() {
                showAlert('Erreur', 'Erreur lors du chargement des événements', 'error');
            }
        },
        eventClick: function(info) {
            currentEventId = info.event.id;
            currentEventTitle = info.event.title;
            showEventDetails(info.event);
        },
        dateClick: function(info) {
            @can('create_agendas')
                $('#date_debut').val(info.dateStr);
                $('#createEventModal').modal('show');
            @endcan
        },
        eventDidMount: function(info) {
            // Tooltip avec les détails de l'événement
            $(info.el).tooltip({
                title: info.event.title + '<br>' + 
                       (info.event.extendedProps.description || '') + '<br>' +
                       (info.event.extendedProps.dossier || ''),
                html: true,
                placement: 'top'
            });
        },
        datesSet: function(info) {
            updateFilterInfo(info);
        }
    });

    calendar.render();

    // Fonction pour mettre à jour les informations du filtre actuel
    function updateFilterInfo(info) {
        var view = info.view;
        var filterInfo = '';
        
        if ($('#filter_year').val() || $('#filter_month').val() || $('#filter_day').val() || $('#filter_specific_date').val()) {
            filterInfo = 'Filtre date actif';
        } else {
            var start = info.start;
            var end = info.end;
            
            if (view.type === 'dayGridMonth') {
                filterInfo = start.toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
            } else if (view.type === 'timeGridWeek') {
                filterInfo = 'Semaine du ' + start.toLocaleDateString('fr-FR') + ' au ' + 
                            new Date(end.getTime() - 1).toLocaleDateString('fr-FR');
            } else if (view.type === 'timeGridDay') {
                filterInfo = start.toLocaleDateString('fr-FR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            } else {
                filterInfo = 'Période sélectionnée';
            }
        }
        
        $('#currentFilterInfo').text(filterInfo);
    }

    // Fonction pour afficher les alertes
    function showAlert(title, message, type = 'info') {
        if (type === 'error') {
            alert('❌ ' + title + ': ' + message);
        } else if (type === 'success') {
            alert('✅ ' + message);
        } else {
            alert('ℹ️ ' + message);
        }
    }

    // Fonction pour obtenir les catégories sélectionnées
    function getSelectedCategories() {
        var categories = [];
        $('input[data-category]:checked').each(function() {
            categories.push($(this).data('category'));
        });
        return categories.join(',');
    }

    // Fonction pour afficher les détails de l'événement
    function showEventDetails(event) {
        var details = `
            <div class="event-details">
                <h4>${event.title}</h4>
                <p><strong>Catégorie:</strong> ${getCategoryLabel(event.extendedProps.categorie)}</p>
                ${event.extendedProps.description ? `<p><strong>Description:</strong> ${event.extendedProps.description}</p>` : ''}
                <p><strong>Date:</strong> ${formatEventDate(event)}</p>
                ${event.extendedProps.dossier ? `<p><strong>Dossier:</strong> ${event.extendedProps.dossier}</p>` : ''}
                ${event.extendedProps.intervenant ? `<p><strong>Intervenant:</strong> ${event.extendedProps.intervenant}</p>` : ''}
                ${event.extendedProps.utilisateur ? `<p><strong>Assigné à:</strong> ${event.extendedProps.utilisateur}</p>` : ''}
            </div>
        `;
        
        $('#eventModalBody').html(details);
        $('#eventModal').modal('show');
    }

    // Fonction pour formater la date de l'événement
    function formatEventDate(event) {
        if (event.allDay) {
            return event.start.toLocaleDateString('fr-FR');
        } else {
            var start = event.start.toLocaleString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            if (event.end) {
                var end = event.end.toLocaleString('fr-FR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                return `${start} - ${end}`;
            }
            return start;
        }
    }

    // Fonction pour obtenir le label de la catégorie
    function getCategoryLabel(categorie) {
        var labels = {
            'rdv': 'Rendez-vous',
            'audience': 'Audience',
            'delai': 'Délai',
            'tache': 'Tâche',
            'autre': 'Autre'
        };
        return labels[categorie] || categorie;
    }

    // Gestion de la case "Journée entière"
    $('#all_day').change(function() {
        if ($(this).is(':checked')) {
            $('#heure_debut, #heure_fin').val('').prop('disabled', true);
        } else {
            $('#heure_debut, #heure_fin').prop('disabled', false);
        }
    });

    // Appliquer le filtre par date
    $('#btn_apply_date_filter').click(function() {
        applyDateFilter();
    });

    function applyDateFilter() {
        var year = $('#filter_year').val();
        var month = $('#filter_month').val();
        var day = $('#filter_day').val();
        var specificDate = $('#filter_specific_date').val();

        // Si une date spécifique est sélectionnée, naviguer vers cette date
        if (specificDate) {
            calendar.gotoDate(specificDate);
        }
        // Si année et mois sont sélectionnés, naviguer vers ce mois
        else if (year && month) {
            calendar.gotoDate(year + '-' + month + '-01');
        }
        // Si seulement l'année est sélectionnée, naviguer vers cette année
        else if (year) {
            calendar.gotoDate(year + '-01-01');
        }

        calendar.refetchEvents();
    }

    // Auto-application du filtre quand les sélections changent
    $('#filter_year, #filter_month, #filter_day').change(function() {
        // Réinitialiser la date spécifique si on utilise les autres filtres
        if ($(this).attr('id') !== 'filter_specific_date') {
            $('#filter_specific_date').val('');
        }
        applyDateFilter();
    });

    $('#filter_specific_date').change(function() {
        // Réinitialiser les autres filtres si on utilise la date spécifique
        if ($(this).val()) {
            $('#filter_year').val('');
            $('#filter_month').val('');
            $('#filter_day').val('');
        }
        applyDateFilter();
    });

    // Création d'événement
    $('#createEventForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route("agendas.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#createEventModal').modal('hide');
                calendar.refetchEvents();
                $('#createEventForm')[0].reset();
                $('#date_debut').val(new Date().toISOString().split('T')[0]);
                $('#heure_debut, #heure_fin').prop('disabled', false);
                showAlert('Succès', 'Événement créé avec succès', 'success');
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                var errorMessage = 'Erreur de validation:\n';
                $.each(errors, function(key, value) {
                    errorMessage += '• ' + value[0] + '\n';
                });
                showAlert('Erreur', errorMessage, 'error');
            }
        });
    });

    // Modification d'événement
    $('#btnEditEvent').click(function() {
        $('#eventModal').modal('hide');
        loadEventForEdit(currentEventId);
    });

    function loadEventForEdit(eventId) {
        $.ajax({
            url: '/agendas/' + eventId + '/edit',
            type: 'GET',
            success: function(response) {
                // Pré-remplir le formulaire de modification
                $('#edit_event_id').val(response.id);
                $('#editEventForm input[name="titre"]').val(response.titre);
                $('#editEventForm select[name="categorie"]').val(response.categorie);
                $('#editEventForm input[name="date_debut"]').val(response.date_debut);
                $('#editEventForm input[name="date_fin"]').val(response.date_fin);
                $('#editEventForm input[name="heure_debut"]').val(response.heure_debut);
                $('#editEventForm input[name="heure_fin"]').val(response.heure_fin);
                $('#editEventForm input[name="all_day"]').prop('checked', response.all_day);
                $('#editEventForm textarea[name="description"]').val(response.description);
                $('#editEventForm select[name="utilisateur_id"]').val(response.utilisateur_id).trigger('change');
                $('#editEventForm select[name="dossier_id"]').val(response.dossier_id).trigger('change');
                $('#editEventForm select[name="intervenant_id"]').val(response.intervenant_id).trigger('change');
                $('#editEventForm input[name="couleur"]').val(response.couleur);

                if (response.all_day) {
                    $('#editEventForm input[name="heure_debut"], #editEventForm input[name="heure_fin"]').prop('disabled', true);
                }

                $('#editEventModal').modal('show');
            },
            error: function() {
                showAlert('Erreur', 'Erreur lors du chargement des données', 'error');
            }
        });
    }

    $('#editEventForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '/agendas/' + $('#edit_event_id').val(),
            type: 'POST',
            data: $(this).serialize() + '&_method=PUT',
            success: function(response) {
                $('#editEventModal').modal('hide');
                calendar.refetchEvents();
                showAlert('Succès', 'Événement mis à jour avec succès', 'success');
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                var errorMessage = 'Erreur de validation:\n';
                $.each(errors, function(key, value) {
                    errorMessage += '• ' + value[0] + '\n';
                });
                showAlert('Erreur', errorMessage, 'error');
            }
        });
    });

    // Suppression d'événement
    $('#btnDeleteEvent').click(function() {
        $('#eventModal').modal('hide');
        $('#deleteEventTitle').text(currentEventTitle);
        $('#deleteEventModal').modal('show');
    });

    $('#btnConfirmDelete').click(function() {
        $.ajax({
            url: '/agendas/' + currentEventId,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'DELETE'
            },
            success: function(response) {
                $('#deleteEventModal').modal('hide');
                calendar.refetchEvents();
                showAlert('Succès', 'Événement supprimé avec succès', 'success');
            },
            error: function() {
                showAlert('Erreur', 'Une erreur est survenue lors de la suppression', 'error');
            }
        });
    });

    // Événements des filtres
    $('input[data-category]').change(function() {
        calendar.refetchEvents();
    });

    $('#filter_utilisateur, #filter_dossier').change(function() {
        calendar.refetchEvents();
    });

    // Bouton aujourd'hui
    $('#btn_today').click(function() {
        // Réinitialiser les filtres de date
        $('#filter_year').val('');
        $('#filter_month').val('');
        $('#filter_day').val('');
        $('#filter_specific_date').val('');
        
        calendar.today();
        calendar.refetchEvents();
    });

    // Bouton réinitialiser
    $('#btn_reset_filters').click(function() {
        // Réinitialiser tous les filtres
        $('input[data-category]').prop('checked', true);
        $('#filter_utilisateur').val('').trigger('change');
        $('#filter_dossier').val('').trigger('change');
        $('#filter_year').val('');
        $('#filter_month').val('');
        $('#filter_day').val('');
        $('#filter_specific_date').val('');
        
        calendar.refetchEvents();
        calendar.today();
    });
});
</script>

<style>
.legend-item {
    display: flex;
    align-items: center;
    margin-bottom: 5px;
}
.legend-color {
    width: 20px;
    height: 20px;
    border-radius: 3px;
    margin-right: 10px;
    display: inline-block;
}
.legend-text {
    font-size: 14px;
}
#currentFilterInfo {
    font-style: italic;
    color: #6c757d;
}
</style>
@endsection