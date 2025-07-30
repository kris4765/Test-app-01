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
        Schema::create('d_bproftests', function (Blueprint $table) {
            $table->id();

            // Basic fields
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->enum('type', ['simple', 'advanced', 'premium'])->default('simple');

            // Relationships (simulate load with FK)
            $table->unsignedBigInteger('user_id')->nullable();

            // Timestamps
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            // JSON and large text
            $table->json('metadata')->nullable();
            $table->longText('content')->nullable();

            // Indexes
            $table->index(['title', 'status']);
            $table->index('starts_at');

            // Extra fields
            $table->boolean('status')->default(false);
            $table->integer('view_count')->default(0);
            $table->float('rating', 3, 2)->nullable(); // e.g., 4.75
            $table->uuid('reference_id')->unique();
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('d_bproftests');
    }
};
