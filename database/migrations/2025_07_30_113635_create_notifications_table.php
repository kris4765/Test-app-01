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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('send_from')->nullable(); // email or user_id
            $table->string('send_to');               // email or user_id
            $table->text('data');                    // message or payload
            $table->boolean('is_read')->default(false);
            $table->timestamps();                    // created_at = for "x time ago"
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
