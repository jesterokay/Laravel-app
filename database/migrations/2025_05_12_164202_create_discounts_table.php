<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('percentage'); // e.g., percentage, flat
            $table->decimal('value', 8, 2); // e.g., 10.00 for 10% or flat amount
            $table->text('description')->nullable();
            $table->string('applies_to'); // 'product' or 'sale'
            $table->timestamps();
        });

        Schema::create('discount_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('discount_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('discount_sale', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('discount_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('discount_product');
        Schema::dropIfExists('discount_sale');
        Schema::dropIfExists('discounts');
    }
};