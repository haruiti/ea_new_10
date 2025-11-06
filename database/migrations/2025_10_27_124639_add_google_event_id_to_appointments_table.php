<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adiciona o campo google_event_id à tabela appointments
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasColumn('appointments', 'google_event_id')) {
                $table->string('google_event_id')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverte a adição do campo
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'google_event_id')) {
                $table->dropColumn('google_event_id');
            }
        });
    }
};
