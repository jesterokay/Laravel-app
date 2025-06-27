<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('type')->default('percentage'); // e.g., percentage, buy_x_get_y
            $table->decimal('value', 8, 2)->nullable(); // e.g., 10.00 for 10% or flat amount
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('applies_to'); // 'product' or 'sale'
            $table->json('conditions')->nullable(); // e.g., {"min_quantity": 2}
            $table->timestamps();
        });

        Schema::create('product_promotion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('promotion_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('promotion_sale', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('promotion_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_promotion');
        Schema::dropIfExists('promotion_sale');
        Schema::dropIfExists('promotions');
    }
};