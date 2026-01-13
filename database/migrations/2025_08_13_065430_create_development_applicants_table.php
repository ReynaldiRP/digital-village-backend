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
        Schema::create('development_applicants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('development_id')
                ->constrained(
                    table: 'developments',
                    column: 'id',
                )
                ->cascadeOnDelete();
            $table->foreignUuid('user_id')
                ->constrained(
                    table: 'users',
                    column: 'id',
                )
                ->cascadeOnDelete();
            $table->enum('status', ['menunggu', 'diterima', 'ditolak'])
                ->default('menunggu');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('development_applicants');
    }
};
