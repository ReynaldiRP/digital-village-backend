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
        Schema::create('social_assistance_recipients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('social_assistance_id')
                ->constrained(
                    table: 'social_assistances',
                    column: 'id',
                )->onDelete('cascade');
            $table->foreignUuid('head_of_family_id')
                ->constrained(
                    table: 'head_of_families',
                    column: 'id',
                )->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->longText('reason')->nullable();
            $table->enum('bank', ['BRI', 'BNI', 'BCA', 'Mandiri']);
            $table->integer('account_number');
            $table->string('proof')->nullable();
            $table->enum('status', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_assistance_assistants');
    }
};
