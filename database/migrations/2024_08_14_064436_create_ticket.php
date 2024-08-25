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
        Schema::create('ticket', function (Blueprint $table) {
            $table->id();
            $table->string('nomer_ticket')->nullable();
            $table->string('nama_customer')->nullable();
            $table->string('judul')->nullable();
            $table->string('deskripsi', 1000)->nullable();
            $table->string('project_name')->nullable();
            $table->string('note')->nullable();
            $table->string('tipe_severity')->nullable();
            $table->string('kategory')->nullable();
            $table->string('lampiran')->nullable();
            $table->boolean('level_1')->default(0)->comment('ditunjukkan untuk EOS (Engineer On Site) atau Helpdesk')->nullable();
            $table->boolean('level_2')->default(0)->comment('ditunjukkan untuk Engineer atau partnership')->nullable();
            $table->boolean('level_3')->default(0)->comment('ditunjukkan untuk Leader Engineer')->nullable();
            $table->boolean('level_4')->default(0)->comment('ditunjukkan untuk management.')->nullable();
            $table->string('penugasan')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status_open_tiket')->nullable();
            $table->string('id_customer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket');
    }
};
