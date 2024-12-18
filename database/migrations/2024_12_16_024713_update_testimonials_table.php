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
        Schema::table('testimonials', function (Blueprint $table) {
            $table->foreignId('selling_id')->nullable()->constrained('sellings')->onDelete('cascade');
            $table->enum('rate', ['1', '2', '3', '4', '5'])->change();
            $table->string('comment')->nullable()->change();
            $table->dropColumn('photo');
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            //
        });
    }
};
