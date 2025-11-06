<?php
use Illuminate\Support\Facades\Artisan;

Route::get('/run-new-migrations', function () {
    try {
        Artisan::call('migrate', [
            '--path' => 'database/migrations/2025_11_06_114228_create_leads_tracking_table.php',
            '--force' => true
        ]);

        Artisan::call('migrate', [
            '--path' => 'database/migrations/2025_11_06_120109_add_lead_code_to_leads_table.php',
            '--force' => true
        ]);

        return '✅ Migrations novas executadas com sucesso!';
    } catch (\Exception $e) {
        return '❌ Erro: ' . $e->getMessage();
    }
});
