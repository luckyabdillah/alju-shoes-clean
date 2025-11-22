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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('transaction_id');
            $table->foreignId('treatment_id');
            $table->foreignId('treatment_details_id');
            $table->string('merk', 100);
            $table->string('type', 50)->default('sepatu');
            $table->string('size', 10)->nullable();
            $table->integer('amount');
            $table->string('description', 255);
            $table->string('treatment_name', 150);
            $table->string('status', 50)->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
