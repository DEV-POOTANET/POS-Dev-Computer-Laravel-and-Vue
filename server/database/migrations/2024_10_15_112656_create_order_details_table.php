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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Ord_id'); 
            $table->unsignedBigInteger('Serial_id'); 
            $table->decimal('OrdDtl_Price', 10, 2); 
            $table->timestamps();

            // ตั้งค่า Foreign Key Constraints
            $table->foreign('Ord_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('Serial_id')->references('id')->on('serials')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
