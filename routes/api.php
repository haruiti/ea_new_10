<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\AvailableAppointmentsController;

// -------------------------
// APPOINTMENTS
// -------------------------
Route::post('/appointments/{id}/cancel', [AppointmentController::class, 'apiCancel']);
Route::put('/appointments/{id}', [AppointmentController::class, 'apiUpdate']);
Route::get('/appointments/disponiveis', [AvailableAppointmentsController::class, 'index']);
Route::post('/appointments', [AppointmentController::class, 'apiStore']);
Route::get('/leads/{id}/appointments', [AppointmentController::class, 'apiByLead']);

// -------------------------
// LEADS
// -------------------------
Route::get('/leads/por-numero/{numero}', [LeadController::class, 'porNumero']);
Route::post('/leads/obter-ou-criar', [LeadController::class, 'obterOuCriar']);
Route::get('/leads/corrigir-nomes', [LeadController::class, 'corrigirNomesImportados']);
Route::put('/leads/updateByNumber/{numero}', [LeadController::class, 'updateByNumber']);
Route::put('/leads/update-name', [LeadController::class, 'updateName']);
Route::post('/receber-lead', [LeadController::class, 'receberDoFormulario']);
Route::post('/leads', [LeadController::class, 'apiStore']);
Route::post('/receber-lead-click', [LeadController::class, 'registrarCliqueWhatsApp']);




// -------------------------
// CONVERSAS
// -------------------------
Route::get('/conversas', [ConversationController::class, 'index']);
Route::post('/conversas', [ConversationController::class, 'store']);
Route::get('/conversas/historico/{numero}', [ConversationController::class, 'historico']);
Route::get('/conversas/recentes', [ConversationController::class, 'ultimosTresMeses']);
Route::get('/conversas/campanhas', [ConversationController::class, 'listarCampanhas']);
Route::post('/conversas/lote', [ConversationController::class, 'storeBatch']);
