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
            $table->id(); 
            $table->unsignedBigInteger('Cus_id'); // Foreign Key (Customer)
            $table->unsignedBigInteger('Emp_id'); // Foreign Key (Employee)
            $table->date('Ord_Date');
            $table->time('Ord_Time'); 
            $table->decimal('Ord_TotalAmount', 10, 2);
            $table->integer('Ord_Payment'); 
            $table->integer('Ord_Status');
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('Cus_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('Emp_id')->references('id')->on('users')->onDelete('cascade');
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
