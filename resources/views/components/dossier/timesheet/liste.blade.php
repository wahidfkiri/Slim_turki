

                                        <!-- Onglet Feuille de temps -->
                                        <div class="tab-pane fade" id="timesheet" role="tabpanel" aria-labelledby="timesheet-tab">
                                            <div class="p-3">
                                                <div style="display: flow-root;">
                                                <h5 class="text-primary mb-3"><i class="fas fa-money-bill-wave"></i> Informations des feuilles de temps</h5>
                                                <a href="{{ route('dossiers.timesheets.create', ['dossier' => $dossier->id]) }}" class="btn btn-primary mb-3" style="float: right;">
                                                    <i class="fas fa-plus"></i> Ajouter une feuille de temps </a>
                                                </div>

                                                 @if($dossier->timeSheets && $dossier->timeSheets->count() > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Date</th>
                                                                    <th>Description</th>
                                                                    <th>Utilisateur</th>
                                                                    <th>Dossier</th>
                                                                    <th>Catégorie</th>
                                                                    <th>Type</th>
                                                                    <th>Quantité</th>
                                                                    <th>Prix</th>
                                                                    <th>Total</th>
                                                                    <th>Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($dossier->timeSheets as $time_sheet)
                                                                <tr>
                                                                    <td>
                                                                        {{ $time_sheet->date_timesheet->format('d/m/Y') }}
                                                                    </td>
                                                                    <td>{{ $time_sheet->description }}</td>
                                                                    <td>{{ $time_sheet->user->name }}</td>
                                                                    <td>{{ $time_sheet->dossier->numero_dossier }}</td>
                                                                    <td>{{ $time_sheet->categorieRelation->nom }}</td>
                                                                    <td>{{ $time_sheet->typeRelation->nom }}</td>
                                                                    <td>{{ $time_sheet->quantite }}</td>
                                                                    <td>{{ $time_sheet->prix }} DT</td>
                                                                    <td>{{ $time_sheet->total }} DT</td>
                                                                    <td>
                                                                        @if(auth()->user()->hasPermission('view_timesheets'))
                                                                        <a href="{{route('time-sheets.show', $time_sheet)}}" class="btn btn-sm btn-info" title="Voir">
                                                                            <i class="fas fa-eye"></i>
                                                                        </a>
                                                                        @endif
                                                                        @if(auth()->user()->hasPermission('edit_timesheets'))
                                                                        <a href="{{route('time-sheets.edit', $time_sheet)}}" class="btn btn-sm btn-warning" title="Modifier">
                                                                            <i class="fas fa-edit"></i>
                                                                        </a>
                                                                        @endif
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
                                                            Aucune feuille de temps n'a été ajoutée à ce dossier.
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>