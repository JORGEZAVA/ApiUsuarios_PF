<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->binary('image')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string("biografia")->nullable();
            $table->string('role')->default('user');
            $table->boolean("is_banned")->default(false);
            $table->timestamps();
        });
        DB::statement('ALTER TABLE users MODIFY image MEDIUMBLOB');


        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        
    }
};
