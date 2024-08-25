<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    //  table user = customer

    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->nullable();
            $table->string('username')->unique();
            $table->string('nama_customer');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('foto_profile')->nullable();;
            $table->boolean('is_active')->default(0)->nullable();
            $table->integer('attempt_password')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
