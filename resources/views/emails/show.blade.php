{{-- resources/views/email/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Lecture d'Email</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
              <li class="breadcrumb-item"><a href="{{ route('email.index') }}">Emails</a></li>
              <li class="breadcrumb-item"><a href="{{ route('email.folder', $currentFolder) }}">{{ $currentFolder }}</a></li>
              <li class="breadcrumb-item active">Lecture</li>
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

            <div class="row">
              <!-- Sidebar - Dossiers -->
              <div class="col-md-3">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">
                      <i class="fas fa-folder mr-1"></i>
                      Dossiers Email
                    </h3>
                  </div>
                  <div class="card-body p-0">
                    <ul class="nav nav-pills flex-column">
                      @foreach($folders as $folder)
                        <li class="nav-item">
                          <a href="{{ route('email.folder', $folder['name']) }}" 
                             class="nav-link {{ $currentFolder === $folder['name'] ? 'active' : '' }}">
                            <i class="fas fa-folder-open mr-2"></i>
                            {{ $folder['name'] }}
                          </a>
                        </li>
                      @endforeach
                    </ul>
                  </div>
                </div>

                <!-- Card Actions Rapides -->
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">
                      <i class="fas fa-bolt mr-1"></i>
                      Actions
                    </h3>
                  </div>
                  <div class="card-body">
                    <div class="d-grid gap-2">
                      <a href="{{ route('email.folder', $currentFolder) }}" class="btn btn-default btn-block">
                        <i class="fas fa-arrow-left mr-1"></i> Retour
                      </a>
                      <button type="button" class="btn btn-info btn-block">
                        <i class="fas fa-reply mr-1"></i> Répondre
                      </button>
                      <button type="button" class="btn btn-info btn-block">
                        <i class="fas fa-share mr-1"></i> Transférer
                      </button>
                      <button type="button" class="btn btn-danger btn-block">
                        <i class="fas fa-trash mr-1"></i> Supprimer
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Main Content - Email Detail -->
              <div class="col-md-9">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">
                      <i class="fas fa-envelope-open mr-1"></i>
                      {{ $email['subject'] ?? 'Sans objet' }}
                    </h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-toggle="tooltip" title="Imprimer">
                        <i class="fas fa-print"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-toggle="tooltip" title="Télécharger">
                        <i class="fas fa-download"></i>
                      </button>
                    </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <!-- Email Header -->
                    <div class="row mb-4">
                      <div class="col-md-12">
                        <div class="callout callout-info">
                          <div class="row">
                            <div class="col-md-6">
                              <p class="mb-1"><strong>De:</strong> {{ $email['from_name'] ? $email['from_name'] . ' <' . $email['from'] . '>' : $email['from'] }}</p>
                              <p class="mb-1"><strong>À:</strong> Moi</p>
                              @if(isset($email['cc']) && count($email['cc']) > 0)
                                <p class="mb-1"><strong>Cc:</strong> 
                                  @foreach($email['cc'] as $cc)
                                    {{ $cc['name'] ? $cc['name'] . ' <' . $cc['email'] . '>' : $cc['email'] }}{{ !$loop->last ? ', ' : '' }}
                                  @endforeach
                                </p>
                              @endif
                            </div>
                            <div class="col-md-6 text-md-right">
                              <p class="mb-1"><strong>Date:</strong> 
                                @if(isset($email['date']))
                                  {{ \Carbon\Carbon::parse($email['date'])->format('d/m/Y à H:i') }}
                                @endif
                              </p>
                              <p class="mb-0">
                                <span class="badge badge-{{ $email['seen'] ? 'success' : 'warning' }}">
                                  {{ $email['seen'] ? 'Lu' : 'Non lu' }}
                                </span>
                                @if(isset($email['attachments_count']) && $email['attachments_count'] > 0)
                                  <span class="badge badge-info">
                                    <i class="fas fa-paperclip mr-1"></i>{{ $email['attachments_count'] }} pièce(s) jointe(s)
                                  </span>
                                @endif
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Email Content -->
                    <div class="row">
  <div class="col-md-12">
    <div class="email-content">
      @if(isset($email['body']['html']) && !empty(trim($email['body']['html'])))
        <div class="border rounded p-4 bg-white">
          <div class="email-html-content">
            {!! $email['body']['html'] !!}
          </div>
        </div>
      @elseif(isset($email['body']['text']) && !empty(trim($email['body']['text'])))
        <div class="border rounded p-4 bg-white">
          <div class="email-text-content">
            <pre style="
              white-space: pre-wrap; 
              font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
              background: transparent; 
              border: none; 
              padding: 0; 
              margin: 0;
              font-size: 14px;
              line-height: 1.6;
              color: #333;
            ">{{ $email['body']['text'] }}</pre>
          </div>
        </div>
      @else
        <div class="text-center py-5">
          <i class="fas fa-exclamation-circle fa-3x text-muted mb-3"></i>
          <h4 class="text-muted">Aucun contenu disponible</h4>
          <p class="text-muted">Cet email ne contient aucun texte.</p>
        </div>
      @endif
    </div>
  </div>
</div>

                    <!-- Pièces jointes -->
                    @if(isset($email['attachments']) && count($email['attachments']) > 0)
                      <div class="row mt-4">
                        <div class="col-md-12">
                          <div class="card">
                            <div class="card-header">
                              <h5 class="card-title mb-0">
                                <i class="fas fa-paperclip mr-1"></i>
                                Pièces jointes ({{ count($email['attachments']) }})
                              </h5>
                            </div>
                            <div class="card-body">
                              <div class="row">
                                @foreach($email['attachments'] as $attachment)
                                  <div class="col-md-4 mb-3">
                                    <div class="attachment-item border rounded p-2">
                                      <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                          <i class="fas fa-file fa-2x text-muted"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                          <p class="mb-1 text-sm font-weight-bold">{{ $attachment['name'] }}</p>
                                          <p class="mb-0 text-xs text-muted">
                                            {{ number_format($attachment['size'] / 1024, 2) }} KB
                                          </p>
                                        </div>
                                        <div class="ml-2">
                                          <button type="button" class="btn btn-sm btn-primary" title="Télécharger">
                                            <i class="fas fa-download"></i>
                                          </button>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                @endforeach
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    @endif
                  </div>
                  <!-- /.card-body -->
                  
                  <div class="card-footer">
                    <div class="row">
                      <div class="col-md-6">
                        <a href="{{ route('email.folder', $currentFolder) }}" class="btn btn-default">
                          <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
                        </a>
                      </div>
                      <div class="col-md-6 text-right">
                        <div class="btn-group">
                          <button type="button" class="btn btn-info">
                            <i class="fas fa-reply mr-1"></i> Répondre
                          </button>
                          <button type="button" class="btn btn-info">
                            <i class="fas fa-share mr-1"></i> Transférer
                          </button>
                          <button type="button" class="btn btn-danger">
                            <i class="fas fa-trash mr-1"></i> Supprimer
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card -->
              </div>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
<style>
  .email-content {
    font-size: 14px;
    line-height: 1.6;
  }
  .email-content img {
    max-width: 100%;
    height: auto;
  }
  .attachment-item {
    transition: all 0.3s ease;
  }
  .attachment-item:hover {
    background-color: #f8f9fa;
    border-color: #007bff;
  }
  .callout {
    border-left: 5px solid #17a2b8;
  }
</style>
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

<script>
  $(function () {
    // Enable tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Print functionality
    $('.fa-print').closest('button').click(function() {
      window.print();
    });

    // Download attachment
    $('.fa-download').closest('button').click(function() {
      // Implement download logic here
      alert('Fonctionnalité de téléchargement à implémenter');
    });
  });
</script>
@endsection