<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name') }}</title>
    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('logo1.png') }}">
    <meta name="theme-color" content="#ffffff">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css?v=3.2.0') }}">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
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
    /* Classe de base pour l'avatar circulaire */
.user-avatar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    color: white;
    font-weight: bold;
    font-size: 14px;
    text-transform: uppercase;
}

/* Variantes de taille */
.user-avatar-sm {
    width: 30px;
    height: 30px;
    font-size: 12px;
}

.user-avatar-lg {
    width: 50px;
    height: 50px;
    font-size: 16px;
}

.user-avatar-xl {
    width: 60px;
    height: 60px;
    font-size: 18px;
}

    #calendar {
        height: 600px;
        background-color: white;
    }
    .fc-header-toolbar {
        padding: 10px;
        margin-bottom: 0 !important;
    }
    .fc-toolbar-chunk {
        display: flex;
        align-items: center;
    }
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
    .event-details h4 {
        color: #3c8dbc;
        margin-bottom: 15px;
    }
    .event-details p {
        margin-bottom: 8px;
    }
    .fc-event {
        cursor: pointer;
    }
    .fc-day-today {
        background-color: #e8f4fd !important;
    }
    input[type="color"] {
        height: 38px;
        padding: 2px;
    }
    .user-dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-trigger {
    display: flex;
    align-items: center;
    text-decoration: none;
    padding: 8px;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.dropdown-trigger:hover {
    background-color: rgba(0,0,0,0.05);
}

.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 12px;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    padding: 8px 0;
    min-width: 180px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 1000;
}

.user-dropdown.active .dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-item {
    display: flex;
    align-items: center;
    padding: 10px 16px;
    text-decoration: none;
    color: #333;
    transition: background-color 0.2s;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
    font-size: 14px;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.dropdown-item i {
    width: 20px;
    margin-right: 10px;
    color: #6c757d;
}

.logout-btn {
    color: #dc3545;
}

.logout-btn:hover {
    background-color: #fff5f5;
    color: #dc3545;
}

.dropdown-divider {
    height: 1px;
    background-color: #e9ecef;
    margin: 8px 0;
}
.hidden-item {
    display: none;
}
</style>
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
    <x-header />
    <x-side-nav />
                @yield('content')
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

<!-- DataTables & Plugins -->
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>

<!-- Validation & Select2 -->
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>



<!-- Dans layouts/app.blade.php avant la fermeture du body -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- Scripts personnalisés (chargés en dernier) -->
<script src="{{ asset('assets/custom/intervenant-filter.js') }}"></script>
<script src="{{ asset('assets/custom/dossier-filter.js') }}"></script>
<script src="{{ asset('assets/custom/users.js') }}"></script>
<script src="{{ asset('assets/custom/timesheets.js') }}"></script>    
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to handle hiding selected items
    function setupSelectHiding(mainSelectId, multiSelectId) {
        const mainSelect = document.getElementById(mainSelectId);
        const multiSelect = document.getElementById(multiSelectId);
        
        if (!mainSelect || !multiSelect) return;
        
        function updateMultiSelect() {
            const selectedId = mainSelect.value;
            
            // Reset all options to be visible
            for (let option of multiSelect.options) {
                option.classList.remove('hidden-item');
            }
            
            // Hide the selected item
            if (selectedId) {
                for (let option of multiSelect.options) {
                    if (option.value === selectedId) {
                        option.classList.add('hidden-item');
                        option.selected = false;
                    }
                }
            }
        }
        
        mainSelect.addEventListener('change', updateMultiSelect);
        updateMultiSelect(); // Initialize
    }
    
    // Setup for Intervenants tab
    setupSelectHiding('client_id', 'autres_intervenants');
    
    // Setup for Équipe tab
    setupSelectHiding('avocat_id', 'equipe_supplementaire');
});
</script>
</body>
</html>