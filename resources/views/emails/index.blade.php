{{-- resources/views/email/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Gestion des Emails</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
              <li class="breadcrumb-item active">Emails - {{ $currentFolder ?? 'INBOX' }}</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

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

            @if(isset($error))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> Erreur!</h5>
                {{ $error }}
            </div>
            @endif

            @if(isset($warning))
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-exclamation-triangle"></i> Attention!</h5>
                {{ $warning }}
            </div>
            @endif

            <div class="row">
              <!-- Sidebar - Dossiers -->
              <div class="col-md-3">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">
                      <i class="fas fa-folder mr-1"></i>
                      Tous les Dossiers
                      <span class="badge badge-info ml-1">{{ $totalFolders ?? 0 }}</span>
                    </h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body p-0" style="max-height: 500px; overflow-y: auto;">
                    <ul class="nav nav-pills flex-column">
                       
                      @forelse($folders as $folder)
                        <li class="nav-item">
                          <a href="{{ route('email.folder', $folder['name']) }}" 
                             class="nav-link {{ ($currentFolder ?? '') === $folder['name'] ? 'active' : '' }}
                                    {{ $folder['is_common'] ?? false ? 'font-weight-bold' : '' }}"
                             title="{{ $folder['full_name'] ?? $folder['name'] }}">
                            <i class="fas 
                                @if($folder['name'] == 'INBOX') fa-inbox
                                @elseif($folder['name'] == 'Sent' || $folder['name'] == 'Sent Items') fa-paper-plane
                                @elseif($folder['name'] == 'Drafts') fa-edit
                                @elseif($folder['name'] == 'Trash' || $folder['name'] == 'Deleted Items' || $folder['name'] == 'Bin') fa-trash
                                @elseif($folder['name'] == 'Spam' || $folder['name'] == 'Junk') fa-exclamation-triangle
                                @elseif($folder['name'] == 'Archive' || $folder['name'] == 'Archives') fa-archive
                                @elseif($folder['has_children'] ?? false) fa-folder-open
                                @else fa-folder
                                @endif
                                mr-2">
                            </i>
                            {{ $folder['name'] }}
                            @if($folder['has_children'] ?? false)
                              <small class="text-muted ml-1">
                                <i class="fas fa-folder-plus"></i>
                              </small>
                            @endif
                          </a>
                        </li>
                      @empty
                        <li class="nav-item">
                          <span class="nav-link text-muted">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            Aucun dossier trouvé
                          </span>
                        </li>
                      @endforelse
                    </ul>
                  </div>
                  <div class="card-footer">
                    <small class="text-muted">
                      <i class="fas fa-info-circle mr-1"></i>
                      {{ $totalFolders ?? 0 }} dossier(s) disponible(s)
                    </small>
                  </div>
                </div>

                <!-- Card Actions -->
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">
                      <i class="fas fa-edit mr-1"></i>
                      Nouvel Email
                    </h3>
                  </div>
                  <div class="card-body">
                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#composeModal">
                      <i class="fas fa-pen mr-1"></i>Composer
                    </button>
                    <a href="{{ route('email.reconnect') }}" class="btn btn-outline-secondary btn-block mt-2">
                      <i class="fas fa-sync-alt mr-1"></i>Rafraîchir
                    </a>
                  </div>
                </div>

                <!-- Card Info -->
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">
                      <i class="fas fa-info-circle mr-1"></i>
                      Informations
                    </h3>
                  </div>
                  <div class="card-body">
                    <p class="mb-1"><strong>Compte:</strong></p>
                    <p class="text-sm text-muted">{{ $account ?? 'wahid.fkiri@peakmind-solutions.com' }}</p>
                    
                    <p class="mb-1"><strong>Dossier actuel:</strong></p>
                    <p class="text-sm text-muted">{{ $currentFolder ?? 'INBOX' }}</p>
                    
                    <p class="mb-1"><strong>Total dossiers:</strong></p>
                    <p class="text-sm text-muted">{{ $totalFolders ?? 0 }}</p>
                    
                    <p class="mb-1"><strong>Emails affichés:</strong></p>
                    <p class="text-sm text-muted">{{ count($emails ?? []) }}</p>
                  </div>
                </div>
              </div>

              <!-- Main Content -->
              <div class="col-md-9">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">
                      @php
                        $folderIcon = 'fa-folder';
                        $folderNames = [
                          'INBOX' => 'fa-inbox',
                          'Sent' => 'fa-paper-plane', 
                          'Sent Items' => 'fa-paper-plane',
                          'Drafts' => 'fa-edit',
                          'Trash' => 'fa-trash',
                          'Deleted Items' => 'fa-trash',
                          'Bin' => 'fa-trash',
                          'Spam' => 'fa-exclamation-triangle',
                          'Junk' => 'fa-exclamation-triangle',
                          'Archive' => 'fa-archive',
                          'Archives' => 'fa-archive'
                        ];
                        
                        foreach($folderNames as $name => $icon) {
                          if (strtoupper($currentFolder) == strtoupper($name)) {
                            $folderIcon = $icon;
                            break;
                          }
                        }
                      @endphp
                      <i class="fas {{ $folderIcon }} mr-1"></i>
                      {{ $currentFolder ?? 'INBOX' }}
                      @if(isset($emails) && count($emails) > 0)
                        <span class="badge badge-primary ml-2">{{ count($emails) }} email(s)</span>
                      @endif
                    </h3>
                    <div class="card-tools">
                      <div class="input-group input-group-sm" style="width: 250px;">
                        <input type="text" name="table_search" class="form-control" placeholder="Rechercher..." id="emailSearch">
                        <div class="input-group-append">
                          <button type="button" class="btn btn-primary" id="searchButton">
                            <i class="fas fa-search"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card-body p-0">
                    @if(isset($emails) && count($emails) > 0)
                      <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped" id="emailsTable">
                          <thead>
                            <tr>
                              <th style="width: 40px">
                                <div class="icheck-primary">
                                  <input type="checkbox" id="checkAll">
                                  <label for="checkAll"></label>
                                </div>
                              </th>
                              <th style="width: 40px"></th>
                              <th>Expéditeur</th>
                              <th>Sujet & Aperçu</th>
                              <th style="width: 40px"></th>
                              <th style="width: 150px">Date</th>
                              <th style="width: 100px">Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($emails as $email)
                              @if(isset($email['error']))
                                <tr class="bg-warning">
                                  <td colspan="7" class="text-center text-dark">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Erreur: {{ $email['error'] }}
                                  </td>
                                </tr>
                              @else
                                <tr class="{{ $email['seen'] ? '' : 'font-weight-bold bg-light' }}">
                                  <td>
                                    <div class="icheck-primary">
                                      <input type="checkbox" value="{{ $email['uid'] }}" id="check{{ $email['uid'] }}">
                                      <label for="check{{ $email['uid'] }}"></label>
                                    </div>
                                  </td>
                                  <td>
                                    <a href="#" class="text-warning mailbox-star">
                                      <i class="fas fa-star{{ $email['flagged'] ?? false ? '' : '-o' }}"></i>
                                    </a>
                                  </td>
                                  <td class="mailbox-name">
                                    <small>
                                      {{ $email['from_name'] ?: $email['from'] }}
                                    </small>
                                  </td>
                                  <td class="mailbox-subject">
                                    <a href="{{ route('email.show', ['folder' => $currentFolder, 'uid' => $email['uid']]) }}" 
                                       class="text-dark text-decoration-none">
                                      <div class="font-weight-bold">
                                        {{ $email['subject'] }}
                                      </div>
                                      @if(isset($email['preview']) && !empty(trim($email['preview'])))
                                        <div class="text-muted text-sm mt-1">
                                          {{ Str::limit($email['preview'], 80) }}
                                        </div>
                                      @endif
                                    </a>
                                  </td>
                                  <td class="mailbox-attachment text-center">
                                    @if(isset($email['attachments_count']) && $email['attachments_count'] > 0)
                                      <i class="fas fa-paperclip text-muted" title="{{ $email['attachments_count'] }} pièce(s) jointe(s)"></i>
                                    @endif
                                  </td>
                                  <td class="mailbox-date text-sm">
                                    @if(isset($email['date']))
                                      <small>{{ \Carbon\Carbon::parse($email['date'])->diffForHumans() }}</small>
                                    @endif
                                  </td>
                                  <td>
                                    <div class="btn-group btn-group-sm">
                                      <a href="{{ route('email.show', ['folder' => $currentFolder, 'uid' => $email['uid']]) }}" 
                                         class="btn btn-info" title="Voir l'email">
                                        <i class="fas fa-eye"></i>
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
                        @php
                          $emptyIcon = 'fa-folder-open';
                          $emptyText = 'Aucun email trouvé dans ce dossier';
                          
                          if ($currentFolder == 'INBOX') {
                            $emptyIcon = 'fa-inbox';
                            $emptyText = 'Votre boîte de réception est vide';
                          } elseif (in_array($currentFolder, ['Sent', 'Sent Items'])) {
                            $emptyIcon = 'fa-paper-plane';
                            $emptyText = 'Aucun email envoyé';
                          } elseif ($currentFolder == 'Drafts') {
                            $emptyIcon = 'fa-edit';
                            $emptyText = 'Aucun brouillon';
                          } elseif (in_array($currentFolder, ['Trash', 'Deleted Items', 'Bin'])) {
                            $emptyIcon = 'fa-trash';
                            $emptyText = 'Corbeille vide';
                          }
                        @endphp
                        <i class="fas {{ $emptyIcon }} fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">{{ $emptyText }}</h4>
                        <p class="text-muted">Le dossier "{{ $currentFolder ?? 'INBOX' }}" ne contient aucun email.</p>
                        <a href="{{ route('email.index') }}" class="btn btn-primary mt-2">
                          <i class="fas fa-inbox mr-1"></i> Retour à l'accueil
                        </a>
                      </div>
                    @endif
                  </div>

                  @if(isset($emails) && count($emails) > 0)
                    <div class="card-footer clearfix">
                      <div class="float-left">
                        <button type="button" class="btn btn-default btn-sm" onclick="location.reload()">
                          <i class="fas fa-sync-alt mr-1"></i> Actualiser
                        </button>
                      </div>
                      <div class="float-right">
                        <small class="text-muted">
                          Affichage de {{ count($emails) }} email(s)
                        </small>
                      </div>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Compose Modal -->
  <div class="modal fade" id="composeModal" tabindex="-1" role="dialog" aria-labelledby="composeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="composeModalLabel">Nouveau Message</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form action="{{ route('email.send') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="form-group">
              <input type="email" class="form-control" name="to" placeholder="À:" required>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="subject" placeholder="Sujet:" required>
            </div>
            <div class="form-group">
              <textarea class="form-control" name="content" style="height: 300px" required placeholder="Votre message..."></textarea>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-paper-plane mr-1"></i>Envoyer
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
<style>
  .mailbox-name {
    width: 180px;
  }
  .mailbox-subject {
    min-width: 300px;
  }
  .mailbox-date {
    width: 120px;
  }
  .table-responsive {
    min-height: 400px;
  }
  .bg-light {
    background-color: #f8f9fa !important;
  }
  .card-body {
    scrollbar-width: thin;
  }
</style>
<!-- jQuery -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

<script>
  $(function () {
    // Check/Uncheck all
    $('#checkAll').click(function () {
      $('input[type="checkbox"]').prop('checked', this.checked);
    });

    // Star functionality
    $('.mailbox-star').click(function (e) {
      e.preventDefault();
      var $icon = $(this).find('i');
      $icon.toggleClass('fa-star fa-star-o');
    });

    // Search functionality
    $('#emailSearch').on('keyup', function () {
      var value = $(this).val().toLowerCase();
      $('#emailsTable tbody tr').filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });

    $('#searchButton').click(function() {
      $('#emailSearch').trigger('keyup');
    });
  });
</script>
@endsection