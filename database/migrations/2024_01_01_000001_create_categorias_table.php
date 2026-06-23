<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('descricao')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            // Um usuário não pode ter duas categorias com o mesmo nome
            $table->unique(['user_id', 'nome']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
