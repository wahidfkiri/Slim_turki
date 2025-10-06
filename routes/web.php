<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\{
    UserController, IntervenantController, DossierController, DomaineController,
    TimeSheetController, AgendaController, TaskController, FactureController, ProfileController
};

use App\Http\Controllers\EmailController;
use App\Http\Controllers\EmailWebController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();
Route::middleware(['web','auth','active'])
    ->group(function () {
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::resource('users', UserController::class);
Route::get('users/search', [UserController::class, 'search']);

Route::resource('intervenants', IntervenantController::class);
Route::get('intervenants/search', [IntervenantController::class, 'search']);
Route::post('intervenants/{intervenant}/attach-dossier', [IntervenantController::class, 'attachDossier']);
Route::post('intervenants/detach-intervenant', [IntervenantController::class, 'detachIntervenant'])->name('intervenants.detach-intervenant');
Route::delete('intervenant-files/{file}', [IntervenantController::class, 'destroyFile'])->name('intervenants.files.destroy');

Route::resource('dossiers', DossierController::class);
Route::get('dossiers/search', [DossierController::class, 'search']);
Route::post('dossiers/{dossier}/attach-user', [DossierController::class, 'attachUser']);
Route::post('dossiers/{dossier}/attach-intervenant', [DossierController::class, 'attachIntervenant']);
Route::post('dossiers/{dossier}/link-dossier', [DossierController::class, 'linkDossier']);
Route::get('/sous-domaines/by-domaine', [DossierController::class, 'getSousDomainesByDomaine'])->name('sous-domaines.by-domaine');
Route::get('/get-sous-domaines', [DossierController::class, 'getSousDomaines'])->name('get.sous-domaines');
Route::resource('domaines', DomaineController::class);

Route::resource('time-sheets', TimeSheetController::class);
Route::get('dossiers/{dossierId}/time-sheets', [TimeSheetController::class, 'byDossier']);
Route::get('users/{userId}/time-sheets', [TimeSheetController::class, 'byUser']);
Route::get('time-sheets/report', [TimeSheetController::class, 'report']);
Route::get('/timesheets/data', [TimesheetController::class, 'getTimesheetsData'])->name('timesheets.data');

Route::resource('agendas', AgendaController::class);
Route::get('agendas/by-date-range', [AgendaController::class, 'byDateRange']);
Route::get('dossiers/{dossierId}/agendas', [AgendaController::class, 'byDossier']);
Route::get('/get/agendas/data', [AgendaController::class, 'getAgendasData'])->name('agendas.data');

Route::resource('tasks', TaskController::class);
Route::get('tasks/status/{statut}', [TaskController::class, 'byStatus']);
Route::get('users/{userId}/tasks', [TaskController::class, 'byUser']);
Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus']);
Route::get('/get/tasks/data', [TaskController::class, 'getTasksData'])->name('tasks.data');

Route::resource('factures', FactureController::class);
 Route::get('/get/factures/data', [FactureController::class, 'getFacturesData'])->name('factures.data');
 Route::get('/factures/{facture}/pdf', [FactureController::class, 'downloadPDF'])->name('factures.pdf');
Route::get('dossiers/{dossierId}/factures', [FactureController::class, 'byDossier']);
Route::get('factures/status/{statut}', [FactureController::class, 'byStatus']);
Route::patch('factures/{facture}/status', [FactureController::class, 'updateStatus']);
Route::get('factures/generate-number', [FactureController::class, 'generateNumber']);

// Routes profil
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

// Email Web Routes
Route::prefix('email')->group(function () {
        Route::get('/', [EmailWebController::class, 'index'])->name('email.index');
        Route::get('/folder/{folder}', [EmailWebController::class, 'showFolder'])->name('email.folder');
        Route::get('/email/{folder}/{uid}', [EmailWebController::class, 'showEmail'])->name('email.show');
        Route::post('/send', [EmailWebController::class, 'sendEmail'])->name('email.send');
        Route::post('/email/mark-read', [EmailWebController::class, 'markAsRead'])->name('email.mark-read');
        Route::post('/email/move', [EmailWebController::class, 'moveEmail'])->name('email.move');
        Route::delete('/email/delete', [EmailWebController::class, 'deleteEmail'])->name('email.delete');
        Route::post('/reconnect', [EmailWebController::class, 'reconnect'])->name('email.reconnect');
    });

    // Backup Routes
    Route::get('/backups', [App\Http\Controllers\BackupController::class, 'index'])->name('backups.index');
    Route::post('/backups/create', [App\Http\Controllers\BackupController::class, 'createBackup'])->name('backups.create');
    Route::delete('/backups/delete/{filename}', [App\Http\Controllers\BackupController::class, 'deleteBackup'])->name('backups.delete');
    Route::get('/backups/download/{filename}', [App\Http\Controllers\BackupController::class, 'downloadBackup'])->name('backups.download');
    });



    Route::prefix('v1/peakmind')->group(function () {
    Route::get('/test', [EmailController::class, 'testConnection']);
    Route::get('/folders', [EmailController::class, 'getFolders']);
    Route::get('/emails/{folder?}', [EmailController::class, 'getBasicEmails']);
    Route::get('/emails-very-basic/{folder?}', [EmailController::class, 'getVeryBasicEmails']);
    Route::post('/send', [EmailController::class, 'sendEmail']);
    Route::post('/reconnect', [EmailController::class, 'reconnect']);
});

Route::get('/editor', function () {
    $document = [
        'fileType' => 'docx',
        'key' => uniqid(),
        'title' => 'example.docx',
        'url' => url('storage/example.docx'),
    ];

    $config = [
        'document' => [
            'fileType' => $document['fileType'],
            'key' => $document['key'],
            'title' => $document['title'],
            'url' => $document['url'],
        ],
        'editorConfig' => [
            'callbackUrl' => url('/onlyoffice/callback'),
        ],
    ];

    // Add JWT token
    $secret = 'your_secret_here'; // from ONLYOFFICE local.json
    $token = JWT::encode($config, $secret, 'HS256');
    $config['token'] = $token;

    return view('onlyoffice', compact('config'));
});
