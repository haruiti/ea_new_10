<?php
use App\Services\GoogleCalendarService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Yhc;
use App\Http\Controllers\LeadController;
// 🔹 Rotas para agendamentos (consultas)
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ConversationController;
use Spatie\GoogleCalendar\Event;
use App\Http\Controllers\TrackingController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

// 🔹 Rota original (redirect para WhatsApp)
Route::get('/go-whatsapp', [TrackingController::class, 'redirectToWhatsApp'])->name('go.whatsapp');

// 🔹 Nova rota apenas para retornar JSON (sem redirecionar)
Route::get('/api/get-lead-code', function (Request $request) {
    $leadCode = Str::upper(Str::random(8));

    return response()
        ->json([
            'lead_code' => $leadCode,
            'status' => 'success'
        ])
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
});


Route::get('/test-calendar', function () {
    $service = new GoogleCalendarService();

    $appointment = (object)[
        'date' => '2025-10-28',
        'time' => '15:00',
        'lead' => (object)['name' => 'Teste via rota', 'phone' => '99999-9999']
    ];

    $eventId = $service->createEvent($appointment);

    return "Evento criado com sucesso! ID: " . $eventId;
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Aqui é onde você pode registrar as rotas web para sua aplicação.
*/

// 🔧 Limpeza de cache
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    return "Cache is cleared";
});

// 🏠 Página inicial — redireciona conforme login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/yhc');
    }
    return redirect('/login');
});

// 🔐 Rotas personalizadas de autenticação
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// 🔸 Rota padrão antiga (mantida apenas por segurança)
Route::get('/home', [HomeController::class, 'index'])->name('home');

// 🧩 Rotas do módulo YHC com prefixo e middleware auth
Route::prefix('yhc')->middleware('auth')->group(function () {
    Route::get('/', [Yhc::class, 'index'])->name('yhc.index');
    Route::get('/addcliente', [Yhc::class, 'addcliente']);
    Route::get('/addclienteModal', [Yhc::class, 'index']);
    Route::get('/editcliente', [Yhc::class, 'editcliente']);
    Route::get('/savecliente', [Yhc::class, 'savecliente']);
    Route::get('/vendas/show', [Yhc::class, 'getVenda']);
    Route::get('/addVendaModal', [Yhc::class, 'addVendaModal']);
    Route::get('/saveVenda', [Yhc::class, 'saveVenda']);
    Route::get('/deleteVenda', [Yhc::class, 'deleteVenda']);
    Route::get('/sessoes/show', [Yhc::class, 'getTratamento']);
    Route::get('/editarSessao', [Yhc::class, 'editarSessao']);
    Route::get('/saveSessao', [Yhc::class, 'saveSessao']);
    Route::get('/addSessao', [Yhc::class, 'addSessao']);
    Route::get('/deleteSessao', [Yhc::class, 'deleteSessao']);
    Route::get('/despesaModal', [Yhc::class, 'despesaModal']);
    Route::get('/deleteCliente', [Yhc::class, 'deleteCliente']);
    Route::get('/saveDespesa', [Yhc::class, 'saveDespesa']);
    Route::get('/despesa/show', [Yhc::class, 'getDespesa']);
    Route::get('/importJson', [Yhc::class, 'importJson']);
    Route::get('/deleteDespesa', [Yhc::class, 'deleteDespesa']);
    Route::get('/dashboard', [Yhc::class, 'dashboard']);
    Route::get('/atendimento', [Yhc::class, 'atendimento']);
});

// 🔹 Rotas duplicadas para compatibilidade com JS antigo (sem prefixo)
Route::middleware('auth')->group(function () {
    Route::get('/addcliente', [Yhc::class, 'addcliente']);
    Route::get('/addclienteModal', [Yhc::class, 'index']);
    Route::get('editcliente', [Yhc::class, 'editcliente']);
    Route::get('/savecliente', [Yhc::class, 'savecliente']);
    Route::get('/vendas/show', [Yhc::class, 'getVenda']);
    Route::get('/addVendaModal', [Yhc::class, 'addVendaModal']);
    Route::get('/saveVenda', [Yhc::class, 'saveVenda']);
    Route::get('/deleteVenda', [Yhc::class, 'deleteVenda']);
    Route::get('/sessoes/show', [Yhc::class, 'getTratamento']);
    Route::get('/editarSessao', [Yhc::class, 'editarSessao']);
    Route::get('/saveSessao', [Yhc::class, 'saveSessao']);
    Route::get('/addSessao', [Yhc::class, 'addSessao']);
    Route::get('/deleteSessao', [Yhc::class, 'deleteSessao']);
    Route::get('/despesaModal', [Yhc::class, 'despesaModal']);
    Route::get('/deleteCliente', [Yhc::class, 'deleteCliente']);
    Route::get('/saveDespesa', [Yhc::class, 'saveDespesa']);
    Route::get('/despesa/show', [Yhc::class, 'getDespesa']);
    Route::get('/importJson', [Yhc::class, 'importJson']);
    Route::get('/deleteDespesa', [Yhc::class, 'deleteDespesa']);
    Route::get('/dashboard', [Yhc::class, 'dashboard']);
    Route::get('/atendimento', [Yhc::class, 'atendimento']);

    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{id}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('/appointments/{id}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
});

// 🔸 Rotas de leads
Route::resource('leads', LeadController::class);

Route::get('/run-migrate', function () {
    Artisan::call('migrate', ['--force' => true]);
    return '✅ Migrações executadas com sucesso!';
});
