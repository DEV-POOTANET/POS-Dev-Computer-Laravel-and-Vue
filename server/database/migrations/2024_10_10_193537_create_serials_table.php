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
        Schema::create('serials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Prd_id'); 
            $table->foreign('Prd_id')->references('id')->on('products')->onDelete('cascade'); 
            $table->string('Serial_SerialNumber');
            $table->integer('Serial_Status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serials');
    }
};
