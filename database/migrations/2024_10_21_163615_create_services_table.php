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
        Schema::create('services', function (Blueprint $table) {

            $table->ulid('id')->primary();
            $table->ulid('user_id');
            $table->ulid('category_id');
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->integer('delivery_time'); // in days
            $table->enum('status', ['active', 'paused', 'deleted'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
