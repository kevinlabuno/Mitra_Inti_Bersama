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
        Schema::create('message_ticket', function (Blueprint $table) {
            $table->id();
            $table->string('message', 1000)->nullable(); // Buat kolom message baru
            $table->string('file')->nullable();
            $table->string('id_level_1_agent')->nullable();
            $table->string('id_level_2_agent')->nullable();
            $table->string('id_level_3_agent')->nullable();
            $table->string('id_level_4_agent')->nullable();
            $table->string('id_customer')->nullable();
            $table->string('id_ticket')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_ticket');
    }
};
