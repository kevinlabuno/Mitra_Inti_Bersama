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
        Schema::create('reset_password_or_pin', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('token');
            $table->char('action')->nullable()->comment('1: reset password, 2: reset userId');
            $table->tinyInteger('status')->comment('0:failed, 1:success');
            $table->string('message');
            $table->json('data');
            $table->string('id_user')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reset_password_or_pin');

    }
};
