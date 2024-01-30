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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('customer_id');
            $table->foreignId('outlet_id');
            $table->enum('transaction_type', ['dropoff', 'pickup-delivery'])->default('dropoff');
            $table->enum('payment_method', ['cash', 'qris'])->default('cash');
            $table->enum('payment_time', ['now', 'later'])->default('now');
            $table->datetime('transaction_start');
            $table->datetime('transaction_end');
            $table->enum('payment_status', ['paid', 'unpaid'])->default('unpaid');
            $table->integer('total_items');
            $table->integer('cost');
            $table->integer('other_cost')->nullable();
            $table->integer('total_amount');
            $table->string('invoice_no', 100);
            $table->string('picture_evidence')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->string('transaction_status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
