
                                        <!-- Onglet Facturation -->
                                        <div class="tab-pane fade" id="facturation" role="tabpanel" aria-labelledby="facturation-tab">
                                            <div class="p-3">
                                                <h5 class="text-primary mb-3"><i class="fas fa-money-bill-wave"></i> Informations de facturation</h5>
                                                
                                                 @if($dossier->factures && $dossier->factures->count() > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Numéro</th>
                                                                    <th>Date émission</th>
                                                                    <th>Montant HT</th>
                                                                    <th>Montant TVA</th>
                                                                    <th>Montant</th>
                                                                    <th>Statut</th>
                                                                    <th>Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($dossier->factures as $facture)
                                                                <tr>
                                                                    <td>
                                                                        {{ $facture->numero }}
                                                                    </td>
                                                                    <td>{{ $facture->date_emission->format('d/m/Y') }}</td>
                                                                    <td>{{ number_format($facture->montant_ht, 2) }} DT</td>
                                                                    <td>{{ number_format($facture->montant_tva, 2) }} DT</td>
                                                                    <td>{{ number_format($facture->montant, 2) }} DT</td>
                                                                    <td>{{ $facture->statut }}</td>
                                                                    <td>
                                                                        <a href="{{route('factures.show', $facture)}}" class="btn btn-sm btn-info" title="Voir">
                                                                            <i class="fas fa-eye"></i>
                                                                        </a>
                                                                        <a href="{{ route('factures.pdf', $facture) }}" 
                                                                           download class="btn btn-sm btn-success" title="Télécharger">
                                                                            <i class="fas fa-download"></i>
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