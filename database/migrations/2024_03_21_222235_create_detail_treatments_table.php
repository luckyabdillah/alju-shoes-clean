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
        Schema::create('detail_treatments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('treatment_id');
            $table->string('name', 100);
            $table->integer('cost');
            $table->integer('processing_time');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_treatments');
    }
};