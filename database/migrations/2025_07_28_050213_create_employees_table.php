<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('designation')->nullable(); // Ex: Manager, Operator
            $table->string('department')->nullable();
            $table->string('role')->default('employee'); // admin, manager, hr
            $table->string('status')->default('active'); // active, inactive
            $table->string('profile_image')->nullable(); // store image path
            $table->string('password');
            $table->text('fcm_token')->nullable(); // firebase fcm token
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
