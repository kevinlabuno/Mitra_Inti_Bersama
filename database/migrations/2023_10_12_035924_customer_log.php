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
        Schema::create('user_log', function (Blueprint $table) {
            $table->id();
            $table->string('nama_customer'); // ini di ambil dari table user = customer
            $table->string('message')->nullable();
            $table->tinyInteger('status')->comment('0:failed, 1:success');
            $table->json('data');
            $table->string('id_customer')->nullable(); // ini di ambil dari table user = customer
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_log');
    }
};
