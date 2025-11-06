<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversas', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->index(); // número do lead
            $table->enum('tipo', ['recebida', 'enviada']); // se foi recebida ou enviada
            $table->text('mensagem'); // conteúdo da mensagem
            $table->json('dados_extras')->nullable(); // dados adicionais, como timestamp, nome, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversas');
    }
};
