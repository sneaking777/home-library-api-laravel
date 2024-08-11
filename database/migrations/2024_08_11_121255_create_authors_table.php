<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->id()->comment('Идентификатор');
            $table->string('surname', 100)->comment('Фамилия автора');
            $table->string('name', 100)->comment('Имя автора');
            $table->string('patronymic', 100)->nullable()->comment('Отчество автора');
            $table->timestamps();
            $table->comment('Таблица хранит в себе информацию о авторах книг');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authors');
    }
};
