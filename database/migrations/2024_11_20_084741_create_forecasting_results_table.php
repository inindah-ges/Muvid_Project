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
        Schema::create('forecasting_results', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('raw_material_id')->constrained('raw_materials')->onDelete('cascade');
            $table->date('date');
            $table->decimal('predicted_amount', 8, 2);
            $table->decimal('actual_usage', 8, 1)->nullable();
            $table->decimal('error_rate', 8, 2)->nullable();
            $table->string('forecasting_method');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forecasting_results');
    }
};
