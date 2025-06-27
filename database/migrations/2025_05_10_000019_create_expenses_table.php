<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->date('expense_date');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('employees')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
};