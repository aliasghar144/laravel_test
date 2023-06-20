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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('address')->nullable();
            $table->string('name')->nullable();
            $table->string('lastname')->nullable();
            $table->boolean('request')->default(false);
            $table->boolean('isactive')->default(false);
            $table->boolean('isadmin')->default(false);
            $table->String('phone',)->unique();
            $table->double('lat')->nullable();
            $table->double('long')->nullable();
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
