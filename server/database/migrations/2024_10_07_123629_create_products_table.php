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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('Prd_Name');
            $table->string('Prd_details');
            $table->unsignedBigInteger('Cat_id'); 
            $table->foreign('Cat_id')->references('id')->on('cats')->onDelete('cascade'); 
            $table->decimal('Prd_Price', 9, 2);
            $table->decimal('Prd_CostPrice', 9, 2);
            $table->string('Prd_Img')->default('prd.png');
            $table->integer('Prd_Status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
