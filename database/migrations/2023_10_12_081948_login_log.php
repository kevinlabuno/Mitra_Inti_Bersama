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
        Schema::create('login_log', function (Blueprint $table) {
            $table->id();
            $table->string('nama_customer')->nullable();
            $table->string('status_code');
            $table->tinyInteger('status')->comment('0:failed, 1:success');
            $table->string('message');
            $table->json('data');
            $table->string('id_customer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_log');
    }
};
