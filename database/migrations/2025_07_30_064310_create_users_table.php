<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id'); // id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->string('email')->unique(); // email VARCHAR(255) UNIQUE NOT NULL
            $table->string('password'); // password VARCHAR(255) NOT NULL
            $table->string('role')->default('admin'); // role VARCHAR(255) NOT NULL DEFAULT 'admin'
            $table->text('fcm_token')->nullable(); // fcm_token TEXT NULL
            $table->timestamps(); // created_at, updated_at TIMESTAMP NULL
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
