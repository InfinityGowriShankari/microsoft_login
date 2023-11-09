<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MicrosoftLoginController;
use App\Http\Controllers\MicrosoftGraphController;
use App\Http\Controllers\NoteBookController;
use App\Http\Controllers\OneDriveController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\SSOController;

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
    return view('welcome');
});

Route::get('/auth/microsoft', [MicrosoftLoginController::class, 'redirectToMicrosoft'])->name('microsoft.login');
Route::any('/auth/microsoft/callback', [MicrosoftLoginController::class, 'handleMicrosoftCallback']);

Route::get('/auth/microsoft/teams', [MicrosoftLoginController::class, 'redirectToMicrosoftTeams'])->name('microsoft.teams_login');
Route::get('/auth/microsoft/teams_callback', [MicrosoftLoginController::class, 'handleMicrosoftTeamsCallback']);

Route::get('microsoft/me', [MicrosoftGraphController::class, 'getMe'])->name('microsoft.me');
Route::post('microsoft/me/events', [MicrosoftGraphController::class, 'createEvent'])->name('microsoft.createEvent');

Route::get('microsoft/me/onenote/notebooks', [NoteBookController::class, 'listOneNotePages'])->name('microsoft.onenote');
Route::post('microsoft/onenote/create_notebooks', [NoteBookController::class, 'createNoteBook'])->name('microsoft.createNoteBook');

Route::get('microsoft/me/onedrive', [OneDriveController::class, 'listOneDrive'])->name('microsoft.listOneDrive');
Route::post('microsoft/onenote/create_file', [OneDriveController::class, 'createFile'])->name('microsoft.create_onedrive_file');

Route::get('calendar/list', [CalendarController::class, 'calendarList'])->name('microsoft.calendar_list');

Route::get('/microsoft/sso_process', [SSOController::class, 'ssoUser'])->name('microsoft.sso_user');
Route::any('/microsoft/sso/callback', [SSOController::class, 'handleSSOCallback'])->name('microsoft.redirect');