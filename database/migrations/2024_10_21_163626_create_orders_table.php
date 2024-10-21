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
        Schema::create('orders', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('service_id');
            $table->ulid('client_id');
            $table->ulid('freelancer_id');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->decimal('amount', 10, 2);
            $table->timestamp('due_date');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('freelancer_id')->references('id')->on('users')->onDelete('cascade');
  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
