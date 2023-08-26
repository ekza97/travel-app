<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BackApp\RoleController;
use App\Http\Controllers\BackApp\UserController;
use App\Http\Controllers\BackApp\PermissionController;
use App\Http\Controllers\BackApp\SuratMasukController;
use App\Http\Controllers\BackApp\SuratKeluarController;
use App\Http\Controllers\BackApp\DataMaster\SuratController;
use App\Http\Controllers\BackApp\JamaahController;
use App\Http\Controllers\BackApp\JamaahDocumentController;
use App\Http\Controllers\BackApp\MasterData\AgentController;
use App\Http\Controllers\BackApp\MasterData\AirplaneController;
use App\Http\Controllers\BackApp\MasterData\CategoryController;
use App\Http\Controllers\BackApp\MasterData\DocumentController;
use App\Http\Controllers\BackApp\MasterData\HotelController;
use App\Http\Controllers\BackApp\MasterData\PacketController;
use App\Http\Controllers\BackApp\MasterData\ScheduleController;
use App\Http\Controllers\BackApp\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return to_route('login');
});

Auth::routes([
    'verify'=>false,
    'register'=>true
]);

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    //Master Data Menu
    Route::resource('categories', CategoryController::class)->except(['create','show']);
    Route::resource('documents', DocumentController::class)->except(['create','show']);
    Route::resource('airplanes', AirplaneController::class)->except(['create','show']);
    Route::resource('hotels', HotelController::class)->except(['create','show']);
    Route::resource('agents', AgentController::class)->except(['show']);
    Route::resource('packets', PacketController::class)->except(['show']);
    Route::resource('schedules', ScheduleController::class)->except(['show']);
    //Jamaah Menu
    Route::resource('jamaahs', JamaahController::class)->except(['create','show']);
    Route::get('/form-registration', [JamaahController::class,'create'])->name('form-registration');
    Route::get('/jamaahs/table', [JamaahController::class,'table'])->name('jamaahs.table');
    Route::get('/jamaahs/{id}/documents', [JamaahController::class,'document'])->name('jamaahs.documents');
    Route::post('/jamaahs/document/store', [JamaahController::class, 'document_store'])->name('jamaahs.documents.store');
    //Dokumen Jamaah Menu
    Route::resource('jamaah-documents', JamaahDocumentController::class)->except(['create','show','edit','update']);
    Route::get('/jamaah-documents/table', [JamaahDocumentController::class,'table'])->name('jamaah-documents.table');
    Route::get('/jamaah-documents/{id}/download', [JamaahDocumentController::class,'download'])->name('jamaah-documents.download');

    Route::resource('payments', PaymentController::class)->except(['create','show','edit','update']);
    Route::get('/payments/table', [PaymentController::class,'table'])->name('payments.table');
    Route::get('/payments/{id}/download', [PaymentController::class,'download'])->name('payments.download');

    //get jenis surat select2
    Route::post('selectjenissurat', [SuratMasukController::class,'selectJenisSurat'])->name('selectjenissurat');

    //setting menu
    Route::resource('users', UserController::class)->except(['create']);
    Route::controller(UserController::class)->group(function () {
        Route::put('users/{id}/profile','profile_update')->name('users.profile.update');
        Route::put('users/{id}/password','password_update')->name('users.password.update');
    });
    Route::resource('roles', RoleController::class)->except(['create','show']);
    Route::resource('permissions', PermissionController::class)->only(['index','store','destroy']);
});